

<header id="header-container" class="fullwidth transparent-header">

    <!-- Header -->
    <div id="header">
        <div class="container">

            <!-- Left Side Content -->
            <div class="left-side">

                <!-- Logo -->
                <div id="logo">
                    <a href="index.php"><img src="/landing3/images/logo2.png" data-sticky-logo="/landing3/images/logo.png" data-transparent-logo="/landing3/images/logo2.png" alt="Subastas del Mar"></a>
                </div>

                <!-- Main Navigation -->
                <nav id="navigation">
                    <ul id="responsive">

                        <li><a href="index.php" class="current simple">Home</a></li>

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

                        <li><a href="#">Subastas</a>
                            <ul class="dropdown-nav">
                                <li><a href="subastas-list.php">Todas</a></li>
                                <li><a href="subastas-list.php">Finalizando</a></li>
                                <li><a href="subastas-list.php">Pr&oacute;ximas</a></li>
                                <li><a href="subastas-list.php">Finalizadas</a></li>
                                <li><a href="subastas-list.php">Privadas</a></li>
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


            <!-- Right Side Content / End -->
            <div class="right-side">

                <!--  Actions -->
                <div class="header-widget hide-on-mobile">

                    <!-- Nueva Subasta -->
                    <div class="header-notifications">

                        <!-- Trigger -->
                        <div class="header-notifications-link">
                            <a href="dash-nueva-subasta.php" data-tippy-placement="left" title="Nueva Subasta" data-tippy-theme="dark"><i class="icon-material-outline-gavel"></i></a>
                        </div>


                    </div>

                </div>
                <!--  Actions / End -->

                <!--  User Notifications -->
            {{--<div class="header-widget hide-on-mobile">

                <!-- Notifications -->
                <div class="header-notifications">

                    <!-- Trigger -->
                    <div class="header-notifications-trigger">
                        <a href="#" data-tippy-placement="left" title="Notificaciones" data-tippy-theme="dark"><i class="icon-feather-bell"></i><span>4</span></a>
                    </div>

                    <!-- Dropdown -->
                    <div class="header-notifications-dropdown">

                        <div class="header-notifications-headline">
                            <h4>Notificaciones</h4>
                            <button class="mark-as-read ripple-effect-dark" title="Marcar todas como leídas" data-tippy-placement="left">
                                <i class="icon-feather-check-square"></i>
                            </button>
                        </div>

                        <div class="header-notifications-content">
                            <div class="header-notifications-scroll" data-simplebar>
                                <ul>
                                    <!-- Notification -->
                                    <li class="notifications-not-read">
                                        <a href="#">
                                            <span class="notification-icon"><i class="icon-feather-clock"></i></span>
                                            <span class="notification-text">
                                                <strong>Lote de Camarones Premium</strong> est&aacute; por finalizar.<span class="color"> &iquest;Vas a dejar pasar esta oportunidad?</span>
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Notification -->
                                    <li>
                                        <a href="#">
                                            <span class="notification-icon"><i class="icon-feather-thumbs-up"></i></span>
                                            <span class="notification-text">
                                                <strong>&iexcl;Felicidades!</strong> Gustavo de Sancho hizo una oferta en tu subasta Pesca de alta mar.
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Notification -->
                                    <li>
                                        <a href="#">
                                            <span class="notification-icon"><i class="icon-line-awesome-fire"></i></span>
                                            <span class="notification-text">
                                                La subasta <strong>Cornalitos alta calidad</strong>  est&aacute; registrando muchas ofertas. <span class="color">&iexcl;Haz tu oferta ahora!</span>
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Notification -->
                                    <li>
                                        <a href="#">
                                            <span class="notification-icon"><i class="icon-feather-eye"></i></span>
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
                                    <a href="#"><div class="user-avatar"><img src="landing3/images/avatar/gear_icon.ico" alt="jlopez"></div></a>
                                </div>

                                <!-- Dropdown -->
                                <div class="header-notifications-dropdown">

                                    <!-- User Status -->
                                    <div class="user-status">

                                        <!-- User Name / Avatar -->
                                        <div class="user-details">
                                            <div class="user-avatar status-online"><img src="landing3/images/avatar/gear_icon.ico" alt=""></div>
                                            <div class="user-name">
                                                Julio L&oacute;pez <span class="blue">Netlabs</span><span class="blue">Vendedor</span>
                                            </div>
                                        </div>

                                    </div>

                                    <ul class="user-menu-small-nav">
                                        <li><a href="{{url('/home')}}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                                        <li><a href="#"><i class="icon-feather-user"></i> Mi Cuenta</a></li>
                                        <li><a href="{{url('/sellerboat')}}"><i class="icon-line-awesome-ship"></i> Mis Barcos</a></li>
                                        <li><a href="{{url('/sellerbatch')}}"><i class="icon-feather-box"></i> Mis Lotes</a></li>
                                        <li><a href="{{url('/sellerAuction')}}"><i class="icon-material-outline-gavel"></i>Mis Subastas</a></li>
                                        <li><a href="{{url('/sales')}}"><i class="icon-material-outline-gavel"></i> Ventas de Subastas</a></li>
                                        <li><a href="{{ url('/privatesales') }}"><i class="icon-feather-eye-off"></i> Ventas privadas</a></li>
                                        <li><a href="{{url('auth/logout')}}"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>
                                        {{--<li><a href="dash-list-arribos.php"><i class="icon-material-outline-access-time"></i> Mis Arribos</a></li>--}}
                                        {{--<li><a href="#"><i class="icon-material-outline-shopping-cart"></i> Mis Compras</a></li>--}}
                                        {{--<li><a href="#"><i class="icon-feather-heart"></i> Favoritas</a></li>--}}
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
                    <a href="#"><div class="user-avatar"><img src="landing3/images/avatar/gear_icon.ico" alt="jlopez"></div></a>
                </div>

                <!-- Dropdown -->
                <div class="header-notifications-dropdown">

                    <!-- User Status -->
                    <div class="user-status">

                        <!-- User Name / Avatar -->
                        <div class="user-details">
                            <div class="user-avatar status-online"><img src="landing3/images/avatar/gear_icon.ico" alt=""></div>
                            <div class="user-name">
                                Julio L&oacute;pez <span class="blue">Netlabs</span><span class="blue">Comprador</span>
                            </div>
                        </div>

                    </div>

                    <ul class="user-menu-small-nav">
                        <li><a href="{{url('/home')}}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                        <li><a href="#"><i class="icon-feather-user"></i> Mi Cuenta</a></li>
                        <li><a href="{{url('/auction')}}"><i class="icon-material-outline-gavel"></i> Subastas</a></li>
                        <li><a href="{{url('/auction?type=private')}}"><i class="icon-feather-eye-off"></i> Subastas Privadas</a></li>
                        <li><a href="{{url('/bids')}}"><i class="icon-material-outline-shopping-cart"></i> Compra</a></li>
                        <li><a href="{{url('auth/logout')}}"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>
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
                    <a href="#"><div class="user-avatar"><img src="landing3/images/avatar/gear_icon.ico" alt="jlopez"></div></a>
                </div>

                <!-- Dropdown -->
                <div class="header-notifications-dropdown">

                    <!-- User Status -->
                    <div class="user-status">

                        <!-- User Name / Avatar -->
                        <div class="user-details">
                            <div class="user-avatar status-online"><img src="landing3/images/avatar/gear_icon.ico" alt=""></div>
                            <div class="user-name">
                                Julio L&oacute;pez <span class="blue">Netlabs</span><span class="blue">Administrador</span>
                            </div>
                        </div>

                    </div>

                    <ul class="user-menu-small-nav">
                        <li><a href="{{url('/home')}}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                        <li><a href="#"><i class="icon-feather-user"></i> Mi Cuenta</a></li>
                        <li><a href="{{url('/users')}}"><i class="icon-line-awesome-list"></i> Listado de Usuarios</a></li>
                        <li><a href="{{url('/boats')}}"><i class="icon-line-awesome-ship"></i> Listado de Barcos</a></li>
                        {{--<li><a href="{{url('/products')}}"><i class="icon-line-awesome-sellsy"></i>Productos</a></li>--}}
                        <li><a href="{{url('/products')}}"><i class="icon-brand-product-hunt"></i> Productos</a></li>
                        <li><a href="{{url('auth/logout')}}"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>
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
                    <a href="{{ url('auth/login') }}" class="option-login" rel="nofollow">
                        <img alt="image" width="20" height="20" src="{{ asset('/landing/img/header_usuario.png') }}" /> Ingresá</a>
                </div>
            </div>
        </div>

    @endif

</header>
<div class="clearfix"></div>





