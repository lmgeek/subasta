<!-- Sign In Popup
================================================== -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#login">Acceder a mi cuenta</a></li>
			<li><a href="#register">Registro</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Login -->
			<div class="popup-tab-content" id="login">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>&iexcl;Hola! Nos alegra verte de nuevo</h3>
					<span>&iquest;A&uacute;n no tienes cuenta? <a href="#" class="register-tab">&iexcl;Crea una ahora!</a></span>
				</div>
					
				<!-- Form -->
				<form method="post" id="login-form">
					<div class="input-with-icon-left">
						<i class="icon-material-outline-account-circle"></i>
						<input type="text" class="input-text with-border" name="emailaddress" id="emailaddress" placeholder="Email o Nombre de Usuario" required/>
					</div>

					<div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border" name="password" id="password" placeholder="Contraseña" required/>
					</div>
					<a href="#" class="forgot-password">&iquest;Olvidaste tu Contrase&ntilde;a?</a>
				</form>
				
				<!-- Button -->
				<button class="button full-width button-sliding-icon ripple-effect" type="submit" form="login-form">Ingresar <i class="icon-material-outline-arrow-right-alt"></i></button>
				
			</div>

			<!-- Register -->
			<div class="popup-tab-content" id="register">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>&iexcl;Bienvenido! Vamos a crear tu cuenta</h3>
				</div>
				
				<!-- Form -->
				<form method="post" id="register-account-form">
				<!-- Account Type -->
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
					
					<div class="input-with-icon-left">
						<i class="icon-material-outline-account-circle"></i>
						<input type="text" class="input-text with-border" name="username" id="username" placeholder="Nombre de Usuario" required/>
					</div>
					<div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" class="input-text with-border" name="emailaddress-register" id="emailaddress-register" placeholder="Email" required/>
					</div>

					<div class="input-with-icon-left" title="Debe tener al menos 8 caracateres" data-tippy-placement="bottom">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border" name="password-register" id="password-register" placeholder="Contraseña" required/>
					</div>

					<div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border" name="password-repeat-register" id="password-repeat-register" placeholder="Repetir Contraseña" required/>
					</div>
				</form>
				
				<!-- Button -->
				<button class="margin-top-10 button full-width button-sliding-icon ripple-effect" type="submit" form="register-account-form">Crear Cuenta <i class="icon-material-outline-arrow-right-alt"></i></button>
				
				<!-- Social Login -->
				<div class="social-login-separator"><span>o</span></div>
				<div class="social-login-buttons">
					<button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Registrarme con Facebook</button>
					<button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Registrarme con Google+</button>
				</div>

			</div>

		</div>
	</div>
</div>
<!-- Sign In Popup / End -->
