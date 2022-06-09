<?php

namespace App\Sensor;


class CPUtemperature extends \App\AbstractSensor
{

    const REGEXP = "/^(Core \d+):\s+\+(\d+\.\d+)/m";
    const REGEXPCPU= "/^(Package id)+\s+(\d):\s+\+(\d+\.\d+)°C\s+\(high\s=\s\+\d+\.\d°C,\scrit\s=\s\+(\d+\.\d+)°C\)/m";

    public function report(array $records) : string
    {
        $record = end($records);
        if (! isset($record["cpu-temperature"])) {
            return "<p>No data available...</p>"
                . "<p>Maybe <code>sensors</code> is not installed.</p>"
                . "<p>You can install it with <code>sudo apt install lm-sensors</code></p>";
        }
        $Cores = self::parseCPUtemperature($record['cpu-temperature']);
        $CPUS=self::parseCPU($record['cpu-temperature']);
        $return = "<table class='table table-sm'>";
        $return .= "<tr><th>Name</th><th>Temperature (°C)</th><th>T°crit (°C)</th></tr>";
        foreach ($CPUS as $CPU) {
            $return .= "<tr><td>" . "<b>" ."CPU " . $CPU->number . "</td><td>"
                    . "<b>" . $CPU->value  . "</td><td>" . "<b>" . $CPU->critvalue . "</td></tr>";
            foreach ($Cores as $Core) {
                if ($Core->number == $CPU->number) {
                    $return .= "<tr><td>" . $Core->name . "</td><td>"
                            . $Core->corevalue  . "</td><td>" . " " . "</td></tr>";
                }
            }
        }
        $return .= "</table>";
        return $return;
    }

    public function status(array $records) : int
    {
        $record = end($records);
        if (! isset($record["cpu-temperature"])) {
            return \App\Status::UNKNOWN;
        }

        $all_status = [];
        foreach (self::parseCPU($record['cpu-temperature']) as $CPU) {
            /* @var $CPU Cpu */
            $status = \App\Status::OK;
            if ($CPU->value > $CPU->critvalue) {
                $status = \App\Status::WARNING;
            }
            foreach (self::parseCPUtemperature($record['cpu-temperature']) as $Core) {
                if ($Core->number == $CPU->number) {
                    if ($Core->value > $CPU->critvalue) {
                        $status = \App\Status::WARNING;
                    }
                }
            }
            $all_status[] = $status;
        }

        if (count($all_status) < 1) {
            return \App\Status::UNKNOWN;
        }

        return max($all_status);
    }

    public static function parse(string $string) //cores only
    {
        $values = array();
        preg_match_all(self::REGEXP, $string, $values);
        $temperatures = array();
        $count = count($values[1]);
        for ($i = 0; $i < $count; $i++) {
            $CPUTemp = new Temperature();
            $CPUTemp->name = $values[1][$i];
            $CPUTemp->value = $values[2][$i];
            $temperatures[] = $CPUTemp;
        }
        return $temperatures;
    }

    public static function parseCPU(string $string) //cpus only
    {
        $values = array();
        preg_match_all(self::REGEXPCPU, $string, $values);
        $CPUS = array();
        $count = count($values[1]);
        for ($i = 0; $i < $count; $i++) {
            $CPU = new Cpu();
            $CPU->number = $values[2][$i];
            $CPU->value = $values[3][$i];
            $CPU->critvalue = $values[4][$i];
            $CPUS[] = $CPU;
        }
        return $CPUS;
    }
    public function parseCPUtemperature($string) //cores (to associate with cpus only in report() )
    {
        if ($string == null) {
            return [];
        }

        $current_cpu = new Cpu();
        $CPUS=[];
        $Cores=[];
        $lines=explode("\n", $string);
        foreach ($lines as $line) {
            $matchesCPU = array();
            if (preg_match(self::REGEXPCPU, $line, $matchesCPU) === 1) {
                $current_cpu = new Cpu();
                $current_cpu->number = $matchesCPU[2];
                $CPUS[]=$current_cpu;
                continue;
            }
            $matchesCore = array();
            if (preg_match(self::REGEXP, $line, $matchesCore) === 1) {
                $Core=new Temperature();
                $Core->name = $matchesCore[1];
                $Core->corevalue = $matchesCore[2];
                $Core->number = $current_cpu->number;
                $Cores[] = $Core;
                continue;
            }
        }
        return $Cores;
    }
    public function pregMatchOne($pattern, $string)
    {
        $matches = array();
        if (preg_match($pattern, $string, $matches) === 1) {
            return $matches[1];
        }

        return false;
    }
}
