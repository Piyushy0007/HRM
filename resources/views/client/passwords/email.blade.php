<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->

    
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/login.css') }}" rel="stylesheet">
</head>
<body>


    <div class="h-screen flex items-center client-login" id="app"> 

        <div class="mx-auto mt-5" style="width: 450px">

            <form class="bg-white px-16 pt-20 pb-5"  action="{{ route('client.reset') }}"  method="POST">
                @csrf
                <img src="{{ asset('images/logo.png') }}" class="mx-auto mb-3" alt="" style="margin-top: -165px;">
            
                <h2 class="text-center text-3xl font-bold leading-none mb-8 mt-8">RESET PASSWORD</h2>

                <div class="mb-4">
                    <label class="block text-sm px-4 text-center mb-3" for="username">Enter your email. We’ll send you a link to reset your password.</label>
                    <input class="text-sm text-center appearance-none w-full py-3 px-4 leading-tight focus:outline-none rounded-full" id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
                       
                        @if ( $errors->any() )
                        <div class="bg-red-100 text-center text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Email not found</strong>
                        </div>
                        @endif
                        @if ( session('error') )
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline"> {{ session('error') }}</span>
                        </div>
                        @endif
                        @if ( session('success') )
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">success!</strong>
                            <span class="block sm:inline"> {{ session('success') }}</span>
                        </div>
                        @endif

                       
                       
               
                </div>

                <div class="text-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-full focus:outline-none focus:shadow-outline w-full mb-8" type="submit">RESET PASSWORD</button>
                    <!-- <a class="text-sm forgot-password" href="{{ route('password.request') }}" onclick="alert('test')">Forgot your password?</a> -->
                    <b> Remember Password? </b><a class="" style="color: #237BDF;" href="/client-login">Log In </a>
                </div>
            </form>
        </div>

    </div>

<!-- Le javascripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>


</script>

</body>
</html>
