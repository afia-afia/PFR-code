@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                @foreach (Auth::user()->organizations as $organization)
                     @if($organization->id == $id)
                     <h3> {{$organization->name}} </h3>
                      @endif
                @endforeach
                </div>

                <div class="card-body">
                    @if (!$MonitoringSnmp->exists)
                    <form method="POST" action="{{ action("MonitoringSnmpController@store") }}">
                    @else
                    <form method="post" action="./update">
                        
                        {{ method_field("PUT") }}
                    @endif
                        {{ csrf_field() }}
                           <input type="hidden" value="{{ $MonitoringSnmp }}" name="monitoringsnmp">
                        <div class="form-group row">
                            <label for="organization_id" class="col-md-4 col-form-label text-md-right">Groupe</label>

                            <div class="col-md-6">

                                <select id="organization_id"
                                       class="form-control{{ $errors->has('organization_id') ? ' is-invalid' : '' }}"
                                       name="organization_id"
                                       required autofocus>
                                    @foreach (Auth::user()->organizations as $organization)
                                     @if($organization->id == $id)
                                    <option value="{{ $organization->id }}" {{ old('organization_id', $MonitoringSnmp->organization_id) == $organization->id ? "selected" : "" }}">{{ $organization->name }}</option>
                                    @endif
                                    @endforeach

                                </select>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('organization_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name"
                                       value="{{ old('name', $MonitoringSnmp->name) }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Type (host ,router or swich)</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                       name="type"
                                       value="{{ old('type', $MonitoringSnmp->type) }}" required>

                                @if ($errors->has('type'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Ip Address</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('ip') ? ' is-invalid' : '' }}"
                                       name="ip"
                                       value="{{ old('ip', $MonitoringSnmp->ip) }}" required>

                                @if ($errors->has('ip'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('ip') }}</strong>
                                    </span>
                                @endif
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
@endsection