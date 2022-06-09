<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Sensor;

class Memory
{
    public $total;
    public $free;
    public $cached;

    public function __construct(int $total, int $free, int $cached)
    {
        $this->total = $total;
        $this->free = $free;
        $this->cached = $cached;
    }

    public function used() : int
    {
        return $this->total - $this->free - $this->cached;
    }


    public function usedRatio() : float
    {
        return $this->used() / $this->total;
    }
}
