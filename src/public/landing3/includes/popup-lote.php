<!-- Edit Bid Popup
================================================== -->
<div id="small-dialog-lote" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

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
						<h2 class="fw700 text-left t32 lsp-1">Nuevo Lote</h2>
					</div>
					
					<form method="post" id="nuevo-lote-form">
						
					<!-- Bidding -->
					<div class="bidding-widget">
						<!-- Headline -->
						<span class="bidding-detail">&iquest;En qu&eacute; <strong>barco</strong> arrib&oacute; o arribar&aacute; el lote?</span>

						<!-- Producto -->
						<div class="bidding-fields w100">
							<select class="selectpicker with-border" title="Selecciona...">
								<option>Barco I (20 Ene 2019 23:00)</option>
								<option>Barco II (22 Ene 2019 14:00)</option>
								<option>Barco III (2 Feb 2019 03:00)</option>
								<option>Barco IV (3 Feb 2019 04:30)</option>
								<option>Barco V (1 Mar 2019 12:15)</option>
							</select>
						</div>
												
						<!-- Fields -->
						<div class="bidding-fields margin-top-0">
							<div class="bidding-field">
								<!-- Headline -->
								<span class="bidding-detail margin-top-30 margin-bottom-12">&iquest;De qu&eacute; <strong>producto</strong> se trata?</span>
								<!-- Quantity Buttons -->
								<select class="selectpicker with-border" title="Selecciona...">
									<option>Camar&oacute;n (Cajas)</option>
									<option>Langosta (Kg)</option>
									<option>Cornalito (Kg)</option>
									<option>Salm&oacute;n (Kg)</option>
									<option>R&oacute;balo (Kg)</option>
								</select>
							</div>
							<div class="bidding-field">
								<!-- Headline -->
								<span class="bidding-detail margin-top-30 margin-bottom-12">&iquest;De qu&eacute; <strong>calidad</strong> es?</span>
								<div class="leave-rating margin-top-10">
									<input type="radio" name="rating" id="rating-1" value="1" />
									<label for="rating-1" class="icon-material-outline-star"></label>
									<input type="radio" name="rating" id="rating-2" value="2"/>
									<label for="rating-2" class="icon-material-outline-star"></label>
									<input type="radio" name="rating" id="rating-3" value="3"/>
									<label for="rating-3" class="icon-material-outline-star"></label>
									<input type="radio" name="rating" id="rating-4" value="4"/>
									<label for="rating-4" class="icon-material-outline-star"></label>
									<input type="radio" name="rating" id="rating-5" value="5"/>
									<label for="rating-5" class="icon-material-outline-star"></label>
									</div>
								</div>
						
						<!-- Fields -->
						<div class="bidding-fields margin-top-0">
							<div class="bidding-field">
								<!-- Headline -->
								<span class="bidding-detail margin-top-30 margin-bottom-12">Define el <strong>calibre</strong></span>
								<!-- Quantity Buttons -->
								<select class="selectpicker default with-border" title="Selecciona...">
									<option>Chico/a</option>
									<option>Mediano/a</option>
									<option>Grande</option>
								</select>
							</div>
							<div class="bidding-field">
								<!-- Headline -->
								<span class="bidding-detail margin-top-30 margin-bottom-12">&iquest;Qu&eacute; <strong>cantidad</strong> dispones?</span>
								<div class="qtyButtons with-border">
									<div class="qtyDec"></div>
									<input type="text" name="qtyInput" value="0">
									<div class="qtyInc"></div>
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
				
			</div>
				</form>

		</div>
	</div>
</div>
<!-- Edit Bid Popup / End -->
