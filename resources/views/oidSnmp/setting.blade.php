@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
     <div class="col-md-3">
     </div>

    <div class="col-md-9">
          <h1>{{$type}}</h1>
          @switch($type)
        @case('host')
          <p>
                <a class="btn btn-primary" 
                href="./createOidH">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> New 
                </a>
            </p>
            @break
            @case('router')
            <p>
                <a class="btn btn-primary" 
                href="./createOidR">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> New 
                </a>
            </p>
            @break
            @case('swich') 
            <p>
                <a class="btn btn-primary" 
                href="./createOidS">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> New 
                </a>
            </p>
            @break  
            @endswitch 
            <table class="table table-striped">
                <tr>
                    <th>Description</th>
                    <th>OID</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($oids as $oid)
                <tr>
                    <td>{{ $oid->name }}</td>
                    <td>{{ $oid->oid }}</td>
                    <td></td>
                    <td></td>
                    
                    <td> 
          <div class="btn-group" role="group">

                   <div class="col-md-7" style="padding-right: 2px;padding-left: 2px;">
                   @switch($type)
                       @case('router')
                      <form method="post" action="./editOidR">
                        {{csrf_field()}}
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i>Edit</button>
                         <input type="hidden" value="{{ $oid }}" name="oid">
                       </form>
                      @break
                       @case('host')
                    <form method="post" action="./editOidH">
                        {{csrf_field()}}
                       
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i>Edit</button>
                         <input type="hidden" value="{{ $oid }}" name="oid">
                       </form>
                       @break
                      
                      @case('swich')
                      <form method="post" action="./editOidS">
                        {{csrf_field()}}
                        
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i>Edit</button>
                         <input type="hidden" value="{{ $oid }}" name="oid">
                       </form> 
                      @break  
                   @endswitch
                      </div>
                       <div class="col-md-7" style="padding-right: 2px;padding-left: 2px;">
                 @switch($type)
                     @case('host')
                    <form method="post" action="./deleteOidH">
                        {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        <button class="btn btn-danger btn-sm">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> Delete </button>
                        <input type="hidden" value="{{ $oid->id }}" name="id">
                    </form>
                    @break
                      @case('router')
                      <form method="post" action="./deleteOidR">
                        {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        <button class="btn btn-danger btn-sm">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> Delete </button>
                        <input type="hidden" value="{{ $oid->id }}" name="id">
                      @break
                      @case('swich')
                      <form method="post" action="./deleteOidS">
                        {{ csrf_field() }}
                        {{ method_field("DELETE") }}
                        <button class="btn btn-danger btn-sm">
                        <i class="fa fa-times-circle" aria-hidden="true"></i> Delete </button>
                        <input type="hidden" value="{{ $oid->id }}" name="id">
                      @break  
                   @endswitch
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