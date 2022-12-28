<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>San pedro</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{!! asset('vendor/bootstrap/css/bootstrap.min.css') !!}">

        <link rel="stylesheet" href="{!! asset('vendor/bootstrap/css/bootstrap.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('assets/css/fontawesome.css') !!}">
        <link rel="stylesheet" href="{!! asset('assets/css/templatemo-woox-travel.css') !!}">
        <link rel="stylesheet" href="{!! asset('assets/css/owl.css') !!}">
        <link rel="stylesheet" href="{!! asset('assets/css/animate.css') !!}">
        <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
        
    </head>
    <body class="antialiased">
       <div id="app">
        @yield('cuerpo')
        </div> 

    <script src="{!! asset('vendor/jquery/jquery.min.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('vendor/bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
    
    <script src="{!! asset('assets/js/isotope.min.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/js/owl-carousel.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/js/tabs.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/js/popup.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('assets/js/custom.js') !!}" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" type="text/javascript"></script>
    <script src="{!! asset('assets/js/main.js') !!}" type="text/javascript"></script>
    </body>
</html>
