<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhysicalSensor extends Model
{
    //


    public function __construct()
    {
      
        parent::__construct();
    }

    public function organization()
    {
        return $this->belongsTo("App\Organization");
    }

    
}
