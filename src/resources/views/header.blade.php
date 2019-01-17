<?php
use Carbon\Carbon;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/favicon.ico">
    <link rel="shortcut icon" type="image/ico" href="/img/favicon.ico" />
    <title>Subastas Ya :: Subastas del Mar</title>
  
    <!-- Aplication CSS -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="/css/plugins/star_rating/jquery.raty.css">
    <link rel="stylesheet" href="/css/plugins/datetimepicker/bootstrap-datetimepicker.css">
    <link rel="stylesheet" href="/css/plugins/jasny/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="/css/plugins/chosen/chosen.css" >

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/landing/css/bootstrap.css">
    <link rel="stylesheet" href="/landing/font-awesome/css/font-awesome.css">

    <!-- Animation CSS -->
    {{--<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />--}}
    <link rel="stylesheet" href="/css/plugins/toastr/toastr.min.css">
    {{--<link rel="stylesheet" href="/landing/css/animate.min.css">--}}

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="/landing/css/style.css">
    <link rel="stylesheet" href="/landing/css/styles.css">
    <link rel="stylesheet" href="/landing/css/header.css">


            <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    

            <!-- Google Analytics -->
    {{--<script>--}}
        {{--$(document).ready(function(){--}}
            {{--var btn = $('.noDblClick').click(function (e) {--}}
                {{--var isValid = $(this).parents('form')[0].checkValidity();--}}
                {{--if(isValid) {--}}
                    {{--$(this).button('loading');--}}
                {{--}--}}
            {{--})--}}
        {{--});--}}

        {{--(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){--}}
                    {{--(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),--}}
                {{--m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)--}}
        {{--})(window,document,'script','//www.google-analytics.com/analytics.js','ga');--}}

        {{--ga('create', '', { 'userId': '2' });--}}
        {{--ga('send', 'pageview');--}}
    {{--</script>--}}
    <!-- End Google Analytics -->
</head>

