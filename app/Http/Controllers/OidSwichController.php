<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\User;
use App\MonitoringSnmp;
use App\OidSwich;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Post;

// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();

class OidSwichController extends Controller
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
        $type='swich';
        return view("oidSnmp.editS", ["newOid" =>new OidSwich() , "type"=>$type]);
    }
   
      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setting()
    {
        $oids=OidSwich::all();
        $swich='swich';
        return view("oidSnmp.setting", ["oids" => $oids , "type"=>$swich]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return $this->saveAndRedirect($request, new OidSwich());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $OidSwich=OidSwich::find(json_decode($request->oid, true)["id"]) ;
        $type="swich";
        return view("oidSnmp.editS", ["newOid" =>$OidSwich  , "type"=>$type]);
        
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
     
        $OidSwich=OidSwich::find(json_decode($request->newOid, true)["id"]) ;
        return $this->saveAndRedirect($request, $OidSwich);
    }
    
    private function saveAndRedirect(Request $request, OidSwich $OidSwich)
    {
        $this->validator($request->all())->validate();

        $OidSwich->name = $request->name;
        $OidSwich->oid = $request->oid;
        $OidSwich->save();
      
        return redirect("/app/organizations/settingswich")->with('success', 'Data saved successfully!');
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        OidSwich::find($request->id)->delete();
        return back();
    }
}
