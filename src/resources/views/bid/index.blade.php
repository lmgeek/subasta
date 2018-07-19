@extends('admin')

@section('content')
<?php use Carbon\Carbon; ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('auction.bids') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
       
            <div class="col-lg-12" style="margin-top: 10px">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('auction.bids') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($bids) == 0)
                            <div class="text-center">
                                {{ trans('auction.no_bids') }}
                            </div>
                        @else
                            @foreach ($bids as $a)
                                <div class="auction row">
                                    <div class="col-md-2">
                                         @include('bid.partials.auctionInfo')
                                    </div>
                                    <div class="col-md-1 text-center">
                                       <div style="margin-top:22px;">
										   <label>
											   {{ $a->amount  }}
											   {{ trans('general.product_units.'.$a->auction->batch->product->unit) }}
										  </label>
									  </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div style="margin-top:20px;">  
										   <label> {{ trans('auction.success_bid_price') }}</label><br>
										   <label>
											 $ {{ $a->price  }}
										  </label>
										</div>
                                    </div>
									 <div class="col-md-2">
                                        <div class="priceText">
											
										<div style=""> 
										   <label>
											 $ {{ $a->price * $a->amount  }}
										  </label>
										</div>
											
										</div>
                                    </div>
                                    <div class="col-md-2">
                                         <div style="margin-top:20px;">  
										   <label> {{ trans('auction.bid_date') }}</label><br>
										   <label>
											  {{ Carbon::parse($a->bid_date)->format('H:i:s d/m/Y') }}
										  </label>
										</div>
                                    </div>
									 <div class="col-md-2">
										 <div style="margin-top:20px;">  
										   <label> {{ trans('auction.bid_date_arrive') }}</label><br>
										   <label>
											  {{ Carbon::parse($a->auction->batch->arrive->date)->format('H:i:s d/m/Y') }}
										  </label>
										</div>
                                    </div>
                                    <div class="col-md-2">
                                        @if (is_null($a->seller_calification))
                                            <a href="{{ route('bid.qualify',$a) }}" style="margin-top: 30px" class="btn btn-action">{{ trans('auction.qualify') }}</a>
                                        @else
                                            <a href="#" style="margin-top: 30px" class="btn btn-action calificacion" data-cal="{{ trans('general.seller_qualification.'.$a->seller_calification) }}" data-com="{{ $a->seller_calification_comments }}" >{{ trans('auction.qualification') }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
							
							{!!  $bids->render() !!}
							
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal inmodal fade" id="qualifyStatus" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3>Calificacion Vendedor</h3>
                </div>
                <div class="modal-body text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: right; width:30%; padding-right: 5px; font-weight: bold">{{ trans('auction.seller_qualification') }}:</td>
                            <td style="text-align: left" id="cal"></td>
                        </tr>
                        <tr id="comments">
                            <td style="text-align: right; width:30%; padding-right: 5px; font-weight: bold">{{ trans('auction.comments') }}:</td>
                            <td style="text-align: left" id="calcom"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/js/plugins/datetimepicker/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('/js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/star_rating/jquery.raty.js') }}"></script>
    <script src="{{ asset('/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
     <script src="{{ asset('/js/plugins/toastr/toastr.min.js') }}"></script>
	<script src="{{ asset('/js/plugins/jsKnob/jquery.knob.js') }}"></script>
	<script>

        $(document).ready(function(){

            $('.calificacion').click(function(){

                var cal = $(this).data('cal');
                var com = $(this).data('com');

                $('#cal').html(cal);


                if(cal == '{{ trans('general.seller_qualification.'.\App\Bid::CALIFICACION_POSITIVA) }}' && com == "" ) {
                    $("#comments").hide();
                }
                $('#calcom').html(com);

                $('#qualifyStatus').modal('show');
                return false;
            });

            $('.quality').each(function(k,v){
                sc = $(v).data('score');
                $(v).raty({
                    readOnly: true,
                    score: sc,
                    starType: 'i',
                    hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
                });
            });

        });

	</script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/star_rating/jquery.raty.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/datetimepicker/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/jasny/jasny-bootstrap.min.css') }}">
    <style>
        .auction{
            width: 100%;
            /*height: 135px;*/
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .auction .status{
            top: 10px;
            left: 10px;
            width: 150px;
            height: 25px;
            border-radius: 0 0 .5em 0;
            text-align: center;
            font-family: 'Open Sans';
            font-size: 10px;
            font-weight: 600;
            padding-top: 5px;
        }
        .priceText{
            font-weight: 600;
            font-family: 'Open Sans';
            font-size: 28px;
            width: 159px;
            margin-right: 0px;
            margin-top: 12px;
            display: inline-block;

        }
        .divInterval{
            display: inline-block;
        }
        .timerDiv .more{
            margin-top:20px;
        }
    </style>
@endsection

