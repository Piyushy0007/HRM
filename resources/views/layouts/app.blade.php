<!doctype html>
<?PHP
header('Access-Control-Allow-Origin: *');
?>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="7200;url=/login" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AHR</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link rel="icon" href="/favicon.ico">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="app">

        <!-- <nav class="navigation bg-custom-primary">
            <ul class="flex justify-center">
                <router-link tag="li" :to="{ name: 'home' }" exact class="md:w-40 xl:w-64"><a href="#">HOME</a></router-link>
                <router-link tag="li" :to="{ name: 'schedules' }" class="md:w-40 xl:w-64"><a href="#">SCHEDULES</a></router-link>
                <router-link tag="li" :to="{ name: 'employees' }" class="md:w-40 xl:w-64"><a href="#">EMPLOYEES</a></router-link>
                <router-link tag="li" :to="{ name: 'messaging' }" class="md:w-40 xl:w-64"><a href="#">MESSAGING</a></router-link>
                <router-link tag="li" :to="{ name: 'reports' }" class="md:w-40 xl:w-64"><a href="#">REPORTS</a></router-link>
                <router-link tag="li" :to="{ name: 'on-now' }" class="md:w-40 xl:w-64"><a href="#">ON NOW</a></router-link>
                <router-link tag="li" :to="{ name: 'settings' }" class="md:w-40 xl:w-64"><a href="#">SETTINGS</a></router-link>
                <li class="md:w-40 xl:w-64">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">SIGNOUT</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav> -->

        <router-view></router-view>
    </div>


<!-- Le javascripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{ asset('js/socket.io.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script src="https://maps.google.com/maps/api/js?libraries=drawing,places&key=AIzaSyDfX422PdA9Yy6GOf4HeRBRTtfoz-AGQdU"></script>
<script src="https://cdn.rawgit.com/bjornharrtell/jsts/gh-pages/1.4.0/jsts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
</body>
</html>
