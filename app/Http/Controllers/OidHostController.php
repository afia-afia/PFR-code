<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\User;
use App\MonitoringSnmp;
use App\OidHost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Nelisys\Snmp;
use App\Post;

// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();

class OidHostController extends Controller
{

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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $type='host';
        return view("oidSnmp.editH", ["newOid" =>new OidHost() , "type"=>$type]);
    }
     
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setting()
    {
        $oids=OidHost::all();
        $host='host';
        return view("oidSnmp.setting", ["oids" => $oids , "type"=>$host]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        return $this->saveAndRedirect($request, new OidHost());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $OidHost=OidHost::find(json_decode($request->oid, true)["id"]) ;
        $type="host";
        return view("oidSnmp.editH", ["newOid" =>$OidHost  , "type"=>$type]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

       
        $OidHost=OidHost::find(json_decode($request->newOid, true)["id"]) ;
        return $this->saveAndRedirect($request, $OidHost);
    }
     
    private function saveAndRedirect(Request $request, OidHost $OidHost)
    {
        $this->validator($request->all())->validate();

        $OidHost->name = $request->name;
        $OidHost->oid = $request->oid;
        $OidHost->save();
      
        return redirect("/app/organizations/settinghost")->with('success', 'Data saved successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        OidHost::find($request->id)->delete();
        return back();
    }
}
