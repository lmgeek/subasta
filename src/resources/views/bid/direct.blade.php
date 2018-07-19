@extends('admin')

@section('content')
    <?php use Carbon\Carbon; ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>Venta Privada</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="ibox float-e-margins">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Error</strong><br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('savePrivateSale') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $batch->id }}">
                        <div class="ibox-title">
                            <h5>Datos de la venta</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <input type="text" value="{{ (is_null(old('amount'))?$batch->status->remainder:old('amount')) }}" data-max="{{ $batch->status->remainder }}" name="amount" class="dial m-r" data-fgColor="#1AB394" data-width="150" data-height="150"/><br>
                                    <h2>{{ trans('general.product_units.'.$batch->product->unit) }}</h2>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="peso">Peso promedio por Unidad ( {{ trans('general.product_units.'.$batch->product->unit) }} )</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="peso" name="peso" placeholder="" aria-describedby="basic-addon2" value="{{ old('peso') }}">
                                            <span class="input-group-addon" id="basic-addon2">Kg</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="importe">Importe</label>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon2">$</span>
                                            <input type="number" class="form-control" name="importe" id="importe" value="{{ old('importe') }}" placeholder="" aria-describedby="basic-addon2">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="comprador">Razon social del comprador</label>
                                        <input type="text" class="form-control" id="comprador" name="comprador" value="{{ old('comprador') }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-footer text-right">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/jsKnob/jquery.knob.js') }}"></script>
    <script src="{{ asset('/js/plugins/typeahead/bootstrap3-typeahead.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            $.get('{{ url('/bids/buyers') }}', function(data){
                $("#comprador").typeahead({ source:data });
            },'json');

            $(".dial").knob();
        });
    </script>
@endsection

@section('stylesheets')
@endsection