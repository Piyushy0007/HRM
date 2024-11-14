@component('mail::message')


Dear {{ $user['clientname'] }},<br>

Email : {{ $user['email'] }} <br>
Password : {{ $user['password'] }}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
