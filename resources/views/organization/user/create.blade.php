@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Invite a user to your Groupe
                </div>

                <div class="card-body">

                    <form class="form" method="POST"
                          action="{{ action('OrganizationUserController@store', ["organization" => $organization]) }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name">Groupe</label>
                            <p class="form-control">{{ $organization->name }}</p>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">E-Mail Address</label>

                            <input id="email" type="email" class="form-control"
                                   name="email" value="{{ old('email') }}" autofocus required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                       
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Invite user
                            </button>
                        </div>
                  
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
