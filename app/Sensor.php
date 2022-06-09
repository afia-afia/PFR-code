<?php

namespace App;

interface Sensor
{
    public function status(array $records) : int;
    public function report(array $records) : string;

  
    public function name() : string;
}
