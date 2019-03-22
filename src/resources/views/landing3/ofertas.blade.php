<?php
use App\Constants;
use App\User;
?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.((Auth::user()->type==Constants::COMPRADOR)?'Mis ':'').'Ofertas')
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-tag"></i> <?=((Auth::user()->type==Constants::COMPRADOR)?'Mis ':'')?>Ofertas<?=((Auth::user()->type!=Constants::COMPRADOR)?(' de '. ucfirst($user->name).' '. ucfirst($user->lastname).' ('.$user->nickname.')'):'')?></h3></div>
@foreach($user->offers as $auction)
<div class="row"<?=(count($user->offers)>0)?' style="margin:10px"':' style="display:none"'?>>
    <div class="col">
        <div class="headline">
            <div class="col">
                <h3>
                    <i class="icon-material-outline-gavel"></i> Subasta: <?= App\Http\Controllers\AuctionFrontController::getAuctionCode($auction->correlative, $auction->StartDateAuction)?>
                    <span class="dashboard-status-button <?=Constants::colorByStatus($auction->status)?>"><?=trans('general.offer_status.'.$auction->status)?></span>
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Producto<br>
                <?=$auction->name.' '.trans('general.product_caliber.'.$auction->caliber)?>
            </div>
            <div class="col">
                Precio/ <?=$auction->sale_unit?><br>
                <?= number_format($auction->price, 2, ',', '.')?> ARS
            </div>
            <div class="col">
                Fecha de oferta<br>
                <?= date_create_from_format('Y-m-d H:i:s', $auction->created_at)->format('d/m/Y - H:i:s')?>
            </div>
        </div>
    </div>
</div>
@endforeach
<?=$user->offers->render()?>
@endsection