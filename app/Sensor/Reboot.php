<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Sensor;


class Reboot extends \App\AbstractSensor
{
    //put your code here
    public function report(array $records) : string
    {
        return "<p>Reboot required: "
            . $this->statusHTML($records)
            . "</p>";
    }

    public function statusHTML(array $records)
    {
        switch ($this->status($records)) {
            case \App\Status::OK:
                return "no";

            case \App\Status::WARNING:
                return "yes";

            default:
                return "?";
        }
    }

    public function status(array $records) : int
    {
        $record = end($records);
        if (! isset($record['reboot'])) {
            return \App\Status::UNKNOWN;
        }

        if ($record->reboot) {
            return \App\Status::WARNING;
        }

        return \App\Status::OK;
    }

    public function name(): string
    {
        return "Reboot required";
    }
}
