@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
          <img src="https://i.postimg.cc/zGGnhNPd/logo.png" alt="Eyewitness">
        @endcomponent
    @endslot

{{-- Body --}}
    Dear {{$user->clientname}}

    Your password has been reset, new login details are below:

    Username: {{$user->email}}
	Password: {{$user->plain_password}}

	If you have any questions, please direct them to your scheduling manager.

	We look forward to serving you 

{{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}.
        @endcomponent
    @endslot
@endcomponent


