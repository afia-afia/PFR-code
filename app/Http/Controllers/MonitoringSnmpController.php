<?php

namespace App\Http\Controllers;
use App\Organization;
use App\User;
use App\MonitoringSnmp;
use App\OidHost;
use App\OidRouter;
use App\OidSwich;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Nelisys\Snmp;
use FreeDSx\Snmp\SnmpClient;
use Illuminate\Support\Facades\Redirect;

use App\Post;

// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();
class MonitoringSnmpController extends Controller
{
    //
 
    public function __construct()
    {
        // Uncomment to require authentication
        $this->middleware('auth');
    }

      /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|regex:/^[a-zA-Z0-9\s\-\.]+$/|max:255'
        ]);
    }

    public function index()
    {
    }

     
    /**
     * Display the specified resource.
     *
     * @param  MonitoringSnmp $MonitoringSnmp
     */
    public function show(monitoringSnmp $monitoringSnmp)
    { 

        return view("monitoringSnmp.show", ["monitoringSnmp" => $monitoringSnmp]);
    }


    
    public function showSnmp(Request $request)
    {    
       // $snmp = new Snmp('127.0.0.1', 'public');
        //dd($snmp->get('1.3.6.1.2.1.1.1.0'));

      //  $snmp = new SnmpClient([
        //    'host' => '127.0.0.1',
          //  'version' => 2,
            //'community' => 'public',
        //]);
        
        # Get a specific OID value as a string...
      //  dd($snmp->getValue('1.3.6.1.2.1.1.1.0').PHP_EOL) ;
      //$walk = $snmp->walk();
      //while($walk->hasOids()) {
        //try {
            # Get the next OID in the walk
          // / $oid = $walk->next();
           // echo $oid->getOid().PHP_EOL;
            //echo $oid->getValue().PHP_EOL;
        //} catch (\Exception $e) {
            # If we had an issue, display it here (network timeout, etc)
          //  echo "Unable to retrieve OID. ".$e->getMessage().PHP_EOL;
        //}
       // echo $walk->count().PHP_EOL;
    //}
         $monitoringSnmp=MonitoringSnmp::find(json_decode($request->monitoringsnmp, true)["id"]) ;
         //$oids=OidHost::all();
         //$newArr=$monitoringSnmp->getArryInfo("127.0.0.1",$oids );
        // dd($newArr);
       
        //$oids = array();
        //$oid=new OidHost();
        //$oid->name="test";       
        //$oid->oid="1.3.6.1.2.1.1.4.0";
        //$oids[0]=$oid;
        //dd($monitoringSnmp->getArryInfo("127.0.0.1",$oids ));

         switch ($monitoringSnmp->type) {
            case "host":
                $oids=OidHost::all();
                $infoArrs=$monitoringSnmp->getArryInfo($monitoringSnmp->ip,$oids );
              break;
            case "router":
                $oids=OidRouter::all();
                $infoArrs=$monitoringSnmp->getArryInfo($monitoringSnmp->ip,$oids );
              break;
            case "swich":
                $oids=OidSwich::all();
                $infoArrs=$monitoringSnmp->getArryInfo($monitoringSnmp->ip,$oids );
              break;
          }


        return view("monitoringSnmp.show", ["monitoringSnmp" => $monitoringSnmp , "infoArrs" => $infoArrs]);
    }
      
      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function setSnmp(Request $request)
    {   
       $monitoring=MonitoringSnmp::find($request->id);
       $monitoring->setInfo($monitoring->ip,$request->info,$request->name); 
      //dd($monitoring);

        return back();
    }

    public function alert(Request $request)
    {   
      
        return Redirect::back()->withErrors(['alert :device is down', 'The Message']);
    }

     /**
     * Show the form for creating a new resource.
     * We use the same view for create and update => provide an empty MonitoringSnmp.
     *
     */
    public function create( $id)
    { 
        return view("monitoringSnmp.edit", ["MonitoringSnmp" =>new MonitoringSnmp() , "id" =>$id ]);
    }
    
    public function createDisc( $id ,$ip ,$name)
    {   
    
        $MonitoringSnmp=new MonitoringSnmp();
        $MonitoringSnmp->name = $name;
        $MonitoringSnmp->type = '';
        $MonitoringSnmp->ip = $ip;
        $MonitoringSnmp->organization_id = $id;
        


       

        return view("monitoringSnmp.edit", ["MonitoringSnmp" =>$MonitoringSnmp , "id" =>$id ]);
    }




     /**
     * Show the form for editing the specified resource.
     *
     * @param  MonitoringSnmp $MonitoringSnmp
     */
    public function edit(Request $request)
    {
        $monitoringSnmp=MonitoringSnmp::find(json_decode($request->monitoringsnmp, true)["id"]) ;
        $id=json_decode($request->monitoringsnmp, true)["organization_id"];
        return view("monitoringSnmp.edit",  ["MonitoringSnmp" => $monitoringSnmp , "id" =>$id ]);
    }
  


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {   
        return $this->saveAndRedirect($request, new MonitoringSnmp());
    }
     
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  MonitoringSnmp $MonitoringSnmp
     */
    public function update(Request $request)//, MonitoringSnmp $MonitoringSnmp)
    {   
        $MonitoringSnmp=MonitoringSnmp::find(json_decode($request->monitoringsnmp, true)["id"]) ;
        return $this->saveAndRedirect($request, $MonitoringSnmp);
    }



    private function saveAndRedirect(Request $request, MonitoringSnmp $MonitoringSnmp)
    {
        $this->validator($request->all())->validate();

        $MonitoringSnmp->name = $request->name;
        $MonitoringSnmp->type = $request->type;
        $MonitoringSnmp->ip = $request->ip;
        $MonitoringSnmp->organization_id = $request->organization_id;
        $MonitoringSnmp->save();
      
        return redirect('/app/organizations/'.$request->organization_id);//redirect(action("MonitoringSnmpController@show", ["MonitoringSnmp" => $MonitoringSnmp]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * 
     */
    public function destroy(Request $request)
    {
       
       MonitoringSnmp::find($request->id)->delete();
        return back();
    }


}
