<?php

namespace App\Sensor;

class NetworkInterface
{
    public $name;
    public $address;
    public $rx;
    public $tx;
    public $time;

    public function humanReadableSize($bytes, $decimals = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public function humanReadableRx()
    {
        return $this->humanReadableSize($this->rx);
    }

    public function humanReadableTx()
    {
        return $this->humanReadableSize($this->tx);
    }
}
