<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use FreeDSx\Snmp\SnmpClient;
use FreeDSx\Snmp\Exception\SnmpRequestException;
use FreeDSx\Snmp\Exception;
use Illuminate\Support\Facades\Log;
use FreeDSx\Snmp\Oid;

class MonitoringSnmp extends Model
{
    //
   public $array = array(
    "contact" => "1.3.6.1.2.1.1.4.0",
    //forwarding(1), -- acting as a router
   //notForwarding(2) -- NOT acting as a router
    "ipForwarding(1,2)" => "1.3.6.1.2.1.4.1",
);
    public function __construct()
    {
      
        parent::__construct();
    }

    public function organization()
    {
        return $this->belongsTo("App\Organization");
    }

    public function getArryInfo($ip , $oids) : array
    {
        $snmp = new SnmpClient([
            'host' => $ip,
            'version' => 2,
            'community' => 'public',
        ]);
        $newArr = array();
      foreach($oids as $oid){
        try {
            $newArr[$oid->name] = $snmp->getValue($oid->oid).PHP_EOL;
         } catch (SnmpRequestException $e) { 
           $newArr[$oid->name] = $e->getMessage().PHP_EOL;
         }
      }
        return $newArr;
    }
    public function setInfo($ip , $info ,$name)
    {
        $snmp = new SnmpClient([
            'host' => $ip,
            'version' => 2,
            'community' => 'public',
        ]);

        try {
            # Set the contact OID string...
            $snmp->set(Oid::fromString($this->array[$name], $info));
        } catch (SnmpRequestException $e) {
            echo $e->getMessage();
            exit;
        }


    }

   
   

}
