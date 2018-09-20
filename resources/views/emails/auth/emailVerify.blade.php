@component('mail::message')
# Thank you for registering

Click to confirm the email address.

@component('mail::button', ['url' => $emailVerifyUrl])
    Verify
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
