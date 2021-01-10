<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Fonts -->
{{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@400;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pikabu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-16x16.png') }}" sizes="16x16" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        body{
            font-family: 'Almarai', sans-serif;
        }
    </style>
    <script src="https://kit.fontawesome.com/d8020608a5.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="overlay" id="overlay" style="  position:fixed; z-index:99999;  top:0;  left:0;  bottom:0;  right:0;  background: rgba(255,251,253,0.71);  transition: 1s 0.4s;">
    <div style="  margin: 0;  position: absolute;  top: 50%;  -ms-transform: translateY(-50%); width: 100%;   text-align: center;  transform: translateY(-50%);">
        <div class="spinner-grow text-primary" style="width: 10rem; height: 10rem;" role="status">
            <img src="{{ asset('img/qq3.png') }}" style="    margin-top: 40px;" height="" width="80%">
        </div>
    </div>
</div>

@yield('after_body')
<!-- the viewport -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: rgba(52,144,220,0.59)">
                @auth
                    <button class="navbar-toggler rtl" style="background-color: #9fc4e8" type="button" >
                        <a href="{{route('home')}}"><i class="fas fa-concierge-bell"></i></a>
                    </button>
                @endauth
                <div class="nav-justified rtl" >
                    <a class="navbar-brand rtl" style="font-size: calc(30% + 0.8vw + 1vh); font-family: 'Almarai', sans-serif; " href="{{ url('/') }}"><strong>{{ config('app.name', 'کیو کیو') }}</strong></a>
                </div>
                <button class="navbar-toggler rtl" style="background-color: #9fc4e8" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse rtl" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        @guest
                            <a class="nav-item nav-link active" href="{{ route('login') }}">{{ __('ورود') }}<span class="sr-only">(current)</span></a>
                            @if (Route::has('register'))
                                <a class="nav-item nav-link" href="{{ route('register') }}">{{ __('ثبت نام') }}</a>
                            @endif
                        @else
                            <a class="nav-item nav-link @if (Request::is('home')) disabled @endif " href="{{route('home')}}" tabindex="-1" aria-disabled="true">{{__('پیشخوان')}}</a>
                            <a class="nav-item nav-link" onclick="event.preventDefault();    document.getElementById('logout-form').submit();" href="{{ route('logout') }}">{{ __('خروج') }}</a>
                            <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">{{ Auth::user()->name }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </div>
                </div>
            </nav>
        </header>
        <div class="container h-100">
            @yield('content')
        </div>
<script src="{{ asset('js/pikabu.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="{{ asset('js/app.js') }}" ></script>
<script src="https://kit.fontawesome.com/d8020608a5.js" crossorigin="anonymous"></script>
<script>
    @yield('script')

    $(document).ready(function() {
            window.setTimeout("fadeMyDiv();", 500); //call fade in 3 seconds
        }
    )
    function fadeMyDiv() {
        $("#overlay").fadeOut('slow');
    }


    $(function() {
        pikabu = new Pikabu();

        // Script to adjust element heights for demo
        $('.m-heights-demo').on('click', 'a', function(e) {
            var $link = $(this), target = $link.data('target');

            e.preventDefault();

            $('.is-active').removeClass('is-active');
            $link.parents('li').addClass('is-active');

            $('.m-dummy-height').remove();

            target && $(target).append($('<div class="m-dummy-height">'));
            pikabu.closeSidebars();
        });

    });
</script>
</body>
</html>
