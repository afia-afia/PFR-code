<?php

namespace App\Sensor;
use Illuminate\Support\Facades\Storage;

class Logs extends \App\AbstractSensor
{

    public function report(array $records) : string
    {
        $record = end($records);
        if (! isset($record['perccli'])) {
            return "<p>No data available...</p>";
        }
      //Storage::disk('public')->delete('auth.txt');  
      Storage::disk('public')->put('auth.txt', $record['perccli']);
        

       $return =$record['perccli'];          
  

          

        return $return;
    }

    public function status(array $records) : int
    {
        $record = end($records);
        if (! isset($record['perccli'])) {
            return \App\Status::UNKNOWN;
        }
        return \App\Status::OK;
    }


}
