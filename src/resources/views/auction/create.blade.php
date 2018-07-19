@extends('admin')

@section('content')
<?php use Carbon\Carbon; ?>
<style>
    #total {font-size: 12px!important}
</style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('auction.auction_create') }}</h2>
        </div>
    </div>
<br>
<button class="btn btn-danger" onclick="history.back()"><span class="glyphicon glyphicon-level-up"></span> Volver</button>
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
                            <div class="col-md-8"><span>{{ $batch->arrive->boat->name }}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Arrivo:</label></div>
                            <div class="col-md-8"><span>{{ Carbon::parse($batch->arrive->date)->format('H:i:s d/m/Y')  }}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Producto:</label></div>
                            <div class="col-md-8"><span>{{ $batch->product->name}}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Calibre:</label></div>
                            <div class="col-md-8"><span>{{ trans('general.product_caliber.'.$batch->caliber) }}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Calidad:</label></div>
                            <div class="col-md-8"><div id="quality" class="text-warning" style="font-size: 8px; display: inline-block;"></div> </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><label>Cantidad:</label></div>
                            <div class="col-md-8">
                                <span>{{ $batch->amount }} {{ trans('general.product_units.'.$batch->product->unit) }}</span><br>
                                <span>( {{ $batch->status->remainder }} {{ trans('auction.available') }} )</span>
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
                    <form action="{{ route('auction.store') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="batch" value="{{ $batch->id }}">
                        <div class="ibox-content">
                            <div class="row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1">Tipo de subasta</label>
                                <select class="form-control m-b" name="tipoSubasta" id="tipoSubasta" class="">
                                        <option @if ( old('tipoSubasta') == \App\Auction::AUCTION_PUBLIC  ) selected @endif value="{{ \App\Auction::AUCTION_PUBLIC }}">Publica</option>
                                        <option @if ( old('tipoSubasta') == \App\Auction::AUCTION_PRIVATE  ) selected @endif  value="{{ \App\Auction::AUCTION_PRIVATE }}">Privada</option> 
                                 </select>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ trans('auction.auction_start') }}</label>
                                        <input type='text' class="form-control" name="fechaInicio" id="datetimepickerStart" value=" @if( old('fechaInicio') != null ) {{ old('fechaInicio') }} @else <? echo date('d/m/Y H:i:s');?> @endif" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ trans('auction.auction_end') }}</label>
                                        <input type='text' class="form-control"  name="fechaFin" id="datetimepickerEnd" value="{{ old('fechaFin') }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="interval">{{ trans('auction.interval') }}</label>
                                        <div id="ionrange_2"></div>
                                        <input type="hidden" name="intervalo" id="interval" value="{{ (is_null(old('interval'))?"0":old('interval')) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">{{ trans('auction.auction_price_start') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" name="startPrice" class="form-control" value="{{ old('startPrice') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">{{ trans('auction.auction_price_end') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" name="endPrice" class="form-control" value="{{ old('endPrice') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Cantidad</label>
                                    <br>
                                    <div class="text-center">
                                        <input type="text" value="{{ (is_null(old('amount'))?$batch->status->remainder:old('amount')) }}" data-max="{{ $batch->status->remainder }}" data-displayPrevious=true name="amount" class="dial m-r" data-fgColor="#1AB394" data-width="100" data-height="100" />
                                        <input type="hidden" id="weigth" class="monto" value="{{ $batch->product->weigth }}" disabled/>
                                        <? $max = $batch->product->weigth * $batch->status->remainder; ?>
                                        <br><small>{{ trans('general.product_units.'.$batch->product->unit) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Conversión a Kg</label>
                                    <br>
                                    <div class="text-center">
                                        <input type="text" id="total" value="<? if('NaN'){ echo 0; } ?>" data-max="{{ $max }}" data-displayPrevious=true name="total" class="dial m-r" data-fgColor="#1AB394" data-width="100" data-height="100" readonly/>
                                        <br><small><b>Kg.</b></small>

                                    </div>
                                </div>

                            </div>
							
						
						<div class="row buyers-auction" style="display:none">
							<div class="col-md-12">
								<div class="form-group">
                                        <label class="font-noraml"><strong>Compradores Invitados</strong></label>
                                         <select data-placeholder="Invitados" name="invitados[]"  class="required form-control chosen-select" multiple tabindex="4">
                                           @foreach($buyers as $buyer)
												<option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
										   @endforeach
                                        </select>
                                    </div>
							</div>
						</div>
							
                        </div>
                        <div class="ibox-footer text-right">
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Creando...">{{ trans('auction.create_auction') }}</button>
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
	<script src="{{ asset('/js/plugins/chosen/chosen.jquery.js') }}"></script>
    
	<script type="text/javascript">
        $(document).ready(function(){
				
			
			$('.chosen-select').chosen({width:"100%"});
			
		
			$("#tipoSubasta").change(function(){
				var t = "<?php echo  \App\Auction::AUCTION_PRIVATE ?>";
				
				if ($(this).val() == t){
					$(".buyers-auction").show();
				}else{
					$(".buyers-auction").hide();
				}
			
			});	
				
			
		
			
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
                score: {{ $batch->quality }},
                starType: 'i',
                hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
            });

            // $(".dial").knob();
            $(".dial").knob({
                change: function (value) {
                    // console.log("change : " + value);
                    var total = 0;
                    var val1 = value;
                    var val2 = document.getElementById("weigth").value;
                    total =  val1 * val2;

                    $("#total").val(total);

                }
            });


			<?php if (old('tipoSubasta') == \App\Auction::AUCTION_PRIVATE) { ?>
					$(".buyers-auction").show();
			<?php }	?>

        });


        $(function () {
            $('#datetimepickerStart,#datetimepickerEnd').datetimepicker({
                locale: '{{ Config::get('app.locale') }}',
                sideBySide: true,
                useCurrent:true,
                format: 'DD/MM/YYYY HH:mm',
                minDate: moment()
            });

            $('#datetimepickerStart').on('dp.change',function(e){
                var date = e.date;
                console.log(moment());
                /**
                 * e.date
                 * @return momentJS object
                 */
                $('#datetimepickerEnd').data('DateTimePicker').minDate(date);
                $('#datetimepickerEnd').data('DateTimePicker').date(date);

            });

        });



        $(document).ready(function () {
            $("#texto1").keyup(function () {
                var value = $(this).val();
                $("#texto2").val(value);
            });
        });


        $(document).ready(function () {
            $(".amount").change(function () {
                var value = $(this).val();
                $("#calculofecha").val(value);
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
	<link rel="stylesheet" href="{{ asset('/css/plugins/chosen/chosen.css') }}" >

@endsection

