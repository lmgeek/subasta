@extends('landing3/partials/layout-admin')
@section('title',' | Ofertas Recibidas')
@section('content')
    <?
    use App\UserRating;
    //Creamos una instacia de user para usar el metodo rating
    $userRating = new UserRating();
    ?>
    <div class="jumbotron" style="margin: 10px auto;">
<div class="row">


@foreach($auctions as $auction)
    <div class="col-md-6" style="border: 1px solid #000000;">
        <h4>Subasta </h4><br>
        - Producto: {{ $auction->batch->product->name }}
        <br>
        {{ $auction->code }}
        <br>
        - Fecha Fin: {{ $auction->end }}
        <br>
        - Precio Limite: {{ $auction->end_price }}
        <br>
        - Calibre Producto: {{ trans('auction.'.$auction->batch->caliber) }}
        <br>
        - Calidad:
        <div class="star-rating" data-rating="{{ $auction->batch->quality }}"></div>
        <br>
        - <b>Disponibilidad:</b> {{ $available[$auction->id]['available'] }}
        <br>
        - Mejor Oferta: {{ $max[$auction->id]['price'] }}
        <h4>Ofertas</h4>
        <div class="row">
            @foreach($offers[$auction->id] as $offer)
                <div class="col-md-3" style="border: 1px solid #000000;">
                    <br>
                    - Calificacion Promedio del Comprador: <div class="star-rating" data-rating="{{ $userRating->calculateTheRatingUser($offer->user->id) }}"></div>
                    <br>
                        -Precio Oferta: {{ $offer->price }}
                        <br>
                        - Status: {{ trans('auction.'.$offer->status) }}
                        <br>
                    <br>
                    - Alias Comprador: {{ $offer->user->nickname }}
                    <br>
                    - Precio Ofrecido: {{ $offer->price }}
                    <br>
                    - Fecha de Oferta: {{ $offer->created_at }}
                    <?php
                    /*
                    echo "<pre>";
                    //print_r($offer->user->rating);
                    echo "<pre>";
                    */
                    ?>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
</div>
</div>
@endsection
