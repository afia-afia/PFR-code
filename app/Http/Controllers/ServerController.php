<?php
namespace App\Http\Controllers;

use App\Server;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Post;
use App\CorrelationResult;
// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();

class ServerController extends Controller
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
     */
    public function index()
    {
         return view("server.index", array("servers" => Server::all()->sortBy("name")));
    }

    /**
     * Show the form for creating a new resource.
     * We use the same view for create and update => provide an empty Server.
     *
     */
    public function create()
    {
        return view("server.edit", ["server" => new Server()]);
    }
    
      /**
     * create new server
     *
     * @param  string  $name
     */

    public function createDisc($name)
    {   

        $serve=new Server();
        $serve->name=$name;
      
        return view("server.edit", ["server" => $serve ]);//, "name"=>$name
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        return $this->saveAndRedirect($request, new Server());
    }

    /**
     * Display the specified resource.
     *
     * @param  Server $server
     */
    public function show(Server $server)
    {
        return view("server.show", ["server" => $server]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Server $server
     */
    public function edit(Server $server)
    {
        return view("server.edit", array("server" => $server));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Server $server
     */
    public function update(Request $request, Server $server)
    {
        return $this->saveAndRedirect($request, $server);
    }

    private function saveAndRedirect(Request $request, Server $server)
    {
        $this->validator($request->all())->validate();

        $server->name = $request->name;
        $server->organization_id = $request->organization_id;
        $server->save();

        return redirect(action("ServerController@show", ["server" => $server]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        Server::find($id)->delete();
        return back();
    }

    public function correlation(){
       
        $co=CorrelationResult::all();
      
        return view("server.correlation", ["co" => $co] );
    }
 


}
