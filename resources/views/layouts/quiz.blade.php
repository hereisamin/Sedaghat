<!doctype html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@400;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pikabu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body style="background-color: #0275d8; height: 100%">
<div class="overlay d-none" id="overlay" style="  position:fixed; z-index:99999;  top:0;  left:0;  bottom:0;  right:0;  background: rgba(255,251,253,0.71);  transition: 1s 0.4s;">
    <div style="  margin: 0;  position: absolute;  top: 50%;  -ms-transform: translateY(-50%); width: 100%;   text-align: center;  transform: translateY(-50%);">
        <div class="spinner-grow text-primary" style="width: 10rem; height: 10rem;" role="status">
            <img src="{{ asset('img/qq3.png') }}" style="    margin-top: 40px;" height="" width="80%">
        </div>
    </div>
</div>
<!-- the viewport -->

    <!-- the left sidebar -->

    <!-- the main page content -->

        <!-- Overlay is needed to have a big click area to close the sidebars -->

        <header>

        </header>
        <div class="container" style="height: 100%;" >
            @yield('content')
        </div>


<script src="{{ asset('js/pikabu.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<script src="{{ asset('js/app.js') }}" ></script>
<script>
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
