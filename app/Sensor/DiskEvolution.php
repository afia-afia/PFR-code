<?php

namespace App\Sensor;

class DiskEvolution extends \App\AbstractSensor
{
    public function report(array $records) : string
    {
        return view("agent.diskevolution", [
            "deltas" => $this->computePartitionsDelta($records)]);
    }

    public function status(array $records) : int
    {
        $deltas = $this->computePartitionsDelta($records);
        return $this->computeStatusFromDeltas($deltas);
    }

    public function points(array $records) : array
    {
        // parse the first record to get the list of partitions
        $partitions = Disks::fromRecord($records[0]);
        $dataset = [];
        foreach ($partitions as $partition) {
            /** @var Partition $partition */
            $dataset[$partition->filesystem] = [
                "name" => $partition->filesystem,
                "points" => []
            ];
        }

        // parse all records to create the points
        foreach ($records as $record) {
            $partitions = Disks::fromRecord($record);
            foreach ($partitions as $partition) {
                $dataset[$partition->filesystem]["points"][] = new Point(
                    $partition->time * 1000,
                    $partition->usedPercent()
                );
            }
        }

        return array_values($dataset);
    }

    public function computeStatusFromDeltas(array $deltas) : int
    {
        if (count($deltas) == 0) {
            return \App\Status::UNKNOWN;
        }

        $all_status = [];
        foreach ($deltas as $delta) {
            $status = \App\Status::OK;

            if ($delta->timeUntillFull() > 0
                    && $delta->timeUntillFull() < 7 * 24 * 3600) {
                $status = \App\Status::WARNING;
            }

            $all_status[] = $status;
        }
        return max($all_status);
    }

    /**
     *
     * @param array $records
     * @return array
     */
    public function computePartitionsDelta(array $records) : array
    {
        if (count($records) < 2) {
            throw new \Exception("not enough records...");
        }

        $partitions_t0 = Disks::fromRecord($records[0]);
        $partitions_end = Disks::fromRecord($records[count($records) - 1]);

        $deltas = [];
        foreach ($partitions_t0 as $key => $partition_t0) {
            if (!isset($partitions_end[$key])) {
                continue;
            }

            $delta = new PartitionDelta($partition_t0, $partitions_end[$key]);
            $deltas[] = $delta;
        }
        return $deltas;
    }
}
