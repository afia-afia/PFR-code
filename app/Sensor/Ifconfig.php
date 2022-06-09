<?php

namespace App\Sensor;

use \App\AbstractSensor;

class Ifconfig extends AbstractSensor
{

    public function report(array $records) : string
    {

        $record = end($records);
        if (! isset($record['ifconfig'])) {
            return "<p>No data available...</p>";
        }

        $interfaces = $this->parseIfconfigRecord($record);
        return view("agent.ifconfig", [
            "interfaces" => $interfaces]);
    }

    public function points(array $records)
    {
        // Compute the time ordered list of arrays of interfaces
        $interfaces = [];
        foreach ($records as $record) {
            $interfaces[] = $this->parseIfconfigRecord($record);
        }

        // Foreach interface, compute the array of points
        $dataset = [];
        $current_value = [];
        foreach ($interfaces[0] as $interface) {
            $iname = $interface->name;
            $dataset[$iname . "/TX"] = [
                "name" => $iname . "/TX",
                "points" => []
            ];

            $dataset[$iname . "/RX"] = [
                "name" => $iname . "/RX",
                "points" => []
            ];
            $current_value[$interface->name] = $interface;
        }

        for ($i = 1; $i < count($interfaces); $i++) {
            foreach ($interfaces[$i] as $interface) {
                $iname = $interface->name;
                $previous_value = $current_value[$iname];
                $delta_time = $interface->time - $previous_value->time;

                // RX
                $delta = $interface->rx - $previous_value->rx;
                if ($delta < 0) {
                    // Can happen after a reboot...
                    $delta = 0;
                }
                $dataset[$iname . "/RX"]["points"][] = new Point(
                    $interface->time * 1000,
                    round(8 / 1024 * $delta / ($delta_time+1))
                );

                // TX
                $delta = $interface->tx - $previous_value->tx;
                if ($delta < 0) {
                    // Can happen after a reboot...
                    $delta = 0;
                }
                $dataset[$iname . "/TX"]["points"][] = new Point(
                    $interface->time * 1000,
                    round(8 / 1024 * $delta / ($delta_time+1))
                );

                // Keep current value for next record
                $current_value[$iname] = $interface;
            }
        }

        return array_values($dataset);
    }

    public function status(array $records) : int
    {
        return \App\Status::OK;
    }

    const IFNAME = '/^(?|(\S+)\s+Link encap:|(\S+): flags)/m';
    const IPV4 = '/^\s+inet (?>addr:)?(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/m';
    const RXTX = '/^\s+RX bytes:(\d+) .*TX bytes:(\d+)/m';
    const RX = '/^\s+RX packets (?>\d+)  bytes (\d+)/m';
    const TX = '/^\s+TX packets (?>\d+)  bytes (\d+)/m';

    public function parseIfconfigRecord($record)
    {
        $interfaces = $this->parseIfconfig($record->ifconfig);
        foreach ($interfaces as $interface) {
            $interface->time = $record->time;
        }

        return $interfaces;
    }

    /**
     * Parse the result of the ifconfig command, skipping every virtual
     * interfaces (docker, br, lo)
     * @param string $string
     * @return \App\Sensor\NetworkInterface[]
     */
    public function parseIfconfig(string $string)
    {

        $allowed_prefixes = ["en", "eth", "wl", "venet"];

        if ($string == null) {
            return [];
        }

        $interfaces = [];
        $lines = explode("\n", $string);
        $if = null;
        foreach ($lines as $line) {
            $name = $this->pregMatchOne(self::IFNAME, $line);

            if ($name !== false) {
                // Starting the section of a new interface
                $if = new NetworkInterface();
                $interfaces[] = $if;
                $if->name = $name;
                continue;
            }

            $ip = $this->pregMatchOne(self::IPV4, $line);
            if ($ip !== false) {
                $if->address = $ip;
                continue;
            }

            $matches = [];
            if (preg_match(self::RXTX, $line, $matches) === 1) {
                $if->rx = $matches[1];
                $if->tx = $matches[2];
                continue;
            }

            $rx = $this->pregMatchOne(self::RX, $line);
            if ($rx !== false) {
                $if->rx = $rx;
            }

            $tx = $this->pregMatchOne(self::TX, $line);
            if ($tx !== false) {
                $if->tx = $tx;
            }
        }

        // filter out uninteresting interfaces
        $filtered = [];
        foreach ($interfaces as $interface) {
            if (\starts_with($interface->name, $allowed_prefixes)) {
                $filtered[] = $interface;
            }
        }

        return $filtered;
    }

    public function pregMatchOne(string $pattern, string $string, int $match_group = 1)
    {
        $matches = [];
        if (preg_match($pattern, $string, $matches) === 1) {
            return $matches[$match_group];
        }

        return false;
    }
}