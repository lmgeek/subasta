

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
									<a href="dash-nueva-subasta.php" data-tippy-placement="left" title="Nueva Subasta" data-tippy-theme="dark"><em class="icon-material-outline-gavel"></em></a>
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
										<li><a href="{{url('/home')}}"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
										<li><a href="#"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
										<li><a href="{{url('/sellerboat')}}"><em class="icon-line-awesome-ship"></em> Mis Barcos</a></li>
										<li><a href="{{url('/sellerbatch')}}"><em class="icon-feather-box"></em> Mis Lotes</a></li>
										<li><a href="{{url('/sellerAuction')}}"><em class="icon-material-outline-gavel"></em>Mis Subastas</a></li>
										<li><a href="{{url('/sales')}}"><em class="icon-material-outline-gavel"></em> Ventas de Subastas</a></li>
										<li><a href="{{ url('/privatesales') }}"><em class="icon-feather-eye-off"></em> Ventas privadas</a></li>
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
						<li><a href="{{url('/home')}}"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
						<li><a href="#"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
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
						<li><a href="{{url('/home')}}"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
						<li><a href="#"><em class="icon-feather-user"></em> Mi Cuenta</a></li>
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
					<a href="{{ url('auth/login') }}" class="option-login" rel="nofollow">
						<img alt="image" width="20" height="20" src="/landing/img/header_usuario.png" /> Ingresá</a>
				</div>
			</div>
		</div>

	@endif

</header>
<div class="clearfix"></div>