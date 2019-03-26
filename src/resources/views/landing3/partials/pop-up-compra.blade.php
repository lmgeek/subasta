<div id="small-dialog-compra-{{$auction->id}}" class="small-dialog zoom-anim-dialog mfp-hide dialog-with-tabs ">
    <div class="sign-in-form bid-offer">
        <ul class="popup-tabs-nav">
            <li><a href="#tab">S&oacute;lo un paso m&aacute;s y es tuyo...</a></li>
        </ul>
        <div class="popup-tabs-container">
            <div class="popup-tab-content" id="tab">
                <div class="welcome-text">
                    <h3>&iexcl;Aprovecha y ll&eacute;vatelo ahora!</h3>
                    <p class="padding-top-25">{{(strlen($auction->description)>120)?(substr($auction->description,0,120).'...'):$auction->description}}</p>
                </div>
                <form method="post" id="compra-form{{$auction->id}}">
                    <div class="row margin-bottom-15">
                        <div class="col-md-5 bidding-widget">
                            <p> <div id="auctionAvailabilitypopup{{$auction->id}}" style="display: inline-block!important;font-weight: bold"><small style="font-weight: 400">Disponibilidad:</small> {{$disponible}} <small>de</small> {{$total}} {{$auction->product['presentation_unit']}}</div> <br>
                            <div class="margin-top-15">
                                <h4 class="price red" id="PricePopUp{{$auction->id}}">${{$price['CurrentPrice']}} <small>/ <?= \App\Constants::individualize($auction->product['sale_unit'])?></small></h4>
                                <small class="red">&Uacute;ltimo precio registrado</small>
                            </div>
                        </div>
                        <div class="col-md-7 bidding-widget">
                            <span class="bidding-detail">Por favor, <strong>haz tu pedido</strong>:</span>
                            <div class="bidding-fields margin-top-7">
                                <div class="bidding-field">
                                    <div class="qtyButtons">
                                        <div class="qtyDec" data-id="{{$auction->id}}" onmousedown="modifyNumber('cantidad-<?=$auction->id?>',-1,'checkbox<?=$auction->id?>')"></div>
                                        <input type="text" name="qtyInput" value="1" id="cantidad-{{$auction->id}}"min="0" max="{{$disponible}}" onkeydown="avoidSending()">
                                        <div class="qtyInc" data-id="{{$auction->id}}" onclick="modifyNumber('cantidad-<?=$auction->id?>',1,'checkbox<?=$auction->id?>')"></div>
                                    </div>
                                </div>
                                <div class="bidding-field">
                                    <input type="text" class="with-border" value="{{$auction->product['presentation_unit']}}" id="UnitAuction{{$auction->id}}" disabled>
                                    
                                    <input type="hidden" id="PriceBid{{$auction->id}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bd-tp-1 padding-top-10 text-center">
                        <div class="checkbox">
                            <input type="checkbox" id="checkbox{{$auction->id}}" onclick="popupCompraDisableText({{$auction->id}})">
                            <label for="checkbox{{$auction->id}}"><span class="checkbox-icon"></span> Adquirir todo el lote</label>
                        </div>
                    </div>
                </form>
                <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form" onclick="makeBid({{$auction->id}})">Comprar <em class="icon-material-outline-arrow-right-alt"></em></button>
            </div>

        </div>
    </div>
</div>