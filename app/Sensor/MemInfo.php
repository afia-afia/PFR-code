<?php

namespace App\Sensor;

use \App\AbstractSensor;

class MemInfo extends AbstractSensor
{

    public function report(array $records) : string
    {

        return view("agent.meminfo", []);
    }

    public function usedMemoryPoints(array $records)
    {
        $used = [];
        foreach ($records as $record) {
            $meminfo = $this->parseMeminfo($record->memory);
            $used[] = new Point(
                $record->time * 1000,
                $meminfo->used() / 1000
            );
        }

        return $used;
    }

    public function cachedMemoryPoints(array $records)
    {
        $points = [];
        foreach ($records as $record) {
            $meminfo = $this->parseMeminfo($record->memory);
            $points[] = new Point(
                $record->time * 1000,
                $meminfo->cached / 1000
            );
        }

        return $points;
    }

    public function status(array $records) : int
    {
       // foreach ($records as $record) {
           $record=end($records);
            $mem = $this->parseMeminfo($record->memory);
            if ($mem->usedRatio() > 0.8) {
                return  \App\Status::WARNING;
            }
             
       // }

        return \App\Status::OK;
    }

    // used = total - free - cached
    const MEMTOTAL = "/^MemTotal:\\s+([0-9]+) kB$/m";
    const MEMFREE = "/^MemFree:\\s+([0-9]+) kB$/m";
    const MEMCACHED = "/^Cached:\\s+([0-9]+) kB$/m";

    public function parseMeminfo($string)
    {
        return new Memory(
            $this->pregMatchOne(self::MEMTOTAL, $string),
            $this->pregMatchOne(self::MEMFREE, $string),
            $this->pregMatchOne(self::MEMCACHED, $string)
        );
    }

    public function pregMatchOne($pattern, $string)
    {
        $matches = array();
        preg_match($pattern, $string, $matches);
        return $matches[1];
    }
}
