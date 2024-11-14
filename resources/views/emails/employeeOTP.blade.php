@component('mail::message')


Your One-Time OTP is: {{ $data['verification_code'] }}<br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
