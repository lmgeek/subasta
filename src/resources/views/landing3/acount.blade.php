<?php
use Illuminate\Support\Facades\Auth;


?>



@extends('landing3/partials/layout-admin')
@section('title', '| Lista de Barcos')
@section('content')

	<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container" data-simplebar>
		<div class="dashboard-content-inner" >
			
			<!-- Dashboard Headline -->
			<div class="dashboard-headline">
				<h3>Ajustes</h3>
			</div>
	
			<!-- Row -->
			<div class="row">

				<!-- Dashboard Box -->
				<div class="col-xl-12">
					<div class="dashboard-box margin-top-0">

						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-material-outline-account-circle"></i> Mi Cuenta</h3>
						</div>

						<div class="content with-padding padding-bottom-0">

							<div class="row">

								<div class="col-auto">
									<div class="avatar-wrapper" data-tippy-placement="bottom" title="Cambiar imagen">
										<img class="profile-pic" src="images/user-avatar-placeholder.png" alt="" />
										<div class="upload-button"></div>
										<input class="file-upload" type="file" accept="image/*"/>
									</div>
								</div>

								<div class="col">
									<div class="row">

										<div class="col-xl-6">
											<div class="submit-field">
												<h5>Nombre y Apellido <i class="help-icon" data-tippy-placement="right" title="Tranquilo. Nadie verá este dato"></i></h5>
												<input type="text" class="with-border" placeholder="Ingresa tu nombre completo" value="{{$user[0]->name." " }}{{$user[0]->lastname}}">
											</div>
										</div>

										<div class="col-xl-6">
											<div class="submit-field">
												<h5>Usuario <i class="help-icon" data-tippy-placement="right" title="Nombre con el que usarás el sitio"></i></h5>
												<input type="text" class="with-border" placeholder="Ingresa tu usuario" value="{{$user[0]->nickname}}">
											</div>
										</div>

										<div class="col-xl-6">
											<!-- Account Type -->
											<div class="submit-field">
												<h5>Tipo de Cuenta</h5>
												<div class="account-type">
													<div>
														<input type="radio" name="account-type-radio" id="freelancer-radio" class="account-type-radio" checked/>
														<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-gavel"></i> Vendedor</label>
													</div>

													<div>
														<input type="radio" name="account-type-radio" id="employer-radio" class="account-type-radio"/>
														<label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-shopping-cart"></i> Comprador</label>
													</div>
												</div>
											</div>
										</div>

										<div class="col-xl-6">
											<div class="submit-field">
												<h5>Email</h5>
												<input type="text" class="with-border" value="{{$user[0]->email}}" disabled >
											</div>
										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="col-xl-12">
					<div class="dashboard-box">

						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-feather-home"></i> Locación</h3>
						</div>

						<div class="content with-padding padding-bottom-0">

							<div class="row">

								<div class="col">
									<div class="row">

										<div class="col-xl-9">
											<div class="submit-field">
												<h5>Domicilio</h5>
												<input type="text" class="with-border">
											</div>
										</div>

										<div class="col-xl-3">
											<div class="submit-field">
												<h5>Código Postal</h5>
												<input type="text" class="with-border">
											</div>
										</div>

										<div class="col-xl-4">
											<div class="submit-field">
												<h5>Localidad</h5>
												<input type="text" class="with-border">
											</div>
										</div>

										<div class="col-xl-4">
											<div class="submit-field">
												<h5>Provincia</h5>
												<input type="text" class="with-border">
											</div>
										</div>

										<div class="col-xl-4">
											<div class="submit-field">
												<h5>País</h5>
												<input type="text" class="with-border">
											</div>
										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<!-- Dashboard Box -->
				<div class="col-xl-12">
					<div id="test1" class="dashboard-box">

						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-material-outline-lock"></i> Contrase&ntilde;a & Seguridad</h3>
						</div>

						<div class="content with-padding">
							<div class="row">
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Contrase&ntilde;a Actual</h5>
										<input type="password" class="with-border">
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Contrase&ntilde;a Nueva</h5>
										<input type="password" class="with-border">
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Repite la Contrase&ntilde;a</h5>
										<input type="password" class="with-border">
									</div>
								</div>

								<div class="col-xl-12">
									<div class="checkbox">
										<input type="checkbox" id="two-step" checked >
										<label for="two-step"><span class="checkbox-icon"></span> Activar <i>Two-Step Verification</i> via Email</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Button -->
				<div class="col-xl-12 text-right">
					<a href="#" class="button ripple-effect big margin-top-30">Guardar</a>
				</div>

			</div>

		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->

</div>
@endsection