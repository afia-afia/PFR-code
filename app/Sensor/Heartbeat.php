<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Sensor;

/**
 * Description of Reboot
 *
 * @author tibo
 */
class Heartbeat extends \App\AbstractSensor
{
    //put your code here
    public function report(array $records) : string
    {
        return "<p>Last heartbeat received "
        . $this->lastRecordTime(end($records))->diffForHumans() . "</p>";
    }

    /**
     *
     * @return \Carbon\Carbon
     */
    public function lastRecordTime($record) : \Carbon\Carbon
    {
        if ($record === null) {
            return \Carbon\Carbon::createFromTimestamp(0);
        }

        return \Carbon\Carbon::createFromTimestamp($record->time);
    }


    public function status(array $records) : int
    {
        $record = end($records);

        if ($record === null) {
            $delta = PHP_INT_MAX;
        } else {
            $delta = \time() - $record->time;
        }

        if ($delta > 900) {
            return \App\Status::WARNING;
        }

        return \App\Status::OK;
    }
}
