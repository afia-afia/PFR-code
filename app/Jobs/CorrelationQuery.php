<?php

namespace App\Jobs;

use App\Organization;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\CorrelationResult;
use App\Sensor\ListeningPorts;
use App\Sensor\LoadAvg;
use App\Sensor\MemInfo;
use App\Sensor\Netstat;
use App\Sensor\Ifconfig;

class CorrelationQuery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public $symtom;
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


      foreach (Organization::all() as $organization) {
           $p=1433;
        foreach ($organization->servers as $server) {
          $this->codebook($server , $p);
            
        }
    }
    }


    public function searchPort(Server $server, $po) : int
    {
      $p=1;
      $record = $server->lastRecord();
     $lesting=new ListeningPorts();
     $ports= $lesting->parse($record["netstat-listen-tcp"]);
    
     $porton = [];
     $ip=[];
     foreach ($ports as $port) {
      $porton[] =$port->port;
      $ip[] =$port->bind;
      if($port->port==$po)
      {
       $p=0;
       break;
      }

     }
   
    return  $p;
    }

    public function laod(Server $server) :int
    {
      $record = $server->lastRecord();
       $laod=new LoadAvg();
       if($laod->parse($record->loadavg) > $server->info()->cpuinfo()["threads"])
       return 1;
       else
       return 0;
    }
   
    public function ram(Server $server)  :int
    {
      $record = $server->lastRecord();
      $ram=new MemInfo();
      if($ram->parseMeminfo($record->memory)->used() / 1000000 > 8*0.8)
       return 1;
       else
       return 0;

    }
    public function retransmission(Server $server)  :int
    {
      $record = $server->lastRecords1Day();
      $tcp=new Netstat();
      $aaray=$tcp->points($record);
   
      
      if($aaray[0]['points'][count($aaray[0]['points'])-1]->y > 12)
       return 1;
       else
       return 0;
    }
    
    public function ipad(Server $server)  :int{
      $record = $server->lastRecord();
      $ip=new Ifconfig();
      $interfaces=$ip->parseIfconfigRecord($record);
      foreach($interfaces as $int)
      {
        $status;
        if($int->address != null){
          
          exec("ping -c 1 ".$int->address, $output, $status);
         
           return $status;
        }
      }
      return 1;
    
    }


    public function codebook(Server $server ,$port){

      $l=$this->laod($server);
      $m=$this->ram($server);
      $t=$this->retransmission($server);
      $ip=$this->ipad($server);
      $p=$this->searchPort($server ,$port);

      $codeBook1 = array(
        "the server seemes over loaded" => array("l"=>1 ,"r"=>1 ,"rt"=>0,"ping"=>0,"port"=>0 ),
        "congestion in network" => array("l"=>0 ,"r"=>0 ,"rt"=>1,"ping"=>0,"port"=>0 ),
        "the server is offline" => array("l"=>0,"r"=>0 ,"rt"=>0,"ping"=>1,"port"=>0 ),
        "service is stopped port : ".$port." closed" => array("l"=>0 ,"r"=>0 ,"rt"=>0,"ping"=>0,"port"=>1 )
    );
     $symptom=array("l"=>$l ,"r"=>$m ,"rt"=>$t,"ping"=>$ip,"port"=>$p);
      foreach($codeBook1 as $k=> $line){
        $x=0;
        foreach($line as $key=>$i)
        {
           if($line[$key]==1 && $symptom[$key]!=1 ){
             $x=1;
            break;
           }
        }
       
       if($x==0){
       $u=new CorrelationResult();
       $u->probleme="The server ".$server->name." ==> ".$k;
       $u->save();
        }
      }

    }
}
