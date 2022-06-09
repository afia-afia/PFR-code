<?php

namespace App\Sensor;

/**
 * Description of Update
 *
 * @author tibo
 */
class Disks extends \App\AbstractSensor
{

    const REGEXP = "/\\n([A-z\/0-9:\\-\\.]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)\s*([0-9]+)%\s*([A-z\/0-9]+)/";

    public function report(array $records) : string
    {
        $record = end($records);
        if (! isset($record['disks'])) {
            return "<p>No data available...</p>";
        }

        $partitions = self::parse($record->disks);
        $return = "<table class='table table-sm'>";
        $return .= "<tr><th></th><th></th><th>Usage</th></tr>";
        foreach ($partitions as $partition) {
            $return .= "<tr><td>" . $partition->filesystem . "</td><td>"
                    . $partition->mounted . "</td><td>" . $partition->usedPercent()
                    . "%</td></tr>";
        }
        $return .= "</table>";
        return $return;
    }

    public function status(array $records) : int
    {
        $record = end($records);
        if (! isset($record['disks'])) {
            return \App\Status::UNKNOWN;
        }

        $all_status = [];
        foreach (self::parse($record->disks) as $partition) {
            /* @var $partition Partition */
            $status = \App\Status::OK;
            if ($partition->usedPercent() > 80) {
                $status = \App\Status::WARNING;
            } elseif ($partition->usedPercent() > 95) {
                $status = \App\Status::ERROR;
            }
            $all_status[] = $status;
        }

        return max($all_status);
    }

    public static $skip_fs = ["none", "tmpfs", "shm", "udev", "overlay", '/dev/loop'];

    public static function parse(string $string) : array
    {
        $values = array();
        preg_match_all(self::REGEXP, $string, $values);
        $partitions = array();
        $count = count($values[1]);
        for ($i = 0; $i < $count; $i++) {
            $fs = $values[1][$i];
            if (self::shouldSkip($fs)) {
                continue;
            }

            $partition = new Partition();
            $partition->filesystem = $fs;
            $partition->blocks = $values[2][$i];
            $partition->used = $values[3][$i];
            $partition->mounted = $values[6][$i];
            $partitions[] = $partition;
        }
        return $partitions;
    }

    public static function fromRecord($record) : array
    {
        $partitions = self::parse($record->disks);
        $time = $record->time;
        foreach ($partitions as $partition) {
            $partition->time = $time;
        }

        return $partitions;
    }

    public static function shouldSkip(string $fs) : bool
    {
        foreach (self::$skip_fs as $should_skip) {
            if (self::startsWith($should_skip, $fs)) {
                return true;
            }
        }

        return false;
    }

    public static function startsWith(string $needle, string $haystack) : bool
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
