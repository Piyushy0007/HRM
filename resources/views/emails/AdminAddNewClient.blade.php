@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            new client 
        @endcomponent
    @endslot

{{-- Body --}}

    Dear {{ $user['contactname'] }}


    
    Use Bellow credentails to login:
    
    User Email Address: {{  $user['email'] }}
	Password: {{ $user['password'] }}

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
            © {{ date('Y') }} {{ config('app.name') }}. Super FOOTER!
        @endcomponent
    @endslot
@endcomponent