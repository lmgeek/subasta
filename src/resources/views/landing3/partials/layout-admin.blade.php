<?php $layoutadmin = 1; ?>
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
        @include('landing3/partials/dashboard-sidebar')
        {{--@include('landing3/partials/sidebar-admin')--}}
        @yield('content')
        @include('/landing3/partials/dashboard-footer')
{{--        @include('landing3/partials/copyright')--}}
    </div>
    </div>
</div>
</div>
@include('/landing3/partials/pop-up-barco')
@include('landing3/partials/js')
<input type="hidden" name="csrf-token" id="csrf" content="{{ Session::token() }}">
</body>
</html>