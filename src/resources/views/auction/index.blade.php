@extends('admin')

@section('content')
<?php use Carbon\Carbon; ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('auction.auction_list') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
			
				@if(isset($sellerAuction) and $sellerAuction==true)
					@include('auction.partials.auctionSellerMenu')
				@else
					@include('auction.partials.auctionBuyerMenu')
				@endif
			
            <div class="col-lg-12" style="margin-top: 10px">
                <div class="ibox float-e-margins">
				@if(Auth::user()->isBuyer())
					 @can('canBid', \App\Auction::class)
					 
					 @else
							<div class="alert alert-warning">
                                <strong> {{ trans('auction.bid_limit')  }}  </strong>
                            </div>
					 @endcan
				@endif 
                    <div class="ibox-title">
                        <h5>{{ trans('auction.auctions') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($auctions) == 0)
                            <div class="text-center">
                                {{ trans('auction.no_auction') }}
                            </div>
                        @else
                            @foreach ($auctions as $a)
                                <div class="auction row">
                                    <div class="col-md-2">
                                        @include('auction.partials.auctionInfo')
                                    </div>
                                    <div class="col-md-3 text-center">
                                        @include('auction.partials.auctionPrice')
                                    </div>
                                    <div class="col-md-2">
										@if($a->active == \App\Auction::ACTIVE)
											@include('auction.partials.auctionAvailability')
										@endif
                                    </div>
                                    <div class="col-md-3">
											@include('auction.partials.auctionTime')
                                    </div>
									 <div class="col-md-2">
                                        @if(isset($sellerAuction) and $sellerAuction==true)
                                            @if($status == \App\Auction::MY_IN_CURSE or $status == \App\Auction::MY_FINISHED)
                                                @include('auction.partials.auctionDetail')
											@else
											  <br>
                                            @endif
											
											@if( $status != \App\Auction::MY_FINISHED )
												<div class="col-md-2">
													@if($a->active == \App\Auction::ACTIVE)
														<a class="btn btn-action cancelAuction" href="{{ route('auction.deactivate',$a) }}">{{trans('auction.btnauction_cancel')}}</a>
														@if($status == \App\Auction::MY_FUTURE )
															<a class="btn btn-action" href="{{ route('auction.edit',$a) }}">Editar</a>
														@endif
													@else
														<div style="margin-left:115px;margin-bottom:22px">
															<span class="label label-danger pull-right">{{trans('auction.auction_cancel')}}</span>
														</div>
													@endif
												</div>
											@endif
                                        @else
											@if($a->active == \App\Auction::ACTIVE)
												@include('auction.partials.auctionBid')
											@endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
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
	<script src="{{ asset('/js/plugins/chosen/chosen.jquery.js') }}"></script>

	<script>

	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
		$(document.body).on('hidden.bs.modal', function () {
				$(".amount-bid-modal").val('');
				$(".modal-price").html('');
				$(".content-danger").html('');
		});
	
		$(".amount-bid-modal").keyup(function(){
		
			var auctionId = $(this).attr('auctionId');
			var value = $(this).val();
			var price = $(".hid-currentPrice-"+auctionId).val();
			var total = (value*price)
			$(".modal-total-"+auctionId).html('Total $' + total.toFixed(2) )
			
		
		});
	
	});
	
	function calculatePrice(auctionId)
	{
		$.ajax({
		  method: "GET",
		  url: "/calculateprice?auction_id="+auctionId,
		  success: function(data)
		  {
			$(".currentPrice-"+auctionId).html('$' + data);
			$(".hid-currentPrice-"+auctionId).val(data);
			
				if($('#bid-Modal-'+auctionId).is(':visible'))
				{
					var total = ( $("#amount-bid-" + auctionId ).val()  * data);
					$(".modal-total-"+auctionId).html('Total $' + total.toFixed(2) )		
				}
			
		  }
		});
	}

	function showBill(text){
				toastr.remove()
				var options = {
				  "closeButton": true,
				  "positionClass": "toast-top-right",
				  "showDuration": "99999",
				  "debug": false,
				  "onclick": null,
				  "hideDuration": "99999",
				  "extendedTimeOut":"999999",
				  "timeOut": "99999",
				  "onHidden":function(){
						location.reload();
				  }
				};

			toastr.success(text, '', options);

	}

	function showBillError(text){
				toastr.remove()
				var options = {
				  "closeButton": true,
				  "positionClass": "toast-top-right",
				  "showDuration": "99999",
				  "debug": false,
				  "onclick": null,
				  "hideDuration": "99999",
				  "extendedTimeOut":"999999",
				  "timeOut": "99999",
				  "onHidden":function(){
						location.reload();
				  }
				};

			toastr.error(text, '', options);

	}


	$(document).ready(function(){

	
		$('.chosen-select').chosen({width:"100%"});
		


		$(".cancelAuction").click(function(){
			if(!confirm('Esta seguro que quiere cancelar la subasta?')){
				return false;
			}
		});


        $(".auctionIds").each(function(){
            calculatePrice($(this).val());
        });

        $(".dialInterval").knob({
            'format' : function (value) {
                var m = Math.floor(value/60);
                var s = value%60;
                if (s<10)
                    s = "0"+s;

                if (m<10)
                    m = "0"+m;

                return m+":"+s;

            }
        });

        setInterval(function(){
            $('.dialInterval').each(function(k,v){
                valores = $(v).val().split(':');
                if (valores.length == 2){
                    nuevoValor = (valores[0]*60)+(valores[1]-1);
                }else{
                    nuevoValor = valores[0]-1;
                }

                if (nuevoValor == 0){
                    nuevoValor = parseInt($(v).data('max'))-1;
					var auctionId = $(v).attr('auctionId');
					var active = $(v).attr('active');
					if (active==1){
						calculatePrice(auctionId);
					}
                }
                $(v).val(nuevoValor).trigger("change");
            });
        }, 1000);

        setInterval(function(){
            $('.dialLeft').each(function(k,v){
                valores = $(v).val().split(':');
                if (valores.length == 2){
                    nuevoValor = (valores[0]*60)+(valores[1]-1);
                }else{
                    nuevoValor = valores[0]-1;
                }
                $(v).val(nuevoValor).trigger("change");
            });
        }, {{ env('AUCTION_GRAPH_UPDATE_INTERVAL',60000) }});

        $(".dialLeft").knob({
            'format' : function (value) {
                var h = Math.floor(value/60);
                var m = value%60;

                if (h<10)
                    h = "0"+h;

                if (m<10)
                    m = "0"+m;

                return h+":"+m;
            }
        });

		$(".make-bid").click(function(){
			var auctionId = $(this).attr('auctionId');
			var amount = $("#amount-bid-"+auctionId).val();
			makeBid(auctionId,amount);


		});

		$(".amount-bid").keypress(function(e) {
			if(e.which == 13) {
				var auctionId = $(this).attr('auctionId');
				var amount = $("#amount-bid-"+auctionId).val();
				makeBid(auctionId,amount);
                return false;
			}
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

	function makeBid(auctionId,amount)
	{
		var cDispo =  parseInt($(".s-disponible-" +auctionId).html());
		
		if ( amount <= cDispo  ){
			$.ajax({
						  method: "GET",
						  dataType:"json",
						  url: "/makeBid?auction_id="+auctionId + "&amount="+amount,
						  success: function(data)
						  {
							if (data.active == 0)
							{
								$(".modal").modal('hide');
										var note = '';
											note+= '<table>';
												note+= '<tr>';
													note+= '<td colspan="2"><strong>{{ trans("auction.error_auction_cancel") }}</strong></td>';
												note+= '</tr>';
											note+= '</table>';
									showBillError(note);
							}else {
								if (data.isnotavailability == 0)
								{
									$(".modal").modal('hide');
										var note = '<table>';
											note+= '<tr>';
												note+= '<td colspan="2"><strong>{{ trans("auction.success_bid") }}</strong></td>';
												note+= '</tr>';
												note+= '<tr><td colspan="2" style="border-bottom:1px solid"></td>';
												note+= '</tr>';
												note+= '<tr>';
												note+= '<td>{{ trans("auction.success_bid_product") }}</td>';
												note+= '<td>'+data.product+'</td>';
												note+= '</tr>';
												note+= '<tr>';
												note+= '<td>{{ trans("auction.success_bid_price") }}</td>';
												note+= '<td>$ '+data.price+'</td>';
												note+= '</tr>';
												note+= '<tr>';
												note+= '<td>{{ trans("auction.success_bid_amount") }}</td>';
												note+= '<td>'+data.amount+ ' ' + data.unit  + '</td>';
												note+= '</tr>';
												note+= '<tr><td colspan="2" style="border-bottom:1px solid"></td>';
												note+= '</tr>';
												note+= '<tr>';
												note+= '<td><strong>{{ trans("auction.success_bid_total") }}</strong></td>';
												note+= '<td><strong>$ '+(data.price * data.amount)+'</strong></td>';
												note+= '</tr>';
											note+= '</tr>';
										note+='<table>';
										$(".bid-button-act").attr("disabled",true);
										showBill(note);
								}else{
									var note = '';
									
										note+= '<div class="alert alert-danger alert-dismissible" role="alert">';
													note+= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
													note+= 'Solo quedan disponibles ' + data.availability + ' ' + data.unit + ' de ' + data.product ;
										note+= '</div>';
										
										$(".content-danger-" +auctionId ).html(note);
										$("#amount-bid-" +auctionId).val(data.availability);
										$("#amount-bid-" +auctionId).attr('max',data.availability);
										$(".s-disponible-" +auctionId).html(data.availability);
										
										var price = $(".hid-currentPrice-"+auctionId).val();
										var total = price * data.availability;
										$(".modal-total-"+auctionId).html('Total $' + total.toFixed(2) )
										
										if (data.availability == 0)
										{
											$("#amount-bid-" +auctionId).attr('disabled',true);
											$(".mak-bid-"+auctionId).hide();
										}
										
								}
							}
						  }
				});
		}else{
			
			var note = '';
			note+= '<div class="alert alert-danger alert-dismissible" role="alert">';
			note+= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			note+= 'Solo quedan disponibles ' + cDispo + ' ' + $(".modal-unit-"+auctionId).html() ;
			note+= '</div>';
			$(".content-danger-" +auctionId ).html(note);
			$("#amount-bid-" +auctionId).val(cDispo);
			$("#amount-bid-" +auctionId).attr('max',cDispo);
			
			if (cDispo == 0)
			{
				$("#amount-bid-" +auctionId).attr('disabled',true);
				$(".mak-bid-"+auctionId).hide();
			}
			
		
		}
	}

	</script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/star_rating/jquery.raty.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/datetimepicker/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/jasny/jasny-bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/plugins/chosen/chosen.css') }}" >
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
            font-size: 34px;
            width: 159px;
            margin-right: 0px;
            margin-top: 12px;
            display: inline-block;
			margin-left: -21px;

        }
		
		.product-p {  }
		
        .divInterval{
            display: inline-block;
        }
        .timerDiv .more{
            margin-top:20px;
        }
    </style>
@endsection