<body id="page-top">

    {{-- Inicio Menu --}}
     <header role="banner" class="nav-header nav-header-plus">
    <div class="nav-bounds">
        <a class="nav-logo" href="#" tabindex="1"></a>
        <input type="checkbox" id="nav-header-menu-switch">
        <div class="nav-header-menu-wrapper">
        <label for="nav-header-menu-switch" aria-controls="nav-header-menu" tabindex="3">
            <span class="hamburger-top-bread"></span>
            <span class="hamburger-patty"></span>
            <span class="hamburger-bottom-bread"></span>
            </label>
                <nav id="nav-header-menu">
                @if (Auth::check())                   
                        <div class="nav-header-user">
                            <label for="nav-header-user-switch" tabindex="4">

                                <a data-toggle="dropdown" class="dropdown-toggle nav-header-user-myml" href="#">
                                    <span class="clear">
                                        <span class="block m-t-xs">
                                            <img class="user-img" alt="image" width="20" height="20" src="/landing/img/header_usuario.png" />
                                            <strong class="font-bold">{{ Auth::user()->name }}</strong>
                                        </span>
                                        <span class="text-muted text-xs block">
                                            <b class="caret"></b>
                                        </span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu animated fadeInDown m-t-xs">
                                    <li>

                                        <a href="{{ url('/home" style="border-left: none;padding-left: 10px; background-color: #2680ff!important;">
                                            <i class="fa fa-tachometer" aria-hidden="true"></i>
                                            Mi cuenta
                                        </a>
                                    </li>
                                    {{--<li>--}}
                                        {{--<a href="{{ url('/profile/changepassword">{{ Lang::get('profile.change_password.title</a>--}}
                                    {{--</li>--}}
                                    {{--<li class="divider"></li>--}}
                                    <li>

                                        <a href="{{ url('/auth/logout" style="border-left: none;padding-left: 10px;">
                                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                                            {{ Lang::get('general.logout
                                        </a>


                                    </li>
                                </ul>

                                <{{-- a href="/home" class="nav-header-user-myml">
                                    
                                        <img class="user-img" alt="image" width="20" height="20" src="/landing/img/header_usuario.png" />
                                        <span class="nav-header-username">{{ Auth::user()->name }}</span>
                                        <span class="nav-header-username-chevron"></span>
                                    
                                </a> --}}
                            </label>
                            <input type="checkbox" id="nav-header-user-switch">
                            <nav class="nav-header-user-layer user-menu">
                                <div class="user-menu__main">
                                    <div class="user-menu__shortcuts ">
                                        <a href="{{ url('/home" data-id="home" rel="nofollow">
                                            <i class="fa fa-tachometer" style="font-size: 22px;"></i>&nbsp;&nbsp;
                                          Mi cuenta
                                        </a>
                                        <a href="{{ url('/auth/logout" data-id="logout" rel="nofollow">
                                            <i class="fa fa-sign-out" style="font-size: 22px;"></i>&nbsp;&nbsp;
                                          {{ Lang::get('general.logout
                                        </a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <a href="#" rel="nofollow" tabindex="5" id="notiLink">
                            <img alt="image" width="20" height="20" src="/landing/img/header_notificaciones.png" data-toggle="tooltip" data-placement="top" data-original-title="Notificaciones"/>
                        </a>
                        <a href="#" rel="nofollow" tabindex="5" aria-owns="modeless-48" aria-haspopup="true" popup-hidden="true" style="border-left: none">
                            <img alt="image" width="20" height="20" src="/landing/img/header_favoritos.png" data-toggle="tooltip" data-placement="top" data-original-title="Favoritos"/><span></span>
                        </a>
                        <a href="#"  tabindex="7" style="border-right: 1px solid #FFFFFF">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </a>
                        {{-- <a href="#"  tabindex="7"></a> --}}
                    </nav>


                @else
                    {{-- <a href="#" class="option-register" rel="nofollow">Registrate</a> --}}
                    <a href="{{ url('auth/login" class="option-login" rel="nofollow">
                     <img alt="image" width="20" height="20" src="/landing/img/header_usuario.png" /> Ingresá</a>
                    {{-- <a href="#" class="option-help" rel="nofollow">Ayuda</a> --}}
                    {{-- <a href="#" class="option-sell" rel="nofollow">Vender </a></nav> --}}
                @endif
        </div>
        <form class="nav-search" action="#" method="GET" role="search">
            <input type="text" class="nav-search-input" name="as_word" placeholder="Buscar subas, especia, barco y más..." maxlength="120" autofocus="" tabindex="2">
            <button class="nav-search-clear-btn" type="button" title="Limpiar"></button>
            <button class="nav-search-close-btn" type="button" title="Cerrar"></button>
            <button type="submit" class="nav-search-btn" tabindex="3">
                <i class="fa fa-search">
                    <span>Buscar</span>
                </i>
            </button>
        </form>
        <div class="nav-menu">
            <div>
                <ul class="nav navbar-nav">
                  <li class="dropdown nav-menu-categories">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Categorías
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu animated fadeInDown ">
                      <li class="nav-menu-item"><a href="#">Especies</a></li>
                      <li class="nav-menu-item"><a href="#">Puertos</a></li>
                      <li class="nav-menu-item"><a href="#">Barcos</a></li>
                      <li class="nav-menu-item"><a href="#">Logistica</a></li>
                      <li class="nav-menu-item"><a href="#">Conservas</a></li>
                      <li class="nav-menu-item"><a href="#">Naval</a></li>
                    </ul>
                  </li>
                  
                  <li class="nav-menu-item">
                    <a href="{{ url('/auction" class="nav-menu-item-link" rel="nofollow" data-js="nav-menu-item-trigger">Subastas del día</a></li>
                  <li class="nav-menu-item">
                    <a href="#" class="nav-menu-item-link" rel="nofollow" data-js="nav-menu-item-trigger">Tu historial</a></li>
                  <li class="nav-menu-item">
                    <a href="{{ url('/bids" class="nav-menu-item-link" rel="nofollow" data-js="nav-menu-item-trigger">Tus compras</a></li>
                  <li class="nav-menu-item">
                    <a href="{{ url('/sellerbatch" class="nav-menu-item-link" rel="nofollow" data-js="nav-menu-item-trigger">Subastar</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

{{-- Fin menu --}}