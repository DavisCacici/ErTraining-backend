@component('mail::message')
# Salve grazie per esserti registrato

Queste sono le sue credenziali per collegarsi
email : {{ $email }}
password: {{ $password }}
{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
