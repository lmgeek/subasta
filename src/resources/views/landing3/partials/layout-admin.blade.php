<!doctype html>
<html lang="es">
<head>
    <title>Subastas del Mar @yield('title','')</title>
    @include('landing3/partials/common')
</head>
<body class="gray">
<div id="wrapper">
@include('landing3/partials/header')
    <div class="dashboard-container">
        @include('landing3/partials/sidebar-admin')
        @yield('content')
    </div>
    <div id="notificationsauction"></div>
    <div id="Loader" ><div id="LoaderContent"><img src="/landing3/images/logo2.png" alt="Subastas del Mar"><br><div class="fa fa-spinner fa-pulse fa-3x fa-fw"></div></div></div>
    <div id="footer">
    @include('landing3/partials/footer-top')
    @include('landing3/partials/footer-mid')
    @include('landing3/partials/copyright')
    </div>
</div>
@include('landing3/partials/js')
<input type="hidden" name="csrf-token" id="csrf" content="{{ Session::token() }}"> 
</body>
</html>