@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
          <img src="https://i.postimg.cc/zGGnhNPd/logo.png" alt="Eyewitness">
        @endcomponent
    @endslot

{{-- Body --}}
    Dear {{$user->firstname." ".$user->lastname}}, Thank you for registering as a Concerned Citizen.


    For more information and support please visit: <a href="http://www.crimeagitator.com">http://www.crimeagitator.com</a>
    

    If you are interested in joining a community or being a community lead, please email us at <a href="mailto:info@crimeagitatorapp.com">info@crimeagitatorapp.com</a>

{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}.
        @endcomponent
    @endslot
@endcomponent