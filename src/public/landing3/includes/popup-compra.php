<!-- Hacer oferta
================================================== -->
<div id="small-dialog-compra" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

	<!--Tabs -->
	<div class="sign-in-form bid-offer">

		<ul class="popup-tabs-nav">
			<li><a href="#tab">S&oacute;lo un paso m&aacute;s y es tuyo...</a></li>
		</ul>

		<div class="popup-tabs-container">

			<!-- Tab -->
			<div class="popup-tab-content" id="tab">
				
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>&iexcl;Aprovecha y ll&eacute;vatelo ahora!</h3>
					<p class="padding-top-25">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
				
				<!-- Form -->
				<form method="post" id="compra-form">
					
				<div class="row margin-bottom-15">
				<div class="col-md-5 bidding-widget">
					<!-- Headline -->
					<span class="bidding-detail">Disponibles <strong>10</strong> de <strong>400</strong> kg</span>
					<div class="margin-top-15">
						<h4 class="price red">$210 <small>x kg</small></h4>
						<small class="red">&Uacute;ltimo precio registrado</small>
					</div>
				</div>
				<div class="col-md-7 bidding-widget">
					<!-- Headline -->
					<span class="bidding-detail">Por favor, <strong>haz tu pedido</strong>:</span>
					<div class="bidding-fields margin-top-7">
						<div class="bidding-field">
							<!-- Quantity Buttons -->
							<div class="qtyButtons">
								<div class="qtyDec"></div>
								<input type="text" name="qtyInput" value="1" id="cantidad">
								<div class="qtyInc"></div>
							</div>
						</div>
						<div class="bidding-field">
							<input type="text" class="with-border" value="Kg" disabled>
						</div>
					</div>

				</div>
				</div>
				<div class="bd-tp-1 padding-top-10 text-center">
					<div class="checkbox">
						<input type="checkbox" id="chekcbox1" onclick="enable_text(this.checked)">
						<label for="chekcbox1"><span class="checkbox-icon"></span> Adquirir todo el lote</label>
					</div>
				</div>

				</form>
				
				<!-- Button -->
				<button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form">Comprar <i class="icon-material-outline-arrow-right-alt"></i></button>

			</div>

		</div>
	</div>
</div>
<!-- Hacer oferta popup / End -->
	<script>
		function enable_text(status)
			{
			//alert(status);
			document.getElementById("cantidad").disabled = status;
			}
	</script>
