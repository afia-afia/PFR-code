<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use App\Mail\OrganizationUserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Post;

// Using it directly in the code without the use operator won't.
$posts = \App\Post::all();
class OrganizationUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    }

    /** Show form **/
    public function create(Organization $organization)
    {  
       
        return view("organization.user.create", ["organization" => $organization]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
        ]);
    }

    /** add user to organization **/
    public function store(Organization $organization, Request $request)
    {
        $current_user = Auth::user();
        if (! $current_user->ownsOrganization($organization)) {
            return redirect(route("dashboard"));
        }

        $user = User::findByEmail($request->input("email"));

        if ($user == null) {
            // create user
            $this->validator($request->all())->validate();
            $user = User::create([
                'name' => $request->input("email"),
                'email' => $request->input("email"),
                'password' => bcrypt(str_random()),
            ]);
        }

        if ($user->ownsOrganization($organization)) {
            // user is already part of organization...
            return redirect(route("dashboard"));
        }

        Mail::to($user->email)->send(new OrganizationUserInvitation($organization, $user));
        $organization->users()->attach($user->id);
        return redirect(route("dashboard"));
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
    }
}
