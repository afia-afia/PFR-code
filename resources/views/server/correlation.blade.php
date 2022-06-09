@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
       <div class="col-md-3">
        </div>

        <div class="col-md-9">
          <h3>Correlation module </h3>
        <table class="table table-striped">
                <tr>
                    <th>Date</th>
                    <th>Probleme</th>
                    <th></th>
                
                </tr>
        @foreach($co as $c)
          <tr>
                    <td>{{ $c->created_at }}</td>
                    <td>{{ $c->probleme }}</td>
                    <td></td>  
         </tr>
       @endforeach
    </div>
 </div>
@endsection