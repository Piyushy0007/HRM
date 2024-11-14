@component('mail::message')


Subject : {{ $data['subject'] }}<br>
Message : {{ $data['message'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent

