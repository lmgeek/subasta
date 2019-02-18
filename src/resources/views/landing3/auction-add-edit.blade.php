<?php
/*
 * In @section('content') goes all that's inside
 * .dashboard-content-container
 * (Lines 31->242 dash-nueva-subasta.php)
 * The content of the previously mentioned div in some point should transform to 
 * a form which action would be = /auctionStore
 * the rest of the content goes exactly as in the provided desing only changing
 * the selects of boats, ports and products
 * $code would be for the auction id field, it cant be shown because we need to 
 * avoid conflicts at saving in case of multiple sessions
 * In order to the form to work it needs the csrf_field
 * the names of the fields that are explitly declared should stay the same
 * Other name for inputs/selects
 *      Fecha Tentativa de entrega: date
 *      Calibre:    caliber
 *      Calidad:    quality
 *      Cantidad:   amount
 *      idbatch:    batch
 *      idarrive:   id
 *      datestart:  fechaInicio
 *      activehours:fechaFin
 *      startprice: startPrice
 *      endprice:   endPrice  
 *      description:descri
 *      privacy:    tipoSubasta
 *      guests:     invitados[]
 */
$code='SU-'.date('ym').'XXX';
?>
@extends('landing3/partials/layout-admin')
@section('title',' | Ofertas Recibidas')
@section('content')

{{csrf_field()}}


<select class="selectpicker with-border" name="barco" data-size="7" title="Selecciona..." onchange="cambiaValores()">
@foreach($boats as $boat)
    <option value="{{$boat->id}}">{{$boat->name}}</option>
@endforeach    
</select>



<select id="puerto" name="puerto" class="selectpicker with-border" title="Selecciona...">
@foreach($ports as $port)
    <option value ="{{$port->id}}">{{$port->name}}</option>
@endforeach
</select>



<select class="selectpicker with-border" name="product" data-live-search="true" title="Selecciona...">
@foreach($products as $product)
    <option value="{{$product->id}}">{{$product->name}}</option>
@endforeach
</select>



@include('/landing3/partials/pop-up-barco')

@endsection


