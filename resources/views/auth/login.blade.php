<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/login.css') }}" rel="stylesheet">
</head>
<body>

    <div class="h-screen flex items-center c-login" id="app">

        <div class="mx-auto mt-5" style="width: 450px">
            <form class="bg-white px-16 pt-32 pb-5" action="{{ route('login') }}" method="POST">
                @csrf

                <img src="{{ asset('images/logo.png') }}" class="mx-auto mb-3" alt="" style="margin-top: -165px;">
            
                <h2 class="text-center text-3xl font-bold leading-none mb-8">ADMIN LOGIN</h2>
                
                @if ( $errors->any() )
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->all()[0] }}</span>
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm px-4" for="username">E-mail Address</label>
                    <input class="text-sm appearance-none w-full py-3 px-4 leading-tight focus:outline-none rounded-full" id="email" type="email" placeholder="Enter e-mail" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="block text-sm px-4" for="password">Password</label>
                    <div class="flex items-center relative">
                        <input class="text-sm appearance-none w-full py-3 pl-4 pr-10 leading-tight focus:outline-none rounded-full" id="password" type="password" placeholder="Enter password" name="password" required>
                        <a href="#" onclick="togglePasswordVisibility()" class="absolute right-0 mr-4">
                            <font-awesome-icon icon="eye-slash" flip="horizontal" id="eyeClose" class="text-gray-500" />
                        </a>
                        <a href="#" onclick="togglePasswordVisibility()" class="absolute right-0 mr-4">
                            <font-awesome-icon icon="eye" flip="horizontal" id="eyeOpen" class="text-gray-600" />
                        </a>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 px-4">
                        <input class="mr-2 leading-tight" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="text-sm">Keep me signed in</span>
                    </label>
                </div>

                <div class="text-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 rounded-full focus:outline-none focus:shadow-outline w-full mb-8" type="submit">Sign In</button>
                    <!-- <a class="text-sm forgot-password" href="{{ route('password.request') }}" onclick="alert('test')">Forgot your password?</a> -->
                    
                </div>
            </form>
        </div>

    </div>

<!-- Le javascripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
document.getElementById('email').focus()
document.getElementById('eyeOpen').style.display = 'none'
function togglePasswordVisibility() {
    let x = document.getElementById('password')
    if (x.type === 'password') {
        x.type = 'text'
        document.getElementById('eyeClose').style.display = 'none'
        document.getElementById('eyeOpen').style.display = 'block'
    } else {
        x.type = 'password'
        document.getElementById('eyeClose').style.display = 'block'
        document.getElementById('eyeOpen').style.display = 'none'
    }
}

</script>

</body>
</html>
