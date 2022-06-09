<?php

namespace App;

use Illuminate\Support\Facades\Log;

class SensorWrapper
{
    private $sensor;
    private $records;

    private $report;
    private $status;

    public function __construct(Sensor $sensor, array $records)
    {
        $this->sensor = $sensor;
        $this->records = $records;
    }

    public function id() : string
    {
        return \get_class($this->sensor);
    }

    public function name(): string
    {
        return $this->sensor->name();
    }

    public function report(): string
    {
        if (is_null($this->report)) {
            try {
                $this->report = $this->sensor->report($this->records);
            } catch (\Exception $ex) {
                Log::error('Sensor failed : ' . $ex->getTraceAsString());
                $this->report = "<p>Sensor " . $this->name() . " failed :-(</p>";
            }
        }

        return $this->report;
    }

    public function status(): Status
    {
        if (is_null($this->status)) {
            try {
                $this->status = new Status($this->sensor->status($this->records));
            } catch (\Exception $ex) {
                Log::error('Sensor failed : ' . $ex->getTraceAsString());
                $this->status = new Status(Status::UNKNOWN);
            }
        }

        return $this->status;
    }
}
