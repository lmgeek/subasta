@extends('admin')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4">
            <h2>Ventas privadas</h2>
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
                 @include('sales.partials.filtersprivate')
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
                              
                               
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sales->get() as $sale)
                                <tr>
                                    <td>{{ $sale->buyer_name }}</td>
                                    <td>{{ $sale->amount }} {{ trans('general.product_units.'.$sale->batch->product->unit ) }}</td>
                                    <td>{{ $sale->batch->product->name }}</td>
                                    <td class="text-right">$ {{ number_format($sale->price * $sale->amount , 2) }}</td>
                                    <td class="text-right">{{ date('d/m/Y H:i:s',strtotime($sale->date)) }}</td>
                                    <td>{{ $sale->batch->arrive->boat->name }}</td>
                                </tr>
                            @endforeach
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
	<script src="{{ asset('/js/plugins/typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script>
        $(document).ready(function(){
			
			$.get('{{ url('/bids/buyers') }}', function(data){
                $("#comprador").typeahead({ source:data });
            },'json');
		
            $('.chosen-select').chosen({width:"100%"});
        });
    </script>
@endsection

@section('stylesheets')
    <link href="{{ asset('/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection