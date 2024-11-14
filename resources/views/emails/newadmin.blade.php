@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
          <img src="https://i.postimg.cc/zGGnhNPd/logo.png" alt="Eyewitness">
        @endcomponent
    @endslot

{{-- Body --}}
    Dear {{ $data['contactname'] }}, 

    You have been added as a admin. Please visit: <a href="http://www.crimeagitator.com">http://admin.crimeagitator.com</a> to access the portal.

    Login information:

    Username : {{ $data['contactname'] }}
	Temporary Password : {{ $data['password'] }}
	Email : {{ $data['email'] }}
	Phone: {{ $data['phone'] }}

   
    If you have any questions, please contact us directly at <a href="mailto:info@crimeagitatorapp.com">info@crimeagitatorapp.com</a>
	We advise logging in from a desktop or laptop web browser.

    

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
