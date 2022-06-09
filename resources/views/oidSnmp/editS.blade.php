@extends('layouts.app')

@section('content')



<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                     <h3> {{$type}} </h3>
                </div>

                <div class="card-body">
                    @if (!$newOid->exists)
                    <form method="POST" action="./storeS">
                    @else
                    <form method="POST" action="./updateS">
                        
                        {{ method_field("PUT") }}
                    @endif
                        {{ csrf_field() }}
                           <input type="hidden" value="{{ $newOid }}" name="newOid">
                     

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Description</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name"
                                       value="{{ old('name', $newOid->name) }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                       

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">OID</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('oid') ? ' is-invalid' : '' }}"
                                       name="oid"
                                       value="{{ old('oid', $newOid->oid) }}" required>

                                @if ($errors->has('oid'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('oid') }}</strong>
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