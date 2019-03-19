
<header id="header-container" class="fullwidth  <?=(isset($outsidehome) || isset($layoutadmin))?'dashboard-header no-sticky':'transparent-header'?>">
    <!-- Header -->
    <div id="header">
        <div class="container">

            <!-- Left Side Content -->
            <div class="left-side">

                <!-- Logo -->
                <div id="logo">
                    <a href="/"><img src="/landing3/images/logo2.png" data-sticky-logo="/landing3/images/logo.png" data-transparent-logo="/landing3/images/logo2.png" alt="Subastas del Mar"></a>
                </div>

                <!-- Main Navigation -->
                <nav id="navigation">
                    <ul id="responsive">

                        <li><a href="/" class="current simple">Home</a></li>

                        <!--<li><a href="#">Categor&iacute;as</a>
                            <ul class="dropdown-nav">
                                <li><a href="#">Especies</a></li>
                                <li><a href="#">Puertos</a></li>
                                <li><a href="#">Barcos</a></li>
                                <li><a href="#">Log&iacute;stica</a></li>
                                <li><a href="#">Conservas</a></li>
                                <li><a href="#">Naval</a></li>
                            </ul>
                        </li>-->

                        <li><a href="/subastas">Subastas</a>
                            <ul class="dropdown-nav">
                                <li><a href="/subastas?time=all">Todas</a></li>
                                <li><a href="/subastas?time=incourse">Finalizando</a></li>
                                <li><a href="/subastas?time=future">Pr&oacute;ximas</a></li>
                                <li><a href="/subastas?time=finished">Finalizadas</a></li>
                                @if(isset(Auth::user()->type))
                                <li><a href="/subastas?type=private">Privadas</a></li>
                                @else
                                <li><a href="/auth/login">Privadas</a></li>
                                @endif
                            </ul>
                        </li>

                        <li><a href="#" class="simple">Historial</a></li>

                        <li><a href="#" class="simple">Ayuda</a></li>

                    </ul>
                </nav>
                <div class="clearfix"></div>
                <!-- Main Navigation / End -->

            </div>
            <!-- Left Side Content / End -->


            <!-- Si el usuario es vendedor aprace un icono para indicar que puede crear una nueva subasta-->
            <div class="right-side">
            @if (Auth::check())
                @if(Auth::user()->type == \App\User::VENDEDOR)
                <!--  Actions -->
                <div class="header-widget hide-on-mobile">

                    <!-- Nueva Subasta -->
                    <div class="header-notifications">

                        <!-- Trigger -->
                        <div class="header-notifications-link">
                            <a href="/subastas/agregar" data-tippy-placement="left" title="Nueva Subasta" data-tippy-theme="dark"><em class="icon-material-outline-gavel"></em></a>
                        </div>
                    </div>

                </div>
                <!--  Actions / End -->

                @endif
            @endif


            <!--  User Notifications -->
            {{--<div class="header-widget hide-on-mobile">

                <!-- Notifications -->
                <div class="header-notifications">

                    <!-- Trigger -->
                    <div class="header-notifications-trigger">
                        <a href="#" data-tippy-placement="left" title="Notificaciones" data-tippy-theme="dark"><em class="icon-feather-bell"></em><span>4</span></a>
                    </div>

                    <!-- Dropdown -->
                    <div class="header-notifications-dropdown">

                        <div class="header-notifications-headline">
                            <h4>Notificaciones</h4>
                            <button class="mark-as-read ripple-effect-dark" title="Marcar todas como leídas" data-tippy-placement="left">
                                <em class="icon-feather-check-square"></em>
                            </button>
                        </div>

                        <div class="header-notifications-content">
                            <div class="header-notifications-scroll" data-simplebar>
                                <ul>
                                    <!-- Notification -->
                                    <li class="notifications-not-read">
                                        <a href="#">
                                            <span class="notification-icon"><em class="icon-feather-clock"></em></span>
                                            <span class="notification-text">
                                                <strong>Lote de Camarones Premium</strong> est&aacute; por finalizar.<span class="color"> &iquest;Vas a dejar pasar esta oportunidad?</span>
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Notification -->
                                    <li>
                                        <a href="#">
                                            <span class="notification-icon"><em class="icon-feather-thumbs-up"></em></span>
                                            <span class="notification-text">
                                                <strong>&iexcl;Felicidades!</strong> Gustavo de Sancho hizo una oferta en tu subasta Pesca de alta mar.
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Notification -->
                                    <li>
                                        <a href="#">
                                            <span class="notification-icon"><em class="icon-line-awesome-fire"></em></span>
                                            <span class="notification-text">
                                                La subasta <strong>Cornalitos alta calidad</strong>  est&aacute; registrando muchas ofertas. <span class="color">&iexcl;Haz tu oferta ahora!</span>
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Notification -->
                                    <li>
                                        <a href="#">
                                            <span class="notification-icon"><em class="icon-feather-eye"></em></span>
                                            <span class="notification-text">
                                                Tu subasta <strong>Pesca de alta mar</strong> est&aacute; recibiendo muchas visitas. <span class="color">&iexcl;Bien hecho!</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

            </div>--}}
            <!--  User Notifications / End -->


            @if (Auth::check())
                @if(Auth::user()->type == \App\User::VENDEDOR)
                    <!-- User Menu -->
                        <div class="header-widget">

                            <!-- Messages -->
                            <div class="header-notifications user-menu">
                                <div class="header-notifications-trigger">
                                    <a href="#"><div class=""><em class="icon-feather-user"></em></div></a>
                                </div>

                                <!-- Dropdown -->
                                <div class="header-notifications-dropdown">

                                    <!-- User Status -->
                                    <div class="user-status">

                                        <!-- User Name / Avatar -->
                                        <div class="user-details">
                                            <div class="user-avatar status-online"><img src="landing3/images/avatar/icon-user-64.png" alt=""></div>
                                            <div class="user-name">
                                                {{Auth::user()->name}} <span class="blue">Subastas del Mar</span><span class="blue">Vendedor</span>
                                            </div>
                                        </div>

                                    </div>

                                    <ul class="user-menu-small-nav">
                                        <li><a href="{{url('/home')}}"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
                                        <li><a href="/usuarios/editar/<?=Auth::user()->nickname?>"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
                                        <li><a href="{{url('/barcos')}}"><em class="icon-line-awesome-ship"></em> Mis Barcos</a></li>
                                        <li><a href="#"><em class="icon-feather-box"></em> Mis Lotes</a></li>
                                        <li><a href="/subastas?time=all&type=mine"><em class="icon-material-outline-gavel"></em> Mis Subastas</a></li>
                                        <li><a href="#"><em class="icon-material-outline-gavel"></em> Ventas de Subastas</a></li>
                                        <li><a href="#"><em class="icon-feather-eye-off"></em> Ventas privadas</a></li>
                                        <li><a href="{{url('auth/logout')}}"><em class="icon-material-outline-power-settings-new"></em> Logout</a></li>
                                    </ul>

                                </div>
                            </div>

                        </div>
                        <!-- User Menu / End -->

                        <!-- Mobile Navigation Button -->
                        <span class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</span>

            </div>
            <!-- Right Side Content / End -->

        </div>
    </div>
    <!-- Header / End -->

