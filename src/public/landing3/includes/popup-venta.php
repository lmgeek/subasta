<!-- Hacer oferta
================================================== -->
<div id="small-dialog-venta" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form bid-offer">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">Subastas del Mar</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h2 class="fw700 text-left t32 lsp-1">Venta Privada</h2>
				</div>
				
				<!-- Form -->
				<form method="post" id="compra-form">
					
					<div class="row margin-bottom-15">
						<div class="col-md-12 bidding-widget">
							<!-- Headline -->
							<span class="bidding-detail"><strong>Raz&oacute;n Social</strong> del comprador</span>
								<div class="bidding-fields margin-top-7 w100">
									<input class="with-border" placeholder="Ingresa el nombre">
								</div>
						</div>
					</div>
					
					<div class="row margin-bottom-10">

						<div class="col-md-5 bidding-widget">
							<!-- Headline -->
							<span class="bidding-detail">Precio por <strong>unidad</strong> (kg)</span>
							<div class="input-with-icon-left  margin-top-7">
									<i class="currency">AR$</i>
									<input class="with-border margin-bottom-5" type="text" placeholder="Precio">
								</div>
						</div>
						<div class="col-md-7 bidding-widget">
							<!-- Headline -->
							<span class="bidding-detail">&iquest;Cu&aacute;ntas <strong>unidades</strong> se venden?</span>
							<div class="bidding-fields margin-top-7">
								<div class="bidding-field">
									<!-- Quantity Buttons -->
									<div class="qtyButtons with-border">
										<div class="qtyDec"></div>
										<input type="text" name="qtyInput" value="1">
										<div class="qtyInc"></div>
									</div>
								</div>
								<div class="bidding-field">
									<input type="text" class="with-border" value="Kg" disabled>
								</div>
							</div>

						</div>
					</div>
					
					<div class="bidding-widget margin-top-30">
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
<!-- Hacer oferta popup / End -->
