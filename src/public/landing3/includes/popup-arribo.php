<!-- Arribo Popup
================================================== -->
<div id="small-dialog-arribo" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Subastas del Mar</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
					<!-- Welcome Text -->
					<div class="welcome-text">
						<h2 class="fw700 text-left t32 lsp-1">Nuevo Arribo</h2>
					</div>
				
				<form method="post" id="nuevo-arribo-form">
						
					<!-- Bidding -->
					<div class="bidding-widget">
						<!-- Headline -->
						<span class="bidding-detail">&iquest;De qu&eacute; <strong>barco</strong> se trata?</span>

						<!-- Fields -->
						<div class="bidding-fields w100">
							<select class="selectpicker with-border" title="Selecciona...">
								<option>Barco I</option>
								<option>Barco II</option>
								<option>Barco III</option>
								<option>Barco IV</option>
								<option>Barco V</option>
							</select>
						</div>
						
						<!-- Headline -->
						<span class="bidding-detail margin-top-30">Define la <strong>fecha de arribo</strong></span>

						<!-- Fields -->
						<div class="bidding-fields w100">
							<input type="text" data-field="datetime" class="with-border" placeholder="Selecciona..." readonly>
							<div id="dtBox"></div>
						</div>
				</div>
				
				<div class="bidding-widget margin-top-0">
					<div class="bidding-fields bd-tp-1">
						<div class="bidding-field margin-bottom-0">
									<!-- Quantity Buttons -->
							<button class="button ripple-effect big" type="submit">Guardar</button>
						</div>
						<div class="bidding-field">
							<button class="button dark ripple-effect big" type="submit">Cancelar</button>
						</div>
					</div>
				</div>

			</form>

		</div>
	</div>
</div>
</div>
<!-- Arribo Popup / End -->