@elseif(Auth::user()->type == \App\User::COMPRADOR)


    <!-- User Menu -->
        <div class="header-widget">

            <!-- Messages -->
            <div class="header-notifications user-menu">
                <div class="header-notifications-trigger">
                    <a href="#"><div class=""><em class="icon-feather-user"></em></div></a>
                </div>

                <!-- Dropdown -->
                <div class="header-notifications-dropdown">

                    <!-- User Status -->
                    <div class="user-status">

                        <!-- User Name / Avatar -->
                        <div class="user-details">
                            <div class="user-avatar status-online"><img src="landing3/images/avatar/icon-user-64.png" alt=""></div>
                            <div class="user-name">
                                {{Auth::user()->name}} <span class="blue">Subastas del Mar</span><span class="blue">Comprador</span>
                            </div>
                        </div>

                    </div>

                    <ul class="user-menu-small-nav">
                        <li><a href="{{url('/home')}}"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
                        <li><a href="/usuarios/editar/<?=Auth::user()->nickname?>"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
                        <li><a href="/subastas"><em class="icon-material-outline-gavel"></em> Subastas</a></li>
                        <li><a href="/subastas?type=private"><em class="icon-feather-eye-off"></em> Subastas Privadas</a></li>
                        <li><a href="{{url('/bids')}}"><em class="icon-material-outline-shopping-cart"></em> Compra</a></li>
                        <li><a href="{{url('auth/logout')}}"><em class="icon-material-outline-power-settings-new"></em> Logout</a></li>
                    </ul>

                </div>
            </div>

        </div>
        <!-- User Menu / End -->

        <!-- Mobile Navigation Button -->
        <span class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</span>

        </div>
        <!-- Right Side Content / End -->

        </div>
        </div>
        <!-- Header / End -->


        @elseif(Auth::user()->type == \App\User::INTERNAL)


    <!-- User Menu -->
        <div class="header-widget">

            <!-- Messages -->
            <div class="header-notifications user-menu">
                <div class="header-notifications-trigger">
                    <a href="#"><div class=""><em class="icon-feather-user"></em></div></a>
                </div>

                <!-- Dropdown -->
                <div class="header-notifications-dropdown">

                    <!-- User Status -->
                    <div class="user-status">

                        <!-- User Name / Avatar -->
                        <div class="user-details">
                            <div class="user-avatar status-online"><img src="landing3/images/avatar/icon-user-64.png" alt=""></div>
                            <div class="user-name">
                                {{Auth::user()->name}} <span class="blue">Subastas del Mar</span><span class="blue">Administrador</span>
                            </div>
                        </div>

                    </div>

                    <ul class="user-menu-small-nav">
                        <li><a href="{{url('/home')}}"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
                        <li><a href="/usuarios/editar/<?=Auth::user()->nickname?>"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
                        <li><a href="{{url('/users')}}"><em class="icon-line-awesome-list"></em> Listado de Usuarios</a></li>
                        <li><a href="{{url('/boats')}}"><em class="icon-line-awesome-ship"></em> Listado de Barcos</a></li>
                        <li><a href="{{url('/products')}}"><em class="icon-brand-product-hunt"></em> Productos</a></li>
                        <li><a href="{{url('auth/logout')}}"><em class="icon-material-outline-power-settings-new"></em> Logout</a></li>
                    </ul>

                </div>
            </div>

        </div>
        <!-- User Menu / End -->

        <!-- Mobile Navigation Button -->
        <span class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</span>

        </div>
        <!-- Right Side Content / End -->

        </div>
        </div>
        <!-- Header / End -->




        @endif
    @else

        <div class="header-widget">
            <div class=" user-menu">
                <div class="header-notifications-trigger">
                    <a href="/auth/login" class="option-login" rel="nofollow">
                        <img alt="image" width="20" height="20" src="/landing/img/header_usuario.png" /> Ingresá</a>
                </div>
            </div>

        </div>

        <!-- Mobile Navigation Button -->
        <span class="mmenu-trigger">
            <button class="hamburger hamburger--collapse" type="button">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </span>

    @endif

</header>
<div class="clearfix"></div>





