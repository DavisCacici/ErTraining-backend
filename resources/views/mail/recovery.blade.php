@component('mail::message')
# Salve grazie per aver fatto il recupero password

Questa è la sua nuova password {{ $password }}
ti consigliamo di cambiarla al più presto
{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
