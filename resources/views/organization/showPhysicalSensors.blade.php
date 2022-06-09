
<!--<script language="javascript" type="text/javascript" > 

</script>
<script language="javascript" type="text/javascript" > 
</script>-->

@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <p>
                        <a class="btn btn-primary btn-sm"
                           href="{{ action("OrganizationController@dashboard",
                                       ["organization" => $organization]) }}">
                            Dashboard <i class="fas fa-lock ml-2"></i>
                        </a>
                    </p>

                    <p>
                        <a class="btn btn-primary btn-sm"
                           href="{{ route("organization.public.dashboard", [
                               "organization" => $organization,
                                "token" => $organization->dashboard_token]) }}">
                            Public dashboard <i class="fas fa-globe ml-2"></i>
                        </a>
                    </p>

                </div>
        </div>


            <div class="card">
                <div class="card-header">
                    Users
                </div>

                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($organization->users as $user)
                        <li>{{ $user->name }}</li>
                        @endforeach
                    </ul>
                    @if(Auth::user()->email =="ainarko123@gmail.com")
                    <p>
                        <a class="btn btn-primary btn-sm"
                           href="{{ action("OrganizationUserController@create", ["organization" => $organization]) }}">
                            Invite user to Groupe
                        </a>
                    </p>
                    @endif
                </div>
            </div>


        </div>

        <div class="col-md-9">
            <h1>{{ $organization->name }}</h1>  
            <p>
                <a href="./createPh/{{ $organization->id }}" class="btn btn-primary">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> New sensor
                </a>
            </p>
                    @if($errors->any())
                      
                      <h4 class="text-danger bg-dark">{{$errors->first()}}</h4>
                       @endif
            <table class="table table-striped">
                <tr>
                    <th>Rac</th>
                    <th>Ip Address</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($organization->physicalsensor()->orderBy("id")->get() as $physicalsensor)
                <tr>
                    <td>{{ $physicalsensor->rac }}</td>
                    <td>{{ $physicalsensor->ip }}</td>
                    <td>   <?php  
                               $status;
                              exec("ping -c 1 ".$physicalsensor->ip , $output, $status);
                               if($status==0)
                               echo '<span class="badge badge-success">UP</span>' ;
                               else 
                                echo '<span class="badge badge-danger">DOWN</span>';   ?> </td>
                  
                  <td class="text-right">
                    <div class="btn-group" role="group">

                    <div class="col-md-5" style="padding-right: 1px;padding-left: 1px;">
                        @if($status==0)
                        <form method="post" action="./showPh">
                        @else
                        <form method="post" action="alert">    
                        @endif    
                        {{csrf_field()}}
                        <input type="hidden" value="{{ $physicalsensor->id }}" name="id">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search" aria-hidden="true"></i>Show</button>
                        

                       </form> 
                       </div>

                       <div class="col-md-4" style="padding-right: 1px;padding-left: 1px;">
                        <form method="post" action="./editPh">
                        {{csrf_field()}}
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i>Edit</button>
                        <input type="hidden" value="{{ $physicalsensor->id }}" name="id">
                       
                       </form>
                       </div>
                    
                    <div class="col-md-5" style="padding-right: 1px;padding-left: 1px;">
                    <form method="post" action="./deletePh">
                        {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        <button class="btn btn-danger btn-sm">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> Delete </button>
                        <input type="hidden" value="{{ $physicalsensor->id }}" name="id">
                    </form>
                    </div>
                    </div>
                    </td>
                    <td>
                    </td>
                </tr>
                @endforeach
            </table>
              
           
        </div>
    </div>

</div>

@endsection