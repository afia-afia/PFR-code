<?php

namespace App\Sensor;


class Temper
{
    public $part1= "0a"; //eg : 0a
    public $part2= "6c"; //eg : 6c
    public $temp=array();//eg : [26,68]
    
    public function conversion()
    {
        $hexatemp=$this->part1.$this->part2; //eg : 0a6c
        $decitemp=hexdec($hexatemp); //eg : 2668
        $this->temp[1]=substr($decitemp, 0, -2); //eg : 26
        $this->temp[2]=substr($decitemp, -2); //eg : 68
        return $this->temp;
    }
}
