<!DOCTYPE html>
<html class="no-js css-menubar" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}{{ trim($__env->yieldContent('title')) ? ' | ' : '' }}@yield('title')</title>

    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-extend.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.min.css') }}">

    <!-- Plugins -->
    <!--<link rel="stylesheet" href="{{ asset('assets/vendor/animsition/animsition.css') }}">-->
    <link rel="stylesheet" href="{{ asset('assets/vendor/asscrollable/asScrollable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/switchery/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/intro-js/introjs.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/slidepanel/slidePanel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.css') }}">
    @stack('stylesheets_plugins')

    <!-- Fonts -->
    @stack('stylesheets_fonts')
    <link rel="stylesheet" href="{{ asset('assets/fonts/font-awesome/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/web-icons/web-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/brand-icons/brand-icons.min.css') }}">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>

    <!-- Skin -->
    <link rel="stylesheet" href="{{ asset('assets/skins/blue-grey.min.css') }}">

    <!--[if lt IE 9]>
    <script src="{{ asset('assets/vendor/html5shiv/html5shiv.min.js') }}"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="{{ asset('assets/vendor/media-match/media.match.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/respond/respond.min.js') }}"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="{{ asset('assets/vendor/breakpoints/breakpoints.js') }}"></script>
    <script>
      Breakpoints();
    </script>
    @if (str_contains(url('/'), ':8080'))
    <style media="screen">
      .navbar-container {
        background-color: #dc3545!important;
        color: #FFF !important;
      }
      .navbar-header {
        background-color: #dc3545!important;
        color: #FFF !important;
      }
    </style>
    @endif
  </head>
  <body class="animsition @yield('body-class')">
    <!--[if lt IE 8]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    @include('layouts.app._navbar')

    @include('layouts.app._menubar')

    @include('layouts.app._gridmenu')

    @yield('content')

    @include('layouts.app._footer')

    <!-- Core  -->
    <script src="{{ asset('assets/vendor/babel-external-helpers/babel-external-helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/popper-js/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.js') }}"></script>
    <!--<script src="{{ asset('assets/vendor/animsition/animsition.js') }}"></script>-->
    <script src="{{ asset('assets/vendor/mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('assets/vendor/asscrollbar/jquery-asScrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/asscrollable/jquery-asScrollable.js') }}"></script>
    <script src="{{ asset('assets/vendor/ashoverscroll/jquery-asHoverScroll.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/vendor/switchery/switchery.js') }}"></script>
    <script src="{{ asset('assets/vendor/intro-js/intro.js') }}"></script>
    <script src="{{ asset('assets/vendor/screenfull/screenfull.js') }}"></script>
    <script src="{{ asset('assets/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
    @stack('scripts_plugins')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/Component.js') }}"></script>
    <script src="{{ asset('assets/js/Plugin.js') }}"></script>
    <script src="{{ asset('assets/js/Base.js') }}"></script>
    <script src="{{ asset('assets/js/Config.js') }}"></script>

    <script src="{{ asset('assets/js/Section/Menubar.js') }}"></script>
    <script src="{{ asset('assets/js/Section/GridMenu.js') }}"></script>
    <script src="{{ asset('assets/js/Section/Sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/Section/PageAside.js') }}"></script>
    <script src="{{ asset('assets/js/Plugin/menu.js') }}"></script>

    <script src="{{ asset('assets/js/config/colors.js') }}"></script>
    <script src="{{ asset('assets/js/config/tour.js') }}"></script>
    <script>Config.set('assets', '{{ asset('assets') }}');</script>

    <!-- Page -->
    <script src="{{ asset('assets/js/Site.js') }}"></script>
    <script src="{{ asset('assets/js/Plugin/asscrollable.js') }}"></script>
    <script src="{{ asset('assets/js/Plugin/slidepanel.js') }}"></script>
    <script src="{{ asset('assets/js/Plugin/switchery.js') }}"></script>
    @stack('scripts_page')

    @include('layouts.app._toastr')

    <script>
      (function(document, window, $){
        'use strict';

        var Site = window.Site;
        $(document).ready(function(){
          Site.run();
        });
      })(document, window, jQuery);
    </script>
  </body>
</html>
