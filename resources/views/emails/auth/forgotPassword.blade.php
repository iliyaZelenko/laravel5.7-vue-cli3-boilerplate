@component('mail::message')
# Last step

Click the button to continue.

@component('mail::button', ['url' => $resetUrlWithToken])
    Continue
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
