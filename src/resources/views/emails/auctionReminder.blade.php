@extends('mail')


@section('content')
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <h2>Recordatorio de inicio de subasta</h2>
                </div>
                <div class="col-xs-6 col-xs-offset-3">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td colspan="2" class="text-center">
                                <h3>Informaci&oacute;n de la subasta</h3>
                            </td>
                        </tr>
                        <tr>
                            <th>Inicio</th>
                            <td>{{ Carbon\Carbon::parse($auction->start)->format('d/m/Y H:i:s')}}</td>
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


        </div>
    </body>
@endsection
@section('stylesheets')
    {!! file_get_contents(public_path('css/bootstrap.css')) !!}
@endsection