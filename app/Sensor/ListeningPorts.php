<?php

namespace App\Sensor;

class ListeningPorts extends \App\AbstractSensor
{

    const REGEXP = "/(tcp6|tcp|udp6|udp)\s*\d\s*\d\s*(\S*):(\d*).*LISTEN\s*(\S*)/m";

    public function report(array $records) : string
    {
        $record = end($records);

        // "netstat-listen-tcp" "netstat-listen-udp"
        if (! isset($record["netstat-listen-udp"])
                && ! isset($record["netstat-listen-tcp"])) {
            return "<p>No data available...</p>";
        }

        $ports = array_merge(
            $this->parse($record["netstat-listen-tcp"]),
            $this->parse($record["netstat-listen-udp"])
        );

        usort(
            $ports,
            function (ListeningPort $port1, ListeningPort $port2) {
                    return $port1->port - $port2->port;
            }
        );

        $return = "<table class='table table-sm'>";
        $return .= "<tr>"
                . "<th>Port</th>"
                . "<th>Proto</th>"
                . "<th>Bind address</th>"
                . "<th>Process</th>"
                . "</tr>";
        foreach ($ports as $port) {
            $return .= "<tr>"
                    . "<td>" . $port->port . "</td>"
                    . "<td>" . $port->proto . "</td>"
                    . "<td>" . $port->bind . "</td>"
                    . "<td>" . $port->process . "</td>"
                    . "</tr>";
        }
        $return .= "</table>";
        return $return;
    }

    public function status(array $records) : int
    {
        return \App\Status::OK;
    }

    /**
     *
     * @param string $string
     * @return \App\Sensor\ListeningPort[]
     */
    public function parse(?string $string)
    {
        if ($string == null) {
            return [];
        }

        $values = [];
        preg_match_all(self::REGEXP, $string, $values);

        $ports = [];
        $count = count($values[1]);
        for ($i = 0; $i < $count; $i++) {
            $port = new ListeningPort();
            $port->proto = $values[1][$i];
            $port->bind = $values[2][$i];
            $port->port = $values[3][$i];
            $port->process = $values[4][$i];
            $ports[] = $port;
        }
        return $ports;
    }
}
