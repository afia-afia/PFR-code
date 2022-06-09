@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
        <div class="card">
                <div class="card-header">
                   Information
                </div>

                <div class="card-body">
                    <ul class="list-unstyled">
                      
                        <li>Type : {{$monitoringSnmp->type}}</li>
                    
                    </ul>

                    <ul class="list-unstyled">
                    <li>Host Name : {{$monitoringSnmp->name}}</li>
                    
                    </ul>
                    <ul class="list-unstyled">
                      
                        <li>Host IP : {{$monitoringSnmp->ip}}</li>
                    
                    </ul>
                </div>
            </div>
       </div>

<div class="col-md-9">
  
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">SNMP SET</div>                   
                   <div class="card-body">   
                    <form method="POST" action="./setSnmp">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $monitoringSnmp->id }}" name="id">
                        <div class="form-group row">
                            <label for="organization_id" class="col-md-4 col-form-label text-md-right">Change</label>
                            <div class="col-md-6">
                                <select id="organization_id"
                                       class="form-control"
                                       name="name"
                                       required autofocus>  
                                    @foreach($monitoringSnmp->array as $key=>$name)       
                                    <option name="name"> {{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Information</label>
                            <div class="col-md-6">
                                <input id="info" type="text" class="form-control" name="info"  value="set information " required>  
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-check" aria-hidden="true"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
             </div>
        </div>
    </div>
</div>

        
<table class="table table-striped">
                <tr>
                    <th>Information</th>
                    <th>Value</th>
                    
                </tr>
                @foreach($infoArrs as $key => $monitoringsnmp)
                <tr>
                    <td>{{$key}}</td>
                    <td>{{$monitoringsnmp}}</td>   
                </tr>
                @endforeach
            </table>
    </div>
    </div>
</div>


@endsection