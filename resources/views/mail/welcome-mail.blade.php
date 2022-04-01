@component('mail::message')
# Welcome to mailing test

The body of your message.

@component('mail::button', ['url' => 'https://www.google.com/imgres?imgurl=https%3A%2F%2Fpbs.twimg.com%2Fmedia%2FFOQCH-sXIAQTb15%3Fformat%3Djpg%26name%3Dsmall&imgrefurl=https%3A%2F%2Fwww.sportmediaset.mediaset.it%2Fcalcio%2Fmilan%2Fmilan-maignan-posta-sui-social-una-scimmia-con-il-dito-medio-contro-il-razzismo_47621563-202202k.shtml&tbnid=Q8alNyOccHS0GM&vet=12ahUKEwiV5u7kjfP2AhUC_RoKHYY-CtUQMygEegUIARCvAQ..i&docid=AWnF4tIDp1-w1M&w=680&h=489&q=foto%20scimmia%20con%20dito%20medio&client=ubuntu&ved=2ahUKEwiV5u7kjfP2AhUC_RoKHYY-CtUQMygEegUIARCvAQ'])
link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
