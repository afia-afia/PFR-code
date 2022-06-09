<?php

namespace App\Sensor;

use Carbon\Carbon;


class PartitionDelta
{
    private $start;
    private $end;

    public function __construct(Partition $start, Partition $end)
    {
        if ($start->filesystem !== $end->filesystem) {
            throw new \Exception("Comparing different filesystems!");
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function filesystem() : string
    {
        return $this->start->filesystem;
    }

    /**
     * Return difference of the number of used blocks.
     * @return int
     */
    private function deltaBlocks() : int
    {
        return $this->end->used - $this->start->used;
    }

    /**
     * Return time difference between 2 partitions
     * @return int
     */
    private function deltaT() : int
    {
        return $this->end->time - $this->start->time;
    }

    /**
     * Time in second, before this partition gets full.
     * @return int
     */
    public function timeUntillFull() : int
    {
        if ($this->deltaBlocks() <= 0) {
            return PHP_INT_MAX;
        }

        return ($this->end->blocks - $this->end->used) / $this->deltaBlocks()
                * $this->deltaT();
    }

    public function timeUntilFullForHumans() : string
    {
        return Carbon::createFromTimeStamp($this->timeUntillFull())->diffForHumans();
    }
}
