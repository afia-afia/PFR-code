<?php

namespace App\Http\Controllers;
use App\PhysicalSensor;
use Illuminate\Http\Request;
use App\Organization;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PhysicalSensorController extends Controller
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
    public function create( $id)
    {
       
        return view("physicalSensor.edit", ["PhysicalSensor" =>new PhysicalSensor() , "id" =>$id]);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
       
        return $this->saveAndRedirect($request, new PhysicalSensor());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {   
        
        $PhysicalSensor=PhysicalSensor::find($request->id);
        //dd($PhysicalSensor);

        $url='http://';
       // return redirect()->away('http://www.pakainfo.com');//redirect::away('http://192.168.1.21/login.html');
        return  "<script>window.open('".$url."".$PhysicalSensor->ip."?a=".$PhysicalSensor->username."&b=".$PhysicalSensor->password."', '_blank')</script>";
        return back();
    }
      /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   

        $PhysicalSensor=PhysicalSensor::find($request->id);
        $id=$PhysicalSensor->organization_id;
        return view("physicalSensor.edit", ["PhysicalSensor" =>$PhysicalSensor , "id" =>$id]);
        
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
        $PhysicalSensor=PhysicalSensor::find(json_decode($request->PhysicalSensor, true)["id"]) ;
        return $this->saveAndRedirect($request, $PhysicalSensor);
    }
    
    private function saveAndRedirect(Request $request, PhysicalSensor $PhysicalSensor)
    {
        $this->validator($request->all())->validate();

        $PhysicalSensor->username = $request->name;
        $PhysicalSensor->password = $request->password;
        $PhysicalSensor->rac = $request->rac;
        $PhysicalSensor->ip = $request->ip;
        $PhysicalSensor->organization_id = $request->organization_id;
        $PhysicalSensor->save();
        return redirect("/app/organizations/7")->with('success', 'Data saved successfully!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        PhysicalSensor::find($request->id)->delete();
        return back();
    }
}
