@component('mail::message')
# {{ $change->server()->organization->name }} / {{ $change->server()->name }} : status bouncing

Your server **{{ $change->server()->organization->name }} / {{ $change->server()->name }}**
seems to be bouncing between different states.

This is our last email for today...

{{ action("ServerController@show", ["server" => $change->server()]) }}


{{ config('app.name') }}
@endcomponent
