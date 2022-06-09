<?php

namespace App\Sensor;


class Date extends \App\AbstractSensor
{
    
    public function report(array $records) : string
    {
        return "<p>Time drift: " . $this->delta(end($records)) . " seconds</p>";
    }

    public function status(array $records) : int
    {
        $delta = $this->delta(end($records));

        if ($delta == 0) {
            return \App\Status::OK;
        }
        if ($delta == null) {
            return \App\Status::UNKNOWN;
        }

        if (abs($delta) > 10) {
            return \App\Status::WARNING;
        }

        return \App\Status::OK;
    }

    public function delta($record)
    {
        if (! isset($record["date"])) {
            return null;
        }

        return $record->date - $record->time;
    }
}
