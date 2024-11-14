@component('mail::message')

Subject: {{ $message["subject"] }}<br />
Message: {{ $message["message"] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
