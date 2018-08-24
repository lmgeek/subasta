@extends('admin')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4">
            <h2>Ventas</h2>
        </div>
        <div class="col-lg-8">
            <h3>
                <div class="text-center">
                    <h2>Total ventas: $ {{ number_format($total,2) }}</h2>
                </div>
            </h3>

        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-md-12">
                @include('sales.partials.filters')
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('auction.operations') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover dataTables-example dataTable">
                            <thead>
                            <tr>
                                <th>Comprador</th>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Importe total</th>
                                <th>Fecha</th>
                                <th>Barco</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(!empty($bids))
                                    @foreach($bids->get() as $sale)
                                        <tr>
                                            <td>{{ $sale->user->name }}</td>
                                            <td>{{ $sale->amount }} {{ trans('general.product_units.'.$sale->auction->batch->product->unit ) }}</td>
                                            <td>{{ $sale->auction->batch->product->name }}</td>
                                            <td class="text-right">$ {{ $sale->price * $sale->amount }}</td>
                                            <td class="text-right">{{ date('d/m/Y H:i:s',strtotime($sale->bid_date)) }}</td>
                                            <td>{{ $sale->auction->batch->arrive->boat->name }}</td>
                                            <?php
                                                switch ($sale->status){
                                                    case \App\Bid::PENDIENTE:
                                                        $class = "warning";
                                                        break;
                                                    case \App\Bid::NO_CONCRETADA:
                                                        $class = "danger";
                                                        break;
                                                    default:
                                                        $class = "success";
                                                }
                                            ?>
                                            <td class="{{ $class }}">{{ trans('general.bid_status.'.$sale->status) }}</td>
                                            <td>
                                                @if ($sale->status == \App\Bid::PENDIENTE)
                                                    <a href="{{ route('auction.operations.process',$sale) }}" class="btn-action">{{ trans('auction.process') }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.chosen-select').chosen({width:"100%"});
        });
    </script>
@endsection

@section('stylesheets')
    <link href="{{ asset('/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection