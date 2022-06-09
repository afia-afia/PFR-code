<?php
namespace App\Http\Controllers;

use App\Organization;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Nelisys\Snmp;

use App\Post;

// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();
class OrganizationController extends Controller
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


    public function index()
    {
        return view(
            "organization.index",
            array("organizations" => Auth::user()->organizations->sortBy("name"))
        );
    }

    /**
     * Show the form for creating a new resource.
     * We use the same view for create and update => provide an empty Organization.
     *
     */
    public function create()
    {
        return view("organization.edit", ["organization" => new Organization()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $organization = new Organization();
        $organization->name = $request->name;
        $organization->dashboard_token = \str_random(20);
        Auth::user()->organizations()->save($organization);

        return redirect(action('OrganizationController@index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Organization $organization
     */
    public function show(Organization $organization)
    {   
        

        
      if($organization->name=="network equipments" || $organization->name=="hosts") 
         return view("organization.showSnmp", ["organization" => $organization ]);   
      elseif($organization->name=="physical sensors")   
          return view("organization.showPhysicalSensors", ["organization" => $organization ]);
      else    
         return view("organization.show", array("organization" => $organization));
    }
   


    /**
     * Display the specified resource.
     *
     * 
     */
    public function discovery()

    {   $arp_scan_raw = shell_exec('echo nabil | sudo -S arp-scan -I enp1s0f1 --localnet');
        $arp_scan = explode("\n", $arp_scan_raw); 
        $arp_scan_raw = shell_exec('echo nabil | sudo -S arp-scan -I wlp2s0 --localnet');
        $arp_scan2 = explode("\n", $arp_scan_raw);
        $arp_scan = array_merge($arp_scan, $arp_scan2);
        // Will contain matching fields in the regexp
        $matches = [];
        // Will contain all found interfaces in a mac-indexed array
        $found_interfaces = [];
        // Scan results
        foreach($arp_scan as $scan) {
            $matches = []; // reset
            // Parse output lines
            if(preg_match('/^([0-9\.]+)[[:space:]]+([0-9a-f:]+)[[:space:]]+(.+)$/', $scan, $matches) !== 1) {
                // Ignore lines that don't contain results
                continue;
            }
        
            $ip = $matches[1];
            $mac = $matches[2];
            $desc = $matches[3];
        
            $found_interfaces[$mac] = [
                'ip' => $ip,
                'desc' => $desc,
                'known' => false, // Will be changed by the loop
            ];
        }
        
        return view("organization.discovery", ["found_interfaces" => $found_interfaces]);
    }







    public function dashboard(Organization $organization)
    {
        return view("organization.dashboard", ["organization" => $organization]);
    }

    public function resetToken(Organization $organization)
    {
        $organization->dashboard_token = \str_random(20);
        $organization->save();
        return redirect(action('OrganizationController@show', ["organization" => $organization]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Organization $organization
     */
    public function edit(Organization $organization)
    {
        return view("organization.edit", array("organization" => $organization));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Organization $organization
     */
    public function update(Request $request, Organization $organization)
    {
        $this->validator($request->all())->validate();

        $organization->name = $request->name;
        $organization->save();
        return redirect(action('OrganizationController@index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        Organization::find($id)->delete();
        return redirect(action("OrganizationController@index"));
    }
}
