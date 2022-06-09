@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1>List of discovred devices</h1>
            <table class="table table-striped">
                <tr>
                    <th>Name</th>
                    <th>MAC</th>
                    <th>IP</th>
                    <th>Add</th>
                    
                </tr>
                @foreach( $found_interfaces as $mac => $interface )
                <tr>
                    <td>{{ $interface['desc'] }}</td>
                    <td>{{ $mac }}</td>
                    <td>{{ $interface['ip'] }}</td>
                    <td>  
                    <div class="dropdown show"> 
                        <a href="#" class="btn btn-primary dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <i class="fa fa-plus-circle" aria-hidden="true"></i> Add </a>
                             
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @foreach (Auth::user()->organizations as $organization)
                            
                            @switch($organization->name)
                               @case('hosts')
                               <a class="dropdown-item"
                               href="./dis/add/{{$organization->id}}/{{$interface['ip']}}/{{$interface['desc'] }}">
                                {{ $organization->name }}
                               </a>
                               @break

                               @case('network equipments')
                               <a class="dropdown-item"
                               href="./dis/add/{{$organization->id}}/{{$interface['ip']}}/{{$interface['desc'] }}">
                                {{ $organization->name }}
                               </a>
                               @break

                               @default
                               <a class="dropdown-item"
                               href="./dis/add/{{$interface['desc'] }}">
                                {{ $organization->name }}
                               </a>
                               @endswitch


                           
                            @endforeach
                        </div>
                    </div>
                    
                             

                    </td>
                              
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
