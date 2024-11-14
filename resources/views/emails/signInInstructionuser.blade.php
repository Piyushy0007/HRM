@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
          <img src="https://i.postimg.cc/zGGnhNPd/logo.png" alt="Eyewitness">
        @endcomponent
    @endslot

{{-- Body --}}
    Dear {{$user->firstname." ".$user->lastname}}, You have been added as a Watcher and given access to the Eye Witness App. You can download the app from Google play store or Apple store 

    Login information:

    Username: {{$user->email}}
	Temporary Password: {{$user->plain_password}}

	If you have any questions, please contact us directly at <a href="mailto:info@crimeagitatorapp.com">info@crimeagitatorapp.com</a>


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


