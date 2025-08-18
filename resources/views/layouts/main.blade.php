<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="www.rental.myindexpert.com" />
    <title>{{ $title ?? __("Rental is the easiest way to find your dream home") }}</title>
  <link rel="shortcut icon" href="{{asset('wordpress/2022/06/cropped-cropped-projet_ariane-removebg-preview-32x32.png')}}" />
  {{--   <link rel="shortcut icon" href="{{ asset('common') }}/favicon.png" /> --}}
    @include('meta::manager') 
    <meta name="description" content="" />
    <meta name="keywords" content="immobilier" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="{{ asset('common') }}/fonts/icomoon/style.css" />
    <link rel="stylesheet" href="{{ asset('common') }}/fonts/flaticon/font/flaticon.css" />

    <link rel="stylesheet" href="{{ asset('common') }}/css/tiny-slider.css" />
    <link rel="stylesheet" href="{{ asset('common') }}/css/aos.css" />
    <link rel="stylesheet" href="{{ asset('common') }}/css/style.css" />
    @include('layouts.partials._loader_style') 
    @yield('custom_css')
    <style>
      .dash{
        background-color: white;
        color: #005555 !important;
      }
      .option { cursor: pointer; 
      }

      .counter-item:hover{
            background-color: rgba(88, 241, 241, 0.126);
            cursor: pointer; 
      }
    </style>
 @if ($_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['SERVER_NAME'] != '127.0.0.1')
       @include('layouts.partials.analytics')
       <script src="https://kit.fontawesome.com/93901d031a.js" crossorigin="anonymous"></script>
       
       @else
       <link rel="stylesheet" href="{{ asset('fontawesome-free-6.4.2-web') }}/css/all.css" />

  @endif

  </head>
  <body>
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icofont-close js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    {{-- nav start --}}

    @include('layouts.partials.navbar')

    {{-- nav end --}}

    @yield('content')

    <!-- /.site-footer -->
    @include('layouts.partials.footer')
    <!-- /.site-footer -->

    <!-- Preloader -->
    <div id="overlayer"></div>
    <div class="loader">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

   @include('layouts.partials.scripts')
   
   @yield('custom_script')
  </body>
</html>
