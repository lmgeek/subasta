<!-- Hacer oferta
================================================== -->
<div id="small-dialog-oferta{{$auction->id}}" class="small-dialog zoom-anim-dialog mfp-hide dialog-with-tabs">

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
                    <h3>&iexcl;Est&aacute;s por realizar una oferta!</h3>
                    <p class="padding-top-25">El realizar una oferta no garantiza que el producto vaya a ser tuyo. Una vez finalizada la subasta, si a&uacute;n hay stock disponible, el vendedor decidir&aacute; si acepta las ofertas directas.</p>
                    <p>&iquest;No quieres esperar? <a href="#small-dialog-compra-{{$auction->id}}" class="popup-with-zoom-anim">Realiza tu compra ahora</a>.</p>
                </div>

                <!-- Form -->
                <form method="post" id="oferta-form-{{$auction->id}}">

                    <div class="row margin-bottom-25">
                        <div class="col-md-12 bidding-widget">
                            <!-- Headline -->

                            <div class="input-with-icon-left col-md-6 offset-md-3">
                                <em class="currency">AR$</em>
                                <input class="with-border margin-bottom-5" type="text" placeholder="Precio por kilo" id="OfferPrice{{$auction->id}}" onkeydown="avoidSending()" value="{{$price['CurrentPrice']}}">
                            </div>
                            <span class="bidding-detail text-center red"><em class="icon-material-outline-info red"></em> La oferta aplica sobre todo el lote o el remanente.</span>
                        </div>
                    </div>

                </form>

                <!-- Button -->
                <button class="button margin-top-25 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form" onclick="makeOffer({{$auction->id}})">Hacer Oferta <em class="icon-material-outline-arrow-right-alt"></em></button>

            </div>

        </div>
    </div>
</div>
<!-- Hacer oferta popup / End -->
