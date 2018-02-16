@extends('admin')

@section('content')
<?php use Carbon\Carbon; ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>Editar Subasta</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('auction.batch_info') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4"><label>Barco:</label></div>
                            <div class="col-md-8"><span>{{ $auction->batch->arrive->boat->name }}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Arrivo:</label></div>
                            <div class="col-md-8"><span>{{ Carbon::parse($auction->batch->arrive->date)->format('H:i:s d/m/Y')  }}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Producto:</label></div>
                            <div class="col-md-8"><span>{{ $auction->batch->product->name}}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Calibre:</label></div>
                            <div class="col-md-8"><span>{{ trans('general.product_caliber.'.$auction->batch->caliber) }}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Calidad:</label></div>
                            <div class="col-md-8"><div id="quality" class="text-warning" style="font-size: 8px; display: inline-block;"></div> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Cantidad:</label></div>
                            <div class="col-md-8">
                                <span>{{ $auction->batch->amount }} {{ trans('general.product_units.'.$auction->batch->product->unit) }}</span><br>
                                <span>( {{ $auction->batch->status->remainder }} sin asignar <br> {{ $auction->amount }} en esta subasta )</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
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
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('auction.auction_info') }}</h5>
                    </div>
                    <form action="{{ route('auction.update') }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <input type="hidden" name="id" value="{{ $auction->id }}">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ trans('auction.auction_start') }}</label>
                                        <input type='text' class="form-control" name="fechaInicio" id="datetimepickerStart"
                                               value="{{
                                                            !is_null(old('fechaInicio')) ?
                                                                old('fechaInicio') :
                                                                Carbon::parse($auction->start)->format('d/m/Y H:i')
                                               }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ trans('auction.auction_end') }}</label>
                                        <input type='text' class="form-control"  name="fechaFin" id="datetimepickerEnd"
                                               value="{{
                                                            !is_null(old('fechaFin')) ?
                                                                old('fechaFin') :
                                                                Carbon::parse($auction->end)->format('d/m/Y H:i')

                                               }}"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">{{ trans('auction.auction_price_start') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" name="startPrice" class="form-control"
                                                   value="{{
                                                            !is_null(old('startPrice')) ?
                                                                old('startPrice') :
                                                                $auction->start_price
                                               }}"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">{{ trans('auction.auction_price_end') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" name="endPrice" class="form-control"
                                                   value="{{
                                                            !is_null(old('endPrice')) ?
                                                                old('endPrice') :
                                                                $auction->end_price
                                                   }}"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Cantidad</label>
                                    <br>
                                    <div class="text-center">
                                        <input type="text" value="{{ (is_null(old('amount'))?$auction->amount:old('amount')) }}" data-max="{{ $auction->batch->status->remainder + $auction->amount }}" name="amount" class="dial m-r" data-fgColor="#1AB394" data-width="75" data-height="75"/>
                                        <br><small>{{ trans('general.product_units.'.$auction->batch->product->unit) }}</small>
                                    </div>

                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ trans('auction.interval') }}</label>
                                        <div id="ionrange_2"></div>
                                        <input type="hidden" name="intervalo" id="interval" value="{{ (is_null(old('interval')) ? $auction->interval : old('interval') ) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-footer text-right">
                            <button type="submit" class="btn btn-primary">Editar Subasta</button>
                            <a href="{{ url('home') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/js/plugins/datetimepicker/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('/js/plugins/ionRangeSlider/ion.rangeSlider_2.1.1.js') }}"></script>
    <script src="{{ asset('/js/plugins/star_rating/jquery.raty.js') }}"></script>
    <script src="{{ asset('/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/jsKnob/jquery.knob.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#ionrange_2").ionRangeSlider({
                min: 0,
                max: 60,
                type: 'single',
                step: 5,
                postfix: " {{ trans('auction.minutes') }}",
                prefix: "{{ trans('auction.every') }} ",
                hide_min_max: true,
                prettify: false,
                grid: true,
                from: $("#interval").val(),
                onFinish: function (data) {
                    $("#interval").val(data.from);
                },
            });

            $('#quality').raty({
                readOnly: true,
                score: {{ $auction->batch->quality }},
                starType: 'i',
                hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
            });

            $(".dial").knob();


        });

        $(function () {
            $('#datetimepickerStart,#datetimepickerEnd').datetimepicker({
                locale: '{{ Config::get('app.locale') }}',
                sideBySide: true,
                format: 'DD/MM/YYYY HH:mm',
            });
        });
    </script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/star_rating/jquery.raty.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/datetimepicker/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/ionRangeSlider/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/jasny/jasny-bootstrap.min.css') }}">
@endsection

