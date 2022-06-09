<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrelationResult extends Model
{
    //





    public function codebook(array $symptom,$p){

    
        $codeBook1 = array(
          "the server seemes over loaded" => array("l"=>1 ,"r"=>1 ,"rt"=>0,"ping"=>0,"port"=>0 ),
          "congestion in network" => array("l"=>0 ,"r"=>0 ,"rt"=>1,"ping"=>0,"port"=>0 ),
          "the server is offline" => array("l"=>0,"r"=>0 ,"rt"=>0,"ping"=>1,"port"=>0 ),
          "service is stopped" => array("l"=>0 ,"r"=>0 ,"rt"=>0,"ping"=>0,"port"=>1 )
      );
     
   
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
          dd($k.". server name: ".$server->name);
         $u=new CorrelationResult();
         $u->probleme=$k.$server->name;
         $u->save();
          }
        }
  
      }
}
