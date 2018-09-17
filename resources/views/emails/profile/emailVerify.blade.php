@component('mail::message')
# Нажмите чтобы подтвердить электронный адрес.

@component('mail::button', ['url' => $emailVerifyUrl])
    Подтвердить
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
