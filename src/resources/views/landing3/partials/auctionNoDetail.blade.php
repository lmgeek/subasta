<?php $userId = $auction->batch->arrive->boat->user->id;
$close=($price[$auction->id]<$auction->targetprice)?1:0;
$userRatings=(($userrating[$userId]/20)<1)?1:round(($userrating[$userId]/20),0,PHP_ROUND_HALF_UP);
?>
<div id="Auction_<?=$auction->id?>" class="task-listing <?=(empty($finished))?'auction':''?>" data-id="{{$auction->id}}" data-price="{{$price[$auction->id]}}" data-end="{{$auction->end}}" data-endOrder="{{date('YmdHi',strtotime($auction->end))}}" data-close="{{$close}}"data-userrating="{{$userRatings}}"
<?='data-port="'.$ports[$auction->batch->arrive->port_id]['name'].'"
data-product="'.$auction->batch->product->name.'"
data-caliber="'.$auction->batch->caliber.'"
data-quality="'.(($auction->batch->quality<1)?1:$auction->batch->quality).'"
data-user="'.$auction->batch->arrive->boat->user->nickname.'"'?>>
<?php
setlocale(LC_TIME,'es_ES');
$fechafin=strftime('%d %b %Y', strtotime($auction->end));
switch ($auction->batch->caliber){
    case 'small':$calibre='chico';break;
    case 'medium':$calibre='mediano';break;
    case 'big':$calibre='grande';break;
}

