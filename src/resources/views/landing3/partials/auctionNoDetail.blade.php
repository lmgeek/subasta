<?php
use App\Auction;
use App\Constants;
use App\AuctionQuery;
$userId = $auction->batch->arrive->boat->user->id;
$total=$auction->amount;
$availability=Auction::getAvailable($auction->id,$total);
$disponible = $availability['available'];
$cantbids=$availability['sold'];
$cantofertas=\App\Http\Controllers\AuctionController::getOffersCount($auction->id);
$price=\App\Http\Controllers\AuctionController::calculatePriceID($auction->id);
$close=$price['close'];
$userRatings=\App\Http\Controllers\AuctionController::getUserRating($auction->batch->arrive->boat->user);
$usercat=AuctionQuery::catUserByAuctions($userId);
$port=\App\Ports::getPortById($auction->batch->arrive->port_id);
if(isset($request->time) and $request->time!= Constants::IN_CURSE){
    if($auction->timeline== Constants::FINISHED){
        $finished='&iexcl;Finalizada!';
    }elseif($auction->timeline== Constants::FUTURE){
        $finished='&iexcl;Proximamente!';
    }else{
        unset($finished);
    }
}
?>
<div id="Auction_<?=$auction->id?>" class="task-listing <?=(empty($finished))?'auction':''?>" data-id="{{$auction->id}}" data-price="{{$price['CurrentPrice']}}" data-end="{{$auction->end}}" data-endOrder="{{date('YmdHi',strtotime($auction->end))}}" data-close="{{$close}}"data-userrating="{{$userRatings}}"
<?='data-port="'.$auction->batch->arrive->port_id.'"
data-product="'.$auction->batch->product->id.'"
data-caliber="'.$auction->batch->caliber.'"
data-quality="'.(($auction->batch->quality<1)?1:$auction->batch->quality).'"
data-user="'.$auction->batch->arrive->boat->user->nickname.'"'?>>
<?php
setlocale(LC_TIME,'es_ES');
$fechafin=strftime('%d %b %Y', strtotime($auction->end));
?>
<!-- Auction Listing Details -->
<div class="task-listing-details" id="AuctionLeft<?=$auction->id?>">
        <!-- Photo -->
        @if(!isset($nopic))
            <div class="task-listing-photo">
                <img src="/img/products/{{$auction->batch->product->image_name}}" alt="{{$auction->batch->product->name}}">
            </div>
    @endif
    <!-- Details -->
        <div class="task-listing-description">
            <h3 class="task-listing-title">
                <a href="{{(empty($finished))?'/auction/details/'.$auction->id:'#'}}" id="LinkSubasta{{$auction->id}}">{{$auction->batch->product->name}} {{trans('auction.'.$auction->batch->caliber)}}</a>
                <div class="star-rating" data-rating="{{$auction->batch->quality}}"></div>
                @if($auction->type!='public')
                    <em class="t16 icon-feather-eye-off" data-tippy-placement="right" title="Subasta Privada" data-tippy-theme="dark"></em></h3>
            @endif
            </h3>


            <ul class="task-icons">
                @if(isset(Auth::user()->id) and Auth::user()->id==$auction->batch->arrive->boat->user_id)
                <li><em class="icon-material-outline-gavel <?=(empty($finished)?'primary':'')?>"></em> {{$auction->code}}</li>
                @endif
                <li><em class="icon-material-outline-access-time <?=(empty($finished)?'primary':'')?>" id="BlueClock{{$auction->id}}"></em><strong class="primary" id="FriendlyDate{{$auction->id}}">{{$fechafin}}</strong></li>
                <li><em class="icon-material-outline-location-on"></em> {{$port}}</li>
                <li style="display: none" id="HotAuction{{$auction->id}}"><em class="icon-line-awesome-fire red" ></em> <strong class="red">¡Subasta caliente!</strong></li>
            </ul>
            <p class="task-listing-text"> <?=(strlen($auction->description)>90)?substr($auction->description,0,90).'...':$auction->description?></p>
            <ul class="task-icons margin-top-20">

                <li>
                    <small>Vendedor</small><br>
                    <strong>
                        <em class="icon-feather-user"></em> {{$auction->batch->arrive->boat->user->nickname}}
                    </strong><br>
                    <div class="medal-rating {{strtolower($usercat)}}" data-rating="{{$usercat}}"><span class="medal {{$usercat}}-text"></span></div>
                </li>
                <li><small>Barco</small><br><strong><em class="icon-line-awesome-ship"></em> {{$auction->batch->arrive->boat->nickname}}</strong><br>
                    <div class="star-rating" data-rating="<?=$userRatings?>"></div></li>
            </ul>
            @if(isset(Auth::user()->id) && $userId==Auth::user()->id)
            <div class="bd-tp-1">
                <?php if($auction->timeline==Constants::FINISHED){?>
                <a href="/auction/offers/<?=$auction->id?>" class="button ripple-effect" data-tippy-placement="top" data-tippy="" title="Ver Ofertas Directas"><i class="icon-material-outline-local-offer"></i> <?=($cantofertas>0)?('<span class="button-info">'.$cantofertas.'</span>'):''?></a>
                <?php }?>
                <a href="/auction/operations/<?=$auction->id?>" class="button ripple-effect ico" title="Ver Ventas" data-tippy-placement="top"><i class="icon-feather-dollar-sign"></i> <?=($cantbids>0)?('<span class="button-info">'.$cantbids.'</span>'):''?></a>
                <?php if($auction->timeline==Constants::FUTURE){?>
                <a href="/auction/edit/<?=$auction->id?>" class="button ripple-effect ico" title="Editar" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                <?php }?>
                <a href="#" class="button  ripple-effect ico" title="Pausar" data-tippy-placement="top"><i class="icon-feather-pause"></i></a>
                <a href="/auction/replicate/<?=$auction->id?>" class="button ripple-effect ico" data-tippy-placement="top" data-tippy="" title="Replicar"><i class="icon-material-outline-filter-none"></i></a>
                <?php if(($cantbids+$cantofertas)==0){?>
                <a href="#" class="button dark ripple-effect ico" title="Eliminar" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                <?php }?>
                <a href="/auction/export/{{$auction->id}}" class="button dark ripple-effect ico" title="Exportar" data-tippy-placement="top"><i class="icon-material-outline-save-alt"></i></a>
            </div>
            @endif
        </div>
    </div>
    <div class="task-listing-bid">
        <div class="task-listing-bid-inner">
            <div class="task-offers">
                <p> <div id="auctionAvailability{{$auction->id}}" style="display: inline-block!important;font-weight: bold"><small style="font-weight: 400">Disponibilidad:</small> {{$disponible}} <small>de</small> {{$total}} {{$auction->batch->product->unit}}</div> <br>
                @if(empty($finished))
                        <small class="green fw700" id="BidsCounter{{$auction->id}}">
                            <?=(($cantbids>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantbids.(($cantbids>1)?' Compras Directas':' Compra Directa')):'')?>
                        </small>
                    @endif
                    </p>

                    <div class="pricing-plan-label billed-monthly-label <?=(empty($finished)?'red':'')?>" id="PriceContainer{{$auction->id}}"><strong class="red" id="Price{{$auction->id}}">${{$price['CurrentPrice']}}</strong>/ Kg<br>
                        <small class="red fw500" id="ClosePrice{{$auction->id}}"<?=(empty($finished) || $finished!='&iexcl;Finalizada!')?'style="display:none"':''?>><?=(empty($finished))?'&iexcl;Cerca del precio l&iacute;mite!':'Precio Final'?></small></div>
                    <div id="timer<?=$auction->id?>" class="countdown <?=(isset($finished) && $finished=='&iexcl;Proximamente!')?'green ':''?>margin-bottom-0 margin-top-20 blink_me <?=(empty($finished))?'timerauction':''?>" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}">
                        <?=(isset($finished))?$finished:''?></div>
            </div>
            @if(empty($finished))
                <div  id="OpenerPopUpCompra{{$auction->id}}">
                    <div class="w100">
                    <?php
                    if(Auth::user()){
                        $userses=Auth::user();//$userses->usersession
                        if($userses->status!="approved"){?>
                        <a href="#" class="button" onclick="notifications(0,null,null,null,'Usuario no aprobado')">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="#" onclick="notifications(0,null,null,null,'Usuario no aprobado')">realizar una oferta</a></div>
                        <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?' Ofertas Directas':' Oferta Directa')):'')?>
                        </small></div>
                        <input type="hidden" value="{{$auction->batch->product->unit}}" id="UnitAuction{{$auction->id}}">
                        <?php }elseif($userses->status=="approved" && $userses->type!=\App\User::COMPRADOR){?>
                        <a href="#" class="button" onclick="notifications(0,null,null,null,'El tipo de usuario no permite comprar')">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="#" onclick="notifications(0,null,null,null,'El tipo de usuario no permite ofertar')">realizar una oferta</a></div>
                        <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?' Ofertas Directas':' Oferta Directa')):'')?>
                        </small></div>
                        <input type="hidden" value="{{$auction->batch->product->unit}}" id="UnitAuction{{$auction->id}}">
                        <?php }else{?>
                        <a href="#small-dialog-compra-{{$auction->id}}" onclick="openPopupCompra({{$auction->id}})"  class="button ripple-effect popup-with-zoom-anim w100">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="#small-dialog-oferta{{$auction->id}}" onclick="openPopupOferta({{$auction->id}})" class="sign-in popup-with-zoom-anim">realizar una oferta</a></div>
                        <div class="text-center">
                            <small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?' Ofertas Directas':' Oferta Directa')):'')?>
                        </small></div>
                        @include('landing3/partials/pop-up-compra')
                        @include('landing3/partials/pop-up-oferta')
                        <?php }
                    }else{ ?>
                        <a href="/auction" class="button">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="/auction">realizar una oferta</a></div>
                        <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                                <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?' Ofertas Directas':' Oferta Directa')):'')?>
                            </small></div>
                        <input type="hidden" value="{{$auction->batch->product->unit}}" id="UnitAuction{{$auction->id}}">
                    <?php
                    }
                    ?>
                    </div>

                </div>

            @endif
        </div>
    </div>
</div>
