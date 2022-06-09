<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Sensor;


class Ssacli extends \App\AbstractSensor
{
    const REGEXP = "/\s*physicaldrive .*\(port (.*):box (\d*):bay (\d*), (.*), (.*), (\w*)\)/";

    public function report(array $records) : string
    {
        $record = end($records);
        if (! isset($record['ssacli'])) {
            return "<p>No data available...</p>";
        }

        $disks = $this->parse($record->ssacli);
        $return = "<table class='table table-sm'>"
                . "<tr>"
                . "<th>Port</th>"
                . "<th>Box</th>"
                . "<th>Bay</th>"
                . "<th>Type</th>"
                . "<th>Size</th>"
                . "<th>Status</th>"
                . "</tr>";
        foreach ($disks as $disk) {
            $return .= "<tr>"
                    . "<td>" . $disk->port . "</td>"
                    . "<td>" . $disk->box . "</td>"
                    . "<td>" . $disk->bay . "</td>"
                    . "<td>" . $disk->type . "</td>"
                    . "<td>" . $disk->size . "</td>"
                    . "<td>" . $disk->status . "</td>"
                    . "</tr>";
        }
        $return .= "</table>";
        return $return;
    }

    public function status(array $records) : int
    {
        $record = end($records);
        if (! isset($record['ssacli'])) {
            return \App\Status::UNKNOWN;
        }

        $disks = $this->parse($record->ssacli);
        foreach ($disks as $disk) {
            if ($disk->status != "OK") {
                return \App\Status::WARNING;
            }
        }

        return \App\Status::OK;
    }

    /**
     *
     * @param string $string
     * @return \App\Sensor\Disk[]
     */
    public function parse($string)
    {
        $values = array();
        preg_match_all(self::REGEXP, $string, $values);
        $disks = array();
        $count = count($values[1]);
        for ($i = 0; $i < $count; $i++) {
            $disk = new Disk();
            $disk->port = $values[1][$i];
            $disk->box = $values[2][$i];
            $disk->bay = $values[3][$i];
            $disk->type = $values[4][$i];
            $disk->size = $values[5][$i];
            $disk->status = $values[6][$i];
            $disks[] = $disk;
        }
        return $disks;
    }
}