?>
<!-- Auction Listing Details -->
    <div class="task-listing-details">
        <!-- Photo -->
        @if(!isset($nopic))
            <div class="task-listing-photo">
                <img src="{{ asset('/img/products/'.$auction->batch->product->image_name) }}" alt="{{$auction->batch->product->name}}">
            </div>
    @endif
    <!-- Details -->
        <div class="task-listing-description">
            <h3 class="task-listing-title">
                <a href="{{url('/auction/details/'.$auction->id)}}">{{$auction->batch->product->name}} {{$calibre}}</a>
                <div class="star-rating" data-rating="{{$auction->batch->quality}}"></div>
                @if($auction->type!='public')
                    <i class="t16 icon-feather-eye-off" data-tippy-placement="right" title="Subasta Privada" data-tippy-theme="dark"></i></h3>
            @endif
            </h3>


            <ul class="task-icons">
                <li><i class="icon-material-outline-access-time <?=(empty($finished)?'primary':'')?>"></i><strong class="primary" id="FriendlyDate{{$auction->id}}">{{$fechafin}}</strong></li>
                <li><i class="icon-material-outline-location-on"></i> {{$ports[$auction->batch->arrive->port_id]['name']}}</li>
            </ul>
            <p class="task-listing-text"> <?=(strlen($auction->description)>90)?substr($auction->description,0,90).'...':$auction->description?></p>
            <ul class="task-icons margin-top-20">

                <li>
                    <small>Vendedor</small><br>
                    <strong>
                        <i class="icon-feather-user"></i> {{$auction->batch->arrive->boat->user->nickname}}
                    </strong><br>
                    <div class="medal-rating {{strtolower($usercat[$userId])}}" data-rating="{{$usercat[$userId]}}"><span class="medal {{$usercat[$userId]}}-text"></span></div>
                </li>
                <li><small>Barco</small><br><strong><i class="icon-line-awesome-ship"></i> {{$auction->batch->arrive->boat->nickname}}</strong><br>
                    <div class="star-rating" data-rating="<?=$userRatings?>"></div></li>
            </ul>
        </div>
    </div>
    <div class="task-listing-bid">
        <div class="task-listing-bid-inner">
            <div class="task-offers">
                <?php
                $vendido = 0;
                foreach ($auction->bids()->where('status','<>',\App\Bid::NO_CONCRETADA)->get() as $b) {
                    $vendido+= $b->amount;
                }
                $total = $auction->amount;
                $disponible = ($total-$vendido);
                $cantofertas=count($auction->bids);
                ?>
                <p> <div id="auctionAvailability{{$auction->id}}" style="display: inline-block!important;font-weight: bold"><small style="font-weight: 400">Disponibilidad:</small> {{$disponible}} <small>de</small> {{$total}} kg</div> <br>
                @if(empty($finished))
                    <small class="green fw700" id="OffersCounter{{$auction->id}}">
                        <?=(($cantofertas>0)?('<i class="icon-material-outline-local-offer green"></i>'.$cantofertas.(($cantofertas>1)?' Ofertas Directas':' Oferta Directa')):'')?>
                    </small>
                    @endif
                    </p>

                    <div class="pricing-plan-label billed-monthly-label <?=(empty($finished)?'red':'')?>" id="PriceContainer{{$auction->id}}"><strong class="red" id="Price{{$auction->id}}">${{$price[$auction->id]}}</strong>/ kg<br>
                        <small class="red fw500" id="ClosePrice{{$auction->id}}" {{($close==0)?'style="display:none"':''}}><?=(empty($finished))?'&iexcl;Cerca del precio l&iacute;mite!':'Precio Final'?></small></div>
                    <div id="timer<?=$auction->id?>" class="countdown margin-bottom-0 margin-top-20 blink_me <?=(empty($finished))?'timerauction':''?>" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}">
                        <?=(isset($finished))?'Finalizada!':''?></div>
                        <div class="pricing-plan-label billed-monthly-label <?=(empty($finished)?'red':'')?>" id="PriceContainer{{$auction->id}}"><strong class="red" id="Price{{$auction->id}}">${{$price[$auction->id]}}</strong>/ kg<br>
                            <small class="red fw500" id="ClosePrice{{$auction->id}}" style="display:none"><?=(empty($finished))?'&iexcl;Cerca del precio l&iacute;mite!':'Precio Final'?></small></div>
                        <div id="timer<?=$auction->id?>" class="countdown margin-bottom-0 margin-top-20 blink_me <?=(empty($finished))?'timerauction':''?>" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}">
                            <?=(isset($finished))?'Finalizada!':''?></div>
            </div>
            @if(empty($finished))
                <div  id="OpenerPopUpCompra{{$auction->id}}">
                    <div class="w100">
                        @can('canBid', \App\Auction::class)
                            <div class="">
                                @if ($disponible > 0)
                                    <a
                                            @if(Auth::user()->status != "approved")
                                            href="#" onclick="notifications(0,null,null,null,'Usuario no aprobado')"
                                            @else
                                            <? if (Auth::user()->type == \App\User::VENDEDOR){ ?>
                                            href="#" onclick="notifications(0,null,null,null,'Los vendedores no pueden participar en subastas')"
                                            <? } else {?>
                                            href="#small-dialog-compra-{{$auction->id}}" class="button ripple-effect popup-with-zoom-anim w100"
                                    <? } ?>
                                            @endif
                                    >Comprar</a>
                                @endif
                            </div>
                        @else
                            <? if (Auth::user()){ ?>
                            <a
                                    @if(Auth::user()->status != "approved")
                                    href="#" onclick="notifications(0,null,null,null,'Usuario no aprobado')"
                                    @else
                                    <? if (Auth::user()->type == \App\User::VENDEDOR){ ?>
                                    href="#" onclick="notifications(0,null,null,null,'Los vendedores no pueden participar en subastas')"
                                    <? } else {?>
                                    href="#small-dialog-compra-{{$auction->id}}" class="button ripple-effect popup-with-zoom-anim w100"
                            <? } ?>
                                    @endif
                            >Comprar</a>
                            <? } else { ?>
                            <a href="{{ url('/auction') }}" class="button">Comprar</a>
                            <? } ?>
                        @endcan
                    <?php
                    if(Auth::user()){
                        $userses=Auth::user();//$userses->usersession
                        if($userses->status!="approved"){?>
                        <a href="#" class="button" onclick="notifications(0,null,null,null,'Usuario no aprobado')">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="#" onclick="notifications(0,null,null,null,'Usuario no aprobado')">realizar una oferta</a></div>
                        <?php }elseif($userses->status=="approved" and $userses->type!=\App\User::COMPRADOR){?>
                        <a href="#" class="button" onclick="notifications(0,null,null,null,'El tipo de usuario no permite comprar')">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="#" onclick="notifications(0,null,null,null,'El tipo de usuario no permite ofertar')">realizar una oferta</a></div>
                        <?php }else{?>
                        <a href="#small-dialog-compra-{{$auction->id}}" class="button ripple-effect popup-with-zoom-anim w100">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="#small-dialog-oferta{{$auction->id}}" class="sign-in popup-with-zoom-anim">realizar una oferta</a></div>
                        @include('landing3/partials/pop-up-compra')
                        <?php }
                    }else{ ?>
                        <a href="/auction" class="button">Comprar</a>
                        <div class="w100 text-center margin-top-5 t14">o puedes <a href="/auction">realizar una oferta</a></div>
                    <?php
                    }
                    ?>


                        {{--<a href="#small-dialog-compra-{{$auction->id}}" class="button ripple-effect popup-with-zoom-anim w100">Comprar</a>--}}
                    </div>

                </div>

            @endif
        </div>
    </div>
</div>
