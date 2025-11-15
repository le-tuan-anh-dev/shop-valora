<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Katie"/>
    <meta name="keywords" content="Katie"/>
    <meta name="author" content="pixelstrap"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Katie - Online Fashion Store')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('client/assets/images/favicon.png')}}" type="image/x-icon"/>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/"/>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet"/>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/fontawesome.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/iconsax.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/swiper-slider/swiper-bundle.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/vendors/toastify.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/style.css')}}"/>
    
    @stack('styles')
</head>
<body class="@yield('body-class', '')">
    
     <div class="tap-top">
      <div><i class="fa-solid fa-angle-up"></i></div>
    </div>
    
    @include('client.layouts.header')
    
    @yield('content')
    
    @include('client.layouts.footer')
    
    <!-- JS Files -->
    <script src="{{ asset('client/assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('client/assets/js/iconsax.js')}}"></script>
    <script src="{{ asset('client/assets/js/stats.min.js')}}"></script>
    <script src="{{ asset('client/assets/js/cursor.js')}}"></script>
    <script src="{{ asset('client/assets/js/swiper-slider/swiper-bundle.min.js')}}"></script>
    <script src="{{ asset('client/assets/js/swiper-slider/swiper-custom.js')}}"></script>
    <script src="{{ asset('client/assets/js/countdown.js')}}"></script>
    <script src="{{ asset('client/assets/js/newsletter.js')}}"></script>
    <script src="{{ asset('client/assets/js/touchspin.js')}}"></script>
    <script src="{{ asset('client/assets/js/cookie.js')}}"></script>
    <script src="{{ asset('client/assets/js/toastify.js')}}"></script>
    <script src="{{ asset('client/assets/js/theme-setting.js')}}"></script>
    <script src="{{ asset('client/assets/js/script.js')}}"></script>
    
    @stack('scripts')
</body>
</html>