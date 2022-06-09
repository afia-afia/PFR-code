@component('mail::message')
Dear,

You were invited to join the groupe **{{ $organization->name  }}**.

@component('mail::button', ['url' => route("dashboard")])
Login
@endcomponent

Best regards,

@endcomponent