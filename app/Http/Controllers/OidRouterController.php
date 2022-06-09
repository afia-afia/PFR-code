<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\User;
use App\MonitoringSnmp;
use App\OidRouter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Post;

// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();

class OidRouterController extends Controller
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
        $type='router';
        return view("oidSnmp.editR", ["newOid" =>new OidRouter() , "type"=>$type]);
    }
       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setting()
    {
        $oids=OidRouter::all();
        $router='router';
        return view("oidSnmp.setting", ["oids" => $oids , "type"=>$router]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        return $this->saveAndRedirect($request, new OidRouter());
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
        $OidRouter=OidRouter::find(json_decode($request->oid, true)["id"]) ;
        return view("oidSnmp.editR", ["newOid" =>$OidRouter  , "type"=>$type]);
        
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

       
        $OidRouter=OidRouter::find(json_decode($request->newOid, true)["id"]) ;
        return $this->saveAndRedirect($request, $OidRouter);
    }
    
    private function saveAndRedirect(Request $request, OidRouter $OidRouter)
    {
        $this->validator($request->all())->validate();

        $OidRouter->name = $request->name;
        $OidRouter->oid = $request->oid;
        $OidRouter->save();
      
        return redirect("/app/organizations/settingrouter")->with('success', 'Data saved successfully!');
    }
/**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       
        OidRouter::find($request->id)->delete();
        return back();
    }
}
