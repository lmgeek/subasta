<?php

/*
 * In @section('content') goes all that's inside
 * .dashboard-content-container
 * (Lines 33->608 dash-list-ofertas.php)
 * In the foreach goes each ship container with all the actions and data
 * 
 */
?>
@extends('landing3/partials/layout-admin')
@section('title',' | Ofertas Recibidas')
@section('content')

@foreach($auctions as $auction)
    <?php echo 'Auction <br>';var_dump($auction);echo '<br>'?>




    @foreach($offers[$auction->id] as $offer)
    <?php echo '&nbsp;&nbsp;&nbsp;Offer<br>';var_dump($offer);echo '<br>'?>
    @endforeach
@endforeach


@endsection
