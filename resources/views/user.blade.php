<!DOCTYPE html>
<html dir="@yield('dir')" lang="@yield('lang')">
<head>
    <meta charset="UTF-8" />
    <meta name="robots" content="@yield('robots')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('fix_title') :: @yield('sub_title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    @section('stylesheets')
        <link href="assets/tools/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/tools/bootstrap/css/bootstrap-rtl.min.css" rel="stylesheet">
        <link href="assets/tools/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    @show
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body>
@include('admin.fix.header')
<div class="container">
    @yield('content')
</div>
@include('admin.fix.footer')
@section('javascript')
     <!--[if lt IE 9]>
    <script src="{{ asset('assets/tools/bootstrap/js/html5shiv.js') }}"></script>
    <script src="{{ asset('assets/tools/bootstrap/js/respond.js') }}"></script>
    <![endif]-->
    <script src="assets/tools/bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/tools/bootstrap/js/bootstrap.min.js') }}"></script>
@show
</body>
</html>
