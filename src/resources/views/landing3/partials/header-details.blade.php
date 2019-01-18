

<header id="header-container" class="fullwidth">

	<!-- Header -->
	<div id="header">
		<div class="container">

			<!-- Left Side Content -->
			<div class="left-side">

				<!-- Logo -->
				<div id="logo">
					<a href="{{url('/')}}"><img src="/landing3/images/logo2.png" data-sticky-logo="/landing3/images/logo.png" data-transparent-logo="/landing3/images/logo2.png" alt="Subastas del Mar"></a>
				</div>

				<!-- Main Navigation -->
				<nav id="navigation">
					<ul id="responsive">

						<li><a href="{{url('/')}}" class="current simple">Home</a></li>

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
			@if (Auth::check())
				@if(Auth::user()->type == \App\User::VENDEDOR)
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

				@endif
			@endif


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