@extends('landing3/partials/layout-admin')
@section('title',' | Ofertas Recibidas')
@section('content')

@foreach($auctions as $auction)
    <h4>Subasta </h4><br>
    Producto: {{ $auction->batch->product->name }}
    <br>
    {{ $auction->code }}
    <br>
    Fecha Fin: {{ $auction->end }}
    <br>
    Precio Limite: {{ $auction->end_price }}
    <br>
    @foreach($offers[$auction->id] as $offer)
        Mejor Oferta: <?=max(array($offer->price))?>
        <br>
        Status: <?=max(array($offer->status))?>
        <br>
        <h4>Ofertas</h4>
        <br>
        Comprador: {{ $offer->user->nickname }}
        <br>
        Precio Ofrecido: {{ $offer->price }}
        <br>
        Calidad Producto: {{ $offer->quality }}
        <?php
        /*
        echo "<pre>";
        //print_r($offer->user->rating);
        echo "<pre>";
        */
        ?>
    @endforeach
@endforeach


@endsection
