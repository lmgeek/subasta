<header id="header-container" class="fullwidth  <?=(isset($outsidehome) || isset($layoutadmin))?'dashboard-header no-sticky':'transparent-header'?>">
    <div id="header">
        <div class="container">
            <div class="left-side">
                <div id="logo">
                    <a href="/"><img src="/landing3/images/logo2.png" data-sticky-logo="/landing3/images/logo.png" data-transparent-logo="/landing3/images/logo2.png" alt="Subastas del Mar"></a>
                </div>
                <nav id="navigation">
                    <ul id="responsive">
                        <li><a href="/" class="current simple">Home</a></li>
                        <li><a href="/subastas">Subastas</a>
                            <ul class="dropdown-nav">
                                <li><a href="/subastas?time=all">Todas</a></li>
                                <li><a href="/subastas?time=incourse">Finalizando</a></li>
                                <li><a href="/subastas?time=future">Pr&oacute;ximas</a></li>
                                <li><a href="/subastas?time=finished">Finalizadas</a></li>
                                @if(isset(Auth::user()->type))
                                <li><a href="#">Privadas</a></li>
                                @else
                                <li><a href="#">Privadas</a></li>
                                @endif
                            </ul>
                        </li>
                        <li><a href="#" class="simple">Historial</a></li>
                        <li><a href="#" class="simple">Ayuda</a></li>
                    </ul>
                </nav>
                <div class="clearfix"></div>
            </div>
            <div class="right-side">
@if (Auth::check())
    @if(Auth::user()->type == \App\User::VENDEDOR)
                <div class="header-widget hide-on-mobile">
                    <div class="header-notifications">
                        <div class="header-notifications-link">
                            <a href="/subastas/agregar" data-tippy-placement="left" title="Nueva Subasta" data-tippy-theme="dark"><em class="icon-material-outline-gavel"></em></a>
                        </div>
                    </div>
                </div>
    @endif
                <div class="header-widget">
                    <div class="header-notifications user-menu">
                        <div class="header-notifications-trigger">
                            <a href="#"><div class=""><em class="icon-feather-user"></em></div></a>
                        </div>
                        <div class="header-notifications-dropdown">
                            <div class="user-status">
                                <div class="user-details">
                                    <div class="user-avatar status-online"><img src="landing3/images/avatar/icon-user-64.png" alt=""></div>
                                    <div class="user-name">
                                        {{Auth::user()->name}} <span class="blue">Subastas del Mar</span><span class="blue"><?=trans('general.users_type.'.Auth::user()->type)?></span>
                                    </div>
                                </div>
                            </div>
                            <ul class="user-menu-small-nav">
                                <li><a href="#"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
                                <li><a href="#"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
                                @if(Auth::user()->type==\App\User::VENDEDOR)
                                <li><a href="/barcos"><em class="icon-line-awesome-ship"></em> Mis Barcos</a></li>
                                <li><a href="#"><em class="icon-feather-box"></em> Mis Lotes</a></li>
                                <li><a href="/subastas?time=all&type=mine"><em class="icon-material-outline-gavel"></em> Mis Subastas</a></li>
                                <li><a href="#"><em class="icon-material-outline-gavel"></em> Ventas de Subastas</a></li>
                                <li><a href="#"><em class="icon-feather-eye-off"></em> Ventas privadas</a></li>
                                @elseif(Auth::user()->type==\App\User::COMPRADOR)
                                <li><a href="/subastas"><em class="icon-material-outline-gavel"></em> Subastas</a></li>
                                <li><a href="#"><em class="icon-feather-eye-off"></em> Subastas Privadas</a></li>
                                <li><a href="{{url('/bids')}}"><em class="icon-material-outline-shopping-cart"></em> Compra</a></li>
                                @elseif(Auth::user()->type==\App\User::INTERNAL)
                                <li><a href="{{url('/users')}}"><em class="icon-line-awesome-list"></em> Listado de Usuarios</a></li>
                                <li><a href="{{url('/boats')}}"><em class="icon-line-awesome-ship"></em> Listado de Barcos</a></li>
                                <li><a href="{{url('/products')}}"><em class="icon-brand-product-hunt"></em> Productos</a></li>
                                @endif
                                <li><a href="{{url('auth/logout')}}"><em class="icon-material-outline-power-settings-new"></em> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
@else
                <div class="header-widget">
                    <div class=" user-menu">
                        <div class="header-notifications-trigger">
                            <a href="/auth/login" class="option-login" rel="nofollow">
                                <em class="icon-feather-user"></em> Ingresar
                            </a>
                        </div>
                    </div>
                </div>
@endif
                <span class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</span>
            </div>
        </div>
    </div>
</header>
<div class="clearfix"></div>





