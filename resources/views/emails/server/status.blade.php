@component('mail::message')
# {{ $change->server()->organization->name }} / {{ $change->server()->name }} : status change

Your server **{{ $change->server()->organization->name }} / {{ $change->server()->name }}**
went **{{ $change->status()->name() }}**

{{ action("ServerController@show", ["server" => $change->server()]) }}


{{ config('app.name') }}
@endcomponent
