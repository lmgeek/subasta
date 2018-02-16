@extends('mail')


@section('content')
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td colspan="2" class="text-center">
                                <h3>Informacion de la subasta</h3>
                            </td>
                        </tr>
                        <tr>
                            <th>Barco</th>
                            <td>{{ $auction->batch->arrive->boat->name }}</td>
                        </tr>
                        <tr>
                            <th>Arribo</th>
                            <td>{{ Carbon\Carbon::parse($auction->batch->arrive->date)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Producto</th>
                            <td>{{ $auction->batch->product->name }}</td>
                        </tr>
                        <tr>
                            <th>Calibre</th>
                            <td>{{ trans('general.product_caliber.'.$auction->batch->caliber) }}</td>
                        </tr>
                        <tr>
                            <th>Calidad</th>
                            <td>{{ $auction->batch->quality }} estrellas</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-8 col-xs-offset-2 text-center">
                    <h2>Resumen</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Precio Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $total=0; ?>
                        @foreach($bids as $b)
                            <tr>
                                <td class="text-left">{{ Carbon\Carbon::parse($b->bid_date)->format('d/m/Y H:i:s') }}</td>
                                <td class="text-right">{{ $b->amount }} {{ trans('general.product_units.'.$auction->batch->product->unit) }}</td>
                                <td class="text-right">$ {{ $b->price }}</td>
                                <td class="text-right">$ {{ $b->amount * $b->price  }}</td>
                            </tr>
                            <?php
                            $total += $b->amount * $b->price;
                            ?>
                        @endforeach
                        <tr>
                            <td colspan="3"  class="text-right">
                                <strong>Total</strong>
                            </td>
                            <td class="text-right"><strong>$ {{$total}}</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
@endsection
@section('stylesheets')
    {!! file_get_contents(public_path('css/bootstrap.css')) !!}
@endsection