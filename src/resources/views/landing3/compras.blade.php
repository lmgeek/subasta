<?php
use App\Constants;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.((Auth::user()->type==Constants::COMPRADOR)?'Mis ':'').'Compras')
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-dollar-sign"></i> <?=((Auth::user()->type==Constants::COMPRADOR)?'Mis ':'')?>Compras<?=((Auth::user()->type!=Constants::COMPRADOR)?(' de '. ucfirst($user->name).' '. ucfirst($user->lastname).' ('.$user->nickname.')'):'')?></h3></div>
@foreach($user->bids as $auction)
<div class="row" style="margin:10px">
    <div class="col">
        <div class="headline">
            <div class="col">
                <h3>
                    <i class="icon-material-outline-gavel"></i> Subasta: <?= App\Http\Controllers\AuctionFrontController::getAuctionCode($auction->correlative, $auction->StartDateAuction)?>
                    <span class="dashboard-status-button <?=Constants::colorByStatus($auction->status)?>"><?=trans('general.bid_status.'.$auction->status)?></span>

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
                Cantidad<br>
                <?=$auction->amount?>
            </div>
            <div class="col">
                Total<br>
                <?= number_format($auction->price*$auction->amount, 2, ',', '.')?> ARS
            </div>
            <div class="col">
                Fecha de compra<br>
                <?= date_create_from_format('Y-m-d H:i:s', $auction->bid_date)->format('d/m/Y - H:i:s')?>
            </div>
        </div>
        @if($auction->status==Constants::CONCRETADA || $auction->status==Constants::NO_CONCRETADA)
        <div class="row" style="margin-top:10px;border-top: 1px solid #e0e0e0;">
            @if($auction->status==Constants::NO_CONCRETADA)
            <div class="col">
                Razon<br>
                <?=$auction->reason?>
            </div>
            @endif
            <div class="col">
                Calificacion Comprador<br>
                <?=trans('general.buyer_qualification.'.$auction->user_calification)?>
            </div>
            <div class="col">
                Comentarios Comprador<br>
                <?=$auction->user_calification_comments?>
            </div>
            <div class="col">
                Calificacion Vendedor<br>
                <?=trans('general.buyer_qualification.'.$auction->seller_calification)?>
            </div>
            <div class="col">
                Comentarios Vendedor<br>
                <?=$auction->seller_calification_comments?>
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach
<?=$user->bids->render()?>
@endsection