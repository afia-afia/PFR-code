<?php

namespace App\Sensor;


class Partition
{
    public $filesystem = "";
    public $blocks = 0;
    public $used = 0;
    public $mounted = "";

    /**
     *
     * @var int time reference, in unix timestamp
     */
    public $time = 0;

    public function usedPercent()
    {
        return round(100.0 * $this->used / $this->blocks);
    }
}
