<?php

namespace App\Sensor;


class USBtemperature extends \App\AbstractSensor
{
    //get device responce (8 bytes) :
    const REGEXP = "/^(80 80)\s*([A-z\/0-9]+) \s*([A-z\/0-9]+)/m";

    public function report(array $records) : string
    {
        $record = end($records);
        if (! isset($record["TEMPer"])) {
            return "<p>No data available...</p>"
                . "<p>Maybe <code>TEMPer</code> is not installed.</p>";
        }
        $temper = self::parse($record['TEMPer']);
        $return= "<p>Ambient temperature (USB TEMPer) : " . $temper->temp[1] . "." . $temper->temp[2] . " °C " . "</p>";
        return $return;
    }

    public function status(array $records) : int
    {
        $record = end($records);
        if (! isset($record["TEMPer"])) {
            return \App\Status::UNKNOWN;
        }
        $status = \App\Status::OK;
        $USBTemp = self::parse($record['TEMPer']);
        if ((int)($USBTemp->temp[1]) > 60) {
            $status = \App\Status::WARNING;
        }
        return $status;
    }

    public static function parse(string $string)
    {
        $values = array();
        preg_match_all(self::REGEXP, $string, $values); //get 8 bytes response from TEMPerUSB device
        $USBTemp = new Temper();
        $USBTemp->part1 = implode($values[2]);
        $USBTemp->part2 = implode($values[3]);
        $USBTemp->conversion(); //1st element = integer part, 2th = decimal part
        $temper=$USBTemp;
        return $temper;
    }
}
