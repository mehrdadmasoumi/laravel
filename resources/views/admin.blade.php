<!DOCTYPE html>
<html  dir="{{ Config::get('app.dir') }}" lang="{{ Config::get('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <meta name="robots" content="@yield('robots')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('fix_title') :: @yield('sub_title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    @section('stylesheets')
        <link href="{{ asset('assets/tools/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/tools/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/tools/fonts/fonts.css') }}" rel="stylesheet">
        @if(Config::get('app.dir') == 'rtl')
            <link href="{{ asset('assets/tools/bootstrap/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
            <link href="{{ asset('assets/admin/css/rtl/style-rtl.css') }}" rel="stylesheet">
            <link href="{{ asset('assets/admin/css/rtl/style-rtl-responsive.css') }}" rel="stylesheet">
        @else
            <link href="{{ asset('assets/admin/css/ltr/style-ltr.css') }}" rel="stylesheet">
            <link href="{{ asset('assets/admin/css/ltr/style-ltr-responsive.css') }}" rel="stylesheet">
            <link href="{{ asset('assets/admin/css/ltr/bootstrap-rest-ltr.css') }}" rel="stylesheet">
            <link href="{{ asset('assets/admin/css/ltr/style.css') }}" rel="stylesheet">
        @endif
        @show
    <!--[if lt IE 9]>
        <script src="{{ asset('assets/tools/bootstrap/js/html5shiv.js') }}"></script>
        <script src="{{ asset('assets/tools/bootstrap/js/respond.js') }}"></script>
        <![endif]-->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body>
<section id="container">
    <header class="header white-bg">
        @include('admin.fix.header')
    </header>
    <aside>@include('admin.fix.sidebar')</aside>
    <section id="main-content">
        <section class="wrapper">
            <div id="inner">
                <div class="row">
                    <div class="col-xs-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
    </section>
    <footer class="site-footer">
        @include('admin.fix.footer')
    </footer>
</section>
@section('javascript')
    <script src="{{ asset('assets/tools/bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/tools/bootstrap/js/bootstrap.min.js') }}"></script>
    {{-- admin js --}}
    <script src="{{ asset('assets/admin/js/dcjqaccordion.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.scroll.js') }}"></script>
    @if(Config::get('app.dir') == 'rtl')
        <script src="{{ asset('assets/admin/js/rtl/common-scripts.js') }}"></script>
    @else
        <script src="{{ asset('assets/admin/js/ltr/common-scripts.js') }}"></script>
    @endif
    <script>
        $(window).on("resize load",function(event){
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }
            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        });
    </script>
@show
</body>