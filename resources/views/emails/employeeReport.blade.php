@component('mail::message')

A-HR Report has been submitted<br><br>

Category: {{ $data['category'] }}<br>
Description: {{ $data['description'] }}<br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent