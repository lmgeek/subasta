<?php
use App\Auction;
use App\Constants;
use App\AuctionQuery;
$userId = $auction->batch->arrive->boat->user->id;
$total=$auction->amount;
$availability=Auction::getAvailable($auction->id,$total);
$disponible = $availability['available'];
$cantbids=$availability['sold'];
$cantofertas=\App\Http\Controllers\AuctionBackController::getOffersCount($auction->id);
$price=\App\Http\Controllers\AuctionFrontController::calculatePriceID($auction->id);
$close=$price['close'];
$userRatings=\App\Http\Controllers\AuctionFrontController::getUserRating($auction->batch->arrive->boat->user);
$usercat=AuctionQuery::catUserByAuctions($userId);
$port=\App\Ports::getPortById($auction->batch->arrive->port_id);
if(isset($request->time) && $request->time!= Constants::IN_COURSE){
    if($auction->timeline== Constants::FINISHED){
        $finished='&iexcl;Finalizada!';
    }elseif($auction->timeline== Constants::FUTURE){
        $finished='&iexcl;Proximamente!';
    }else{
        unset($finished);
    }
}
$link1='#';$link2='#';$class1='button';$class2='';$onclick1='';$onclick2='';$popups=0;
if(Auth::user()){
    $userses=Auth::user();
    if($userses->status!='approved'){
        $onclick1="notifications(0,null,null,null,'Usuario no aprobado')";
        $onclick2=$onclick1;
    }elseif($userses->status=='approved' && $userses->type!= \App\User::COMPRADOR){
        $onclick1="notifications(0,null,null,null,'El tipo de usuario no permite comprar')";
        $onclick2="notifications(0,null,null,null,'El tipo de usuario no permite ofertar')";
    }else{
        $link1='#small-dialog-compra-'.$auction->id;
        $link2='#small-dialog-oferta'.$auction->id;
        $onclick1="openPopupCompra($auction->id)";
        $onclick2="openPopupOferta($auction->id)";
        $class1.=' ripple-effect popup-with-zoom-anim w100';
        $class2='class="sign-in popup-with-zoom-anim"';
        $popups=1;
    }
}else{
    $link1='/auction';
    $link2=$link1;
}
//dd($auction);
setlocale(LC_TIME,'es_ES');
$fechafin=strftime('%d %b %Y', strtotime($auction->end));
?>
<div id="Auction_<?=$auction->id?>" class="task-listing <?=(empty($finished))?'auction':''?>" data-id="{{$auction->id}}" data-price="{{$price['CurrentPrice']}}" data-end="{{$auction->end}}" data-endOrder="{{date('YmdHi',strtotime($auction->end))}}" data-close="{{$close}}"data-userrating="{{$userRatings}}"
<?='data-port="'.$auction->batch->arrive->port_id.'"
data-product="'.$auction->product['idproduct'].'"
data-caliber="'.$auction->product['caliber'].'"
data-quality="'.(($auction->batch->quality<1)?1:$auction->batch->quality).'"
data-user="'.$auction->batch->arrive->boat->user->nickname.'"'?>>
<input type="hidden" id="PresUnit<?=$auction->id?>" value="<?=$auction->product['presentation_unit']?>">
<input type="hidden" id="SaleUnit<?=$auction->id?>" value="<?=$auction->product['sale_unit']?>">
<div class="task-listing-details" id="AuctionLeft<?=$auction->id?>">
    @if(!isset($nopic))
        <div class="task-listing-photo">
            <img src="/img/products/{{$auction->product['image']}}" alt="{{$auction->product['name']}}">
        </div>
    @endif
        <div class="task-listing-description">
            <h3 class="task-listing-title">
                <a href="{{($auction->timeline!=Constants::FINISHED)?'/subastas/ver/'.$auction->id:'#'}}" id="LinkSubasta{{$auction->id}}">
                    {{$auction->product['name']}} {{trans('auction.'.$auction->product['caliber'])}}
                </a>
                <div class="star-rating" data-rating="{{$auction->batch->quality}}"></div>
                @if($auction->type!='public')
                <em class="t16 icon-feather-eye-off" data-tippy-placement="right" title="Subasta Privada" data-tippy-theme="dark"></em>
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
                <li>
                    <small>Barco</small><br>
                    <strong><em class="icon-line-awesome-ship"></em> {{ucfirst($auction->batch->arrive->boat->name)}}</strong><br>
                    <div class="star-rating" data-rating="<?=$userRatings?>"></div>
                </li>
            </ul>
            @if(isset(Auth::user()->id) && $userId==Auth::user()->id)
            <div class="bd-tp-1">
                <?php if($auction->timeline==Constants::FINISHED){?>
                <a href="/ofertas?a=<?=$auction->id?>" class="button ripple-effect" data-tippy-placement="top" data-tippy="" title="Ver Ofertas Directas"><i class="icon-material-outline-local-offer"></i> <?=($cantofertas>0)?('<span class="button-info">'.$cantofertas.'</span>'):''?></a>
                <?php }
                if($auction->timeline!=Constants::FUTURE){?>
                <a href="/auction/operations/<?=$auction->id?>" class="button ripple-effect ico" title="Ver Ventas" data-tippy-placement="top"><i class="icon-feather-dollar-sign"></i> <?=($cantbids>0)?('<span class="button-info">'.$cantbids.'</span>'):''?></a>
                <?php }
                if($auction->timeline==Constants::FUTURE){?>
                <a href="/subastas/editar/<?=$auction->id?>" class="button ripple-effect ico" title="Editar" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                <?php }
                if($auction->timeline==Constants::IN_COURSE){?>
                <a href="#" class="button  ripple-effect ico" title="Pausar" data-tippy-placement="top"><i class="icon-feather-pause"></i></a>
                <?php }?>
                <a href="/subastas/replicar/<?=$auction->id?>" class="button ripple-effect ico" data-tippy-placement="top" data-tippy="" title="Replicar"><i class="icon-material-outline-filter-none"></i></a>
                <?php if(($cantbids+$cantofertas)==0){?>
                <a href="#" class="button dark ripple-effect ico" title="Eliminar" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                <?php }
                if($auction->timeline!=Constants::FUTURE){
                ///subastas/exportar/{{$auction->id}}    
                ?>
                <a href="#" class="button dark ripple-effect ico" title="Exportar" data-tippy-placement="top"><i class="icon-material-outline-save-alt"></i></a>
                <?php }?>
            </div>
            @endif
        </div>
    </div>
    <div class="task-listing-bid">
        <div class="task-listing-bid-inner">
            <div class="task-offers">
                <p> <div id="auctionAvailability{{$auction->id}}" style="display: inline-block!important;font-weight: bold"><small style="font-weight: 400">Disponibilidad:</small> {{$disponible}} <small>de</small> {{$total}} {{$auction->product['presentation_unit']}}</div> <br>
                @if(empty($finished))
                        <small class="green fw700" id="BidsCounter{{$auction->id}}">
                            @if($cantbids>0)
                            <?=Constants::ICON_OFFERS_BIDS_GREEN.$cantbids.(($cantbids>1)?' Compras Directas':' Compra Directa')?>
                            @endif
                        </small>
                    @endif
                    </p>

                    <div class="pricing-plan-label billed-monthly-label <?=(empty($finished)?'red':'')?>" id="PriceContainer{{$auction->id}}"><strong class="red" id="Price{{$auction->id}}">${{$price['CurrentPrice']}}</strong>/ {{$auction->product['sale_unit']}}<br>
                        <small class="red fw500" id="ClosePrice{{$auction->id}}"<?=(empty($finished) || $finished!='&iexcl;Finalizada!')?'style="display:none"':''?>><?=(empty($finished))?'&iexcl;Cerca del precio l&iacute;mite!':'Precio Final'?></small></div>
                    <div id="timer<?=$auction->id?>" class="countdown <?=(isset($finished) && $finished=='&iexcl;Proximamente!')?'green ':''?>margin-bottom-0 margin-top-20 blink_me <?=(empty($finished))?'timerauction':''?>" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}">
                        <?=(isset($finished))?$finished:''?></div>
            </div>
            <input type="hidden" value="{{$auction->product['presentation_unit']}}" id="UnitAuction{{$auction->id}}">
            @if($auction->timeline==Constants::IN_COURSE)
                <div  id="OpenerPopUpCompra{{$auction->id}}">
                    <div class="w100">
                        <a href="{{$link1}}" onclick="{{$onclick1}}"  class="{{$class1}}">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="{{$link2}}" onclick="{{$onclick2}}" <?=$class2?>>realizar una oferta</a></div>
                        <div class="text-center">
                            <small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                                @if($cantofertas>0)
                                <em class="icon-material-outline-local-offer green"></em>
                                <?=$cantofertas.(($cantofertas>1)?' Ofertas Directas':' Oferta Directa')?>
                                @endif
                            </small>
                        </div>
                        
                        @if($popups==1)
                            
                            @include('landing3/partials/pop-up-compra')
                            @include('landing3/partials/pop-up-ver-ventas')
                            @include('landing3/partials/pop-up-oferta')
                        @endif
                    </div>

                </div>

            @endif
        </div>
    </div>
</div>
