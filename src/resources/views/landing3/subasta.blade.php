<?php

use App\Auction;
use App\UserRating;
use App\Constants;
//use Illuminate\Auth;

//Creamos un objeto de ña clase para usar sus funciones
$objtAuction = new Auction();

//Creamos una instacia de user para usar el metodo rating
$userRating = new UserRating();

//Id del usuario
$userId = $auction->batch->arrive->boat->user->id;
$outsidehome=1;
$cantofertas=\App\Http\Controllers\AuctionController::getOffersCount($auction->id);
$cantcompras=$availability['sold'];

?>
@extends('landing3/partials/layout')
@section('title',' | Lista de subastas')
@section('content')
    <div class="single-page-header bd-bt-1 margin-top-35 auction nodelete" id="Auction_{{$auction->id}}" data-id="{{$auction->id}}" data-background-image="/landing3/images/single-auction.jpg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="single-page-header-inner">
                        <div class="left-side">
                            <div class="header-image"><img src="/img/products/{{$auction->batch->product->image_name}}" alt="{{$auction->batch->product->name}}"></div>
                            <div class="header-details">
                                <h3 class="margin-bottom-0">{{$auction->batch->product->name}} {{trans('auction.'.$auction->batch->caliber)}} <div class="star-rating" data-rating="5.0"></div>
                                    @if($auction->type!='public') 
                                        <em class="t16 icon-feather-eye-off" data-tippy-placement="right" title="Subasta Privada" data-tippy-theme="dark"></em>
                                    @endif
                                </h3>


                                <ul>
                                    <li><em class="icon-material-outline-access-time primary"></em><strong class="primary">{{\App\Constants::formatDate($auction->end)}}</strong></li>
                                    <li><em class="icon-material-outline-location-on"></em> {{\App\Http\Controllers\AuctionController::getPortById($auction->batch->arrive->port_id) }}</li>
                                    <li style="display: none" id="HotAuction{{$auction->id}}"><em class="icon-line-awesome-fire red" ></em> <strong class="red">¡Subasta caliente!</strong></li>
                                </ul>
                                <ul class="task-icons margin-top-6">
                                    <li>
                                        <small>Vendedor</small><br>
                                        <strong><em class="icon-feather-user"></em> {{$auction->batch->arrive->boat->user->nickname}}</strong><br>
                                        <div class="medal-rating {{strtolower(\App\Auction::catUserByAuctions($userId))}}" data-rating="{{\App\Auction::catUserByAuctions($userId)}}">
                                            <span class="medal {{\App\Auction::catUserByAuctions($userId)}}"></span>
                                        </div>
                                    </li>
                                    <li><small>Barco</small><br>
                                        <strong><em class="icon-line-awesome-ship"></em>{{$auction->batch->arrive->boat->nickname}}</strong><br>
                                        <div class="star-rating" data-rating="<?php $userRating->calculateTheRatingUser($userId)?>"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="right-side">
                            <div class="salary-box">
                                <div class="salary-type"><span>&Uacute;ltimo precio:</span></div>
                                <div class="salary-amount t32"><strong  id="Price{{$auction->id}}">${{$price}}</strong>/ Kg<br>
                                    <small class="green fw400" id="BidsCounter{{$auction->id}}">
                                        <?php if($cantcompras>0){?>
                                            <em id="ofertasDirectas" class="icon-material-outline-local-offer green"></em>
                                            {{$cantcompras}} Compras Directas
                                        <?php }?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <!-- Content -->
            <div class="col-xl-8 col-lg-8 content-right-offset">

                <!-- Description -->
                <div class="single-page-section">
                    <h3 class="margin-bottom-25">Descripci&oacute;n</h3>
                    <p>{{$auction->description}}</p>

                </div>
                <div class="clearfix"></div>

            </div>


            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4">
                <br class="sidebar-container">
                <?php if($auction->end>date(Constants::DATE_FORMAT) && $availability['available']>0){?>
                <div id="timer<?=$auction->id?>" class="margin-bottom-0 margin-top-20 blink_me countdown timerauction" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}"></div>
                <?php }else{?>
                <div id="timer<?=$auction->id?>" class="margin-bottom-0 margin-top-20 blink_me countdown" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}">&iexcl;Finalizada!</div>    
                <?php }?>
                <br>
                {{--<div class="countdown primary margin-bottom-25 t24" id="timer_1"></div>--}}
                <?php if($auction->end>date(Constants::DATE_FORMAT) && $availability['available']>0){?>
                <div class="sidebar-widget">
                    <div class="bidding-widget">
                        <div class="bidding-headline bg-primary"><h3 class="white">&iexcl;Realiza tu compra ahora!</h3></div>
                        <div class="bidding-inner">

                            <!-- Headline -->
                            <span class="bidding-detail t18 bd-bt-1 padding-bottom-10"  id="auctionAvailabilitypopup{{$auction->id}}" >
                                <small style="font-weight: 400">Disponibilidad:</small> {{$objtAuction->available($auction->id,$auction->amount)}} <small>de</small> {{$auction->amount}} {{$auction->batch->product->unit}}
                            </span>

                            <!-- Headline -->
                            <span class="bidding-detail margin-top-10 fw300">Por favor, haz tu pedido:</span>

                            <!-- Fields -->
                            <div class="bidding-fields">
                                <div class="bidding-field">
                                    <!-- Quantity Buttons -->
                                    <div class="qtyButtons">
                                        <div class="qtyDec" data-id="{{$auction->id}}"></div>
                                        <input type="text" name="qtyInput" value="1" id="cantidad-{{$auction->id}}" max="{{$objtAuction->available($auction->id,$auction->amount)}}">
                                        <div class="qtyInc" data-id="{{$auction->id}}"></div>
                                    </div>
                                </div>
                                <div class="bidding-field">
                                    <input type="text" class="with-border" value="{{$auction->batch->product->unit}}" id="UnitAuction{{$auction->id}}" disabled>
                                </div>
                            </div>
                            <div class="bidding-fields">
                                <div class="checkbox">
                                    <input type="checkbox" id="checkbox{{$auction->id}}"  onclick="popupCompraDisableText({{$auction->id}})">
                                    {{--<input type="checkbox" id="chekcbox1" onclick="enable_text(this.checked)">--}}
                                    <label for="checkbox{{$auction->id}}"><span class="checkbox-icon"></span> Adquirir todo el lote</label>
                                </div>
                            </div>

                            <!-- Button -->
                            <div  id="OpenerPopUpCompra{{$auction->id}}">
                                <div class="w100">
                                    <?php
                                    if(Auth::user()){
                                    $userses=Auth::user();//$userses->usersession
                                    if($userses->status!="approved"){?>
                                    <a href="#" class="button" onclick="notifications(0,null,null,null,'Usuario no aprobado')">Comprar</a>
                                    <div class="w100 text-center margin-top-5 t14">o puedes <a href="#" onclick="notifications(0,null,null,null,'Usuario no aprobado')">realizar una oferta</a></div>
                                    <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?Constants::OFERTAS_DIRECTAS:Constants::OFERTA_DIRECTA)):'')?>
                                        </small></div>
                                    <?php }elseif($userses->status=="approved" && $userses->type!=\App\User::COMPRADOR){?>
                                    <a href="#" class="button" onclick="notifications(0,null,null,null,'El tipo de usuario no permite comprar')">Comprar</a>
                                    <div class="w100 text-center margin-top-5 t14">o puedes <a href="#" onclick="notifications(0,null,null,null,'El tipo de usuario no permite ofertar')">realizar una oferta</a></div>
                                    <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?Constants::OFERTAS_DIRECTAS:Constants::OFERTA_DIRECTA)):'')?>
                                        </small></div>
                                    <?php }else{?>
                                        <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form" onclick="makeBid({{$auction->id}})">Comprar <em class="icon-material-outline-arrow-right-alt"></em></button>
                                    <div class="w100 text-center margin-top-5 t14">o puedes <a href="#small-dialog-oferta{{$auction->id}}" class="sign-in popup-with-zoom-anim">realizar una oferta</a></div>
                                        <input type="hidden" id="PriceBid{{$auction->id}}">
                                    <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?Constants::OFERTAS_DIRECTAS:Constants::OFERTA_DIRECTA)):'')?>
                                        </small></div>
                                    @include('landing3/partials/pop-up-oferta')
                                    <?php }
                                    }else{ ?>
                                    <a href="/auction" class="button">Comprar</a>
                                    <div class="w100 text-center margin-top-5 t14">o puedes <a href="/auction">realizar una oferta</a></div>
                                    <div class="text-center"><small class="green fw700 text-center" id="OffersCounter{{$auction->id}}">
                                            <?=(($cantofertas>0)?(Constants::ICON_OFFERS_BIDS_GREEN.$cantofertas.(($cantofertas>1)?Constants::OFERTAS_DIRECTAS:Constants::OFERTA_DIRECTA)):'')?>
                                        </small></div>
                                    <?php
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <?php }?>
            </div>
        </div>

    </div>
@endsection