@component('mail::message')
# Последний шаг

Нажмите кнопку чтобы продолжить.

@component('mail::button', ['url' => $resetUrlWithToken])
    Продолжить
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
