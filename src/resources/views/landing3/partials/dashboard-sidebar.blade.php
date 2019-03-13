<div class="dashboard-sidebar">
		<div class="dashboard-sidebar-inner" data-simplebar>
			<div class="dashboard-nav-container">

				<!-- Responsive Navigation Trigger -->
				<a href="#" class="dashboard-responsive-nav-trigger">
					<span class="hamburger hamburger--collapse" >
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</span>
					<span class="trigger-title">Dashboard</span>
				</a>

				<!-- Navigation -->
				<div class="dashboard-nav">
					<div class="dashboard-nav-inner">

						<ul>
							<li><a href="#"><em class="icon-material-outline-dashboard"></em> Dashboard</a></li>
							<li><a href="#"><em class="icon-material-outline-history"></em> Mi Historial</a></li>
                            @if(isset(Auth::user()->id) && Auth::user()->type==\App\Constants::VENDEDOR)
                            <li><a href="/subastas?type=mine&time=all"><em class="icon-material-outline-gavel"></em> Mis Subastas</a></li>
                            <li><a href="/barcos"><em class="icon-line-awesome-ship"></em> Mis Barcos</a></li>
                            @endif
                            @if(isset(Auth::user()->id) && Auth::user()->type==\App\Constants::COMPRADOR)
                            <li><a href="/compra"><em class="icon-feather-shopping-bag"></em> Mis Compras</a></li>
                            @endif
						</ul>
						<ul data-submenu-title="Mi Cuenta">
							<li><a href="#"><i class="icon-material-outline-settings"></i> Ajustes</a></li>
							<li><a href="/auth/logout"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>
						</ul>

					</div>
				</div>
				<!-- Navigation / End -->

			</div>
		</div>
	</div>
