<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Sensor;

/**
 * Description of InodesDisk
 *
 * @author tibo
 */
class InodesDisk
{
    public $filesystem = "";
    public $inodes = 0;
    public $used = 0;
    public $mounted = "";

    public function usedPercent()
    {
        return round(100.0 * $this->used / $this->inodes);
    }
}
