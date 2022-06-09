@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Server</div>

                <div class="card-body">
                    @if (!$server->exists)
                    <form method="POST" action="{{ action("ServerController@store") }}">
                        @else
                    <form method="POST"
                          action="{{ action("ServerController@update", ["server" => $server]) }}">
                        {{ method_field("PUT") }}
                        @endif
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="organization_id" class="col-md-4 col-form-label text-md-right">Organization</label>

                            <div class="col-md-6">

                                <select id="organization_id"
                                       class="form-control{{ $errors->has('organization_id') ? ' is-invalid' : '' }}"
                                       name="organization_id"
                                       required autofocus>
                                    @foreach (Auth::user()->organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id', $server->organization_id) == $organization->id ? "selected" : "" }}">{{ $organization->name }}</option>
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
                                       name="name"  value="{{ $name }}" 
                                       required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
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