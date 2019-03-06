@extends('admin')

@section('content')
	<?php
	use Carbon\Carbon;
	use \App\Http\Controllers\AuctionController;
	?>
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
						@can('canBid', \App\Constants::class)

						@else
							<div class="alert alert-warning">
								<strong> {{ trans('auction.bid_limit')  }}  </strong>
							</div>
						@endcan
					@endif
					@if (\Session::has('success'))
						<div class="alert alert-success">
							<ul>
								<li>{!! \Session::get('success') !!}</li>
							</ul>
						</div>
					@endif
					<div class="ibox-title">
						<h5>{{ trans('auction.auctions') }}</h5>

						{{--<div onLoad="mostrar_hora()">--}}
						{{--<div id="fecha">--}}
						{{--<span id="hora"></span> horas, <span id="minuto"></span> minutos,  <span id="segundo"></span> segundos--}}
						{{--</div>--}}
						{{--</div>--}}

					</div>
					<div class="ibox-content">
						@if (count($auctions) == 0)
							<div class="text-center">
								{{ trans('auction.no_auction') }}
							</div>
						@else
							@foreach ($auctions as $a)
								<div class="auction row">
									<div class="col-md-2" style="margin-bottom: 5px;">
										@include('auction.partials.auctionInfo')
									</div>
									<div class="col-md-3 text-center">
										@include('auction.partials.auctionPrice')
									</div>
									<div class="col-md-2">
										@include('auction.partials.auctionAvailability')
									</div>
									<div class="col-md-3">
										@include('auction.partials.auctionTime')
									</div>
									<div class="col-md-2">
										@if(isset($sellerAuction) and $sellerAuction==true)
											@if($status == \App\Constants::MY_IN_CURSE or $status == \App\Constants::MY_FINISHED)
												@include('auction.partials.auctionDetail')
											@else
												<br>
											@endif

											@if( $status != \App\Constants::MY_FINISHED )
												<div class="col-md-2">
													@if($a->active == \App\Constants::ACTIVE)
														<a class="btn btn-action cancelAuction" href="{{ route('auction.deactivate',$a) }}">{{trans('auction.btnauction_cancel')}}</a>
														@if($status == \App\Constants::MY_FUTURE )
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
											@if($a->active == \App\Constants::ACTIVE)
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
			<div class="modal  fade modal-product" id="ver_participantes" tabindex="-1" role="dialog"  aria-hidden="true">
				<div class="modal-dialog ">
					<div class="modal-content">
						<div class="modal-header">

							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button><h3 class="text-center">Lista de invitados</h3>
						</div>
						<div class="modal-body text-center">
							<div class="row">
								<div class="col-md-12 text-center">
									<center>
										<ul class="list-group" id="participantes">
											<li class="list-group-item">Felix</li>
											<li class="list-group-item">Ale</li>
											<li class="list-group-item">Luis</li>
										</ul>
									</center>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="https://unpkg.com/currency.js@1.1.4/dist/currency.min.js"></script>
	<script src="/js/plugins/moment/moment.js"></script>
	<script src="/js/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
	<script src="/js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
	<script src="/js/plugins/star_rating/jquery.raty.js"></script>
	<script src="/js/plugins/jasny/jasny-bootstrap.min.js"></script>
	<script src="/js/plugins/toastr/toastr.min.js"></script>
	<script src="/js/plugins/jsKnob/jquery.knob.js"></script>
	<script src="/js/plugins/chosen/chosen.jquery.js"></script>
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
				$.get('calculateprice?i=c&auction_id=' + auctionId, function (data) {
					$data = JSON.parse(data);
					var available = $data['available'];
					console.log(available);

					if (value > available)
						var total = 0;
					else
						var total = currency(price).multiply(value);

					$(".modal-total-"+auctionId).html('Total $' + currency(total,{ separator: ".",decimal: ","}).format() )
				});
				// var total =   price..replace(/[aiou]/gi,'e')
			});

		});


		function calculatePrice(auctionId)
		{
			var now = new Date().toLocaleTimeString();
			$.get('calculateprice?i=c&auction_id=' + auctionId, function (data) {
				$data = JSON.parse(data);
				// var price = currency(parseFloat($data['price']),{separator: '.',decimal: ","}).format();
				var price = $data['price'];
				console.log(price);
				$(".currentPrice-"+auctionId).html('$' + price);
				$(".hid-currentPrice-"+auctionId).val($data['price']);
				$(".currentAvailability-"+auctionId).html($data['available']);
				$(".hid-currentAvailability-"+auctionId).val($data['price']);
				if($('#bid-Modal-'+auctionId).is(':visible')){
					var total = ( $("#amount-bid-" + auctionId ).val()  * $data['price']);
					// $(".modal-total-"+auctionId).html('Total $' + total.toFixed(2) );
					$(".modal-total-"+auctionId).html('Total $' + currency(total,{ separator: ".",decimal: ","}).format() )
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
			$('.timerauction').each(function(){
				timer($(this).data('id'))
			});
			$(".ver_participantes").click(function (e) {
				var lista_participantes = $("ul#participantes");
				lista_participantes.html("");
				var id = $(this).attr("data-target");
				var auction = $(this).attr("auction");
				$.ajax({
					type: "POST",
					url: "/get/participantes",
					data:{auction:auction, _token: "{{ csrf_token() }}"},
					success: function (data) {
                        console.log(data);
						$.each(data,function(i,val){
							lista_participantes.append(
									`<li class="list-group-item">${val.name} ${val.lastname}</li>`
							);
						});
						$(id).modal('show');
					}
				});
			});


			$('.chosen-select').chosen({width:"100%"});



			$(".cancelAuction").click(function(){
				if(!confirm('¿Está seguro que quiere cancelar la subasta?')){
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
// console.log(valores);
					if (nuevoValor == 0){
						nuevoValor = parseInt($(v).data('max'));
						var auctionId = $(v).attr('auctionId');
						var active = $(v).attr('active');
						if (active==1){
							calculatePrice(auctionId);
						}
						// console.log('contador'+nuevoValor);
					}
					$(v).val(nuevoValor).trigger("change");
				});
			}, 1000);

			// setInterval(function(){
			//     $('.dialLeft').each(function(k,v){
			//         valores = $(v).val().split(':');
			//
			//         if (valores.length == 3){
			// 			nuevoValor = (valores[0]*3600)+(valores[1]*60)+(valores[2]-1);
			//         }else{
			//             nuevoValor = valores[0]-1;
			//         }
			//         // console.log('dial.Left: ' + nuevoValor);
			//         if (nuevoValor == 0){
			// 			var auctionId = $(this).attr('auctionId');
			// 			// var url = 'auction/autofersbid/' + auctionId;
			// 			var url = 'auction/offers/' + auctionId;
			// 			$.get(url, function (data) {
			// 				console.log(data);
			// 			});
			// 			$(this).parent().parent().parent().parent().parent().fadeOut(400,function(){
			// 				// on Animation Complete remove object from DOM
			// 				$(this).remove();
			// 			});
			//
			// 		}
			//         $(v).val(nuevoValor).trigger("change");
			//     });
			// }, 1000);
			//
			// $(".dialLeft").knob({
			// 	'format' : function (value) {
			// 		var h = Math.floor(value/3600);
			// 		if (h<10)
			// 			h = "0"+h;
			// 		var m = Math.floor((value-(h*3600))/60);
			// 		if (m<10)
			// 			m = "0"+m;
			// 		var s = value%60;
			// 		return h+":"+m+":"+s;
			//
			// 	}
			// });
			// $(".dialLeft").knob({
			//     'format' : function (value) {
			//         var h = Math.floor(value/60);
			//         var m = value%60;
			//
			//         if (h<10)
			//             h = "0"+h;
			//
			//         if (m<10)
			//             m = "0"+m;
			//
			//         return h+":"+m;
			//     }
			// });

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

		$(document).on("keypress",".amount-bid-modal",function(e){
			var x = e.keyCode || e.which;
			// console.log(x);
			if (x == 45 || x == 46 || x == 44 || x == 101){
				return false;
			}

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
								note+= 'Sólo quedan disponibles ' + data.availability + ' ' + data.unit + ' de ' + data.product ;
								note+= '</div>';

								$(".content-danger-" +auctionId ).html(note);
								$("#amount-bid-" +auctionId).val(data.availability);
								$("#amount-bid-" +auctionId).attr('max',data.availability);
								$(".s-disponible-" +auctionId).html(data.availability);

								var price = $(".hid-currentPrice-"+auctionId).val();
								var total = price * data.availability;
								// $(".modal-total-"+auctionId).html('Total $' + total.toFixed(2) )
								$(".modal-total-"+auctionId).html('Total $' + currency(total,{ separator: ".",decimal: ","}).format() )

								if (data.availability < 0)
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
				note+= 'Sólo quedan disponibles ' + cDispo + ' ' + $(".modal-unit-"+auctionId).html() ;
				note+= '</div>';
				$(".content-danger-" +auctionId ).html(note);
				$("#amount-bid-" +auctionId).val(cDispo);
				$("#amount-bid-" +auctionId).attr('max',cDispo);

				var price1 = $(".hid-currentPrice-"+auctionId).val();
				var total1 = price1 * cDispo;
				// $(".modal-total-"+auctionId).html('Total $' + total1.toFixed(2) )
				$(".modal-total-"+auctionId).html('Total $' + currency(total1,{ separator: ".",decimal: ","}).format() )

				if (cDispo < 0)
				{
					$("#amount-bid-" +auctionId).attr('disabled',true);
					$(".mak-bid-"+auctionId).hide();
				}


			}
		}

		function timer($id) {
			if ($("#timer" + $id).attr('data-timefin') != null) {
				var $end = new Date($("#timer" + $id).attr('data-timefin'));
				window['now'] = new Date().getTime();
				var url = 'current-time';
				// console.log(url);
				$.get(url, function (data) {
					// console.log(data);
				});
				var countDownDate = new Date($("#" + $id).attr('data-timefin')).getTime();
				var distance = $end - window['now'], string = '';
				var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);
				if (days != 0) {
					string += days + 'd ';
				}
				if (hours != 0 || days != 0) {
					string += hours + 'h ';
				}
				if (minutes != 0 || hours != 0 || days != 0) {
					string += minutes + 'm ';
				}
				string += seconds + 's';
				$('#timer'+$id).html(string);
				if (distance < 0) {
					console.log(distance);
					var url = 'auction/offers/' + $id;
					console.log(url);
					$.get(url, function (data) {
					});
					$("#timer" + $id).parent().parent().parent().parent().fadeOut(400,function(){
						// on Animation Complete remove object from DOM
						$(this).remove();
					});
				} else {
					setTimeout(function () {
						timer($id);
					}, 1000);
				}
			}
		}

	</script>
@endsection

@section('stylesheets')
	<link rel="stylesheet" href="/css/plugins/star_rating/jquery.raty.css">
	<link rel="stylesheet" href="/css/plugins/datetimepicker/bootstrap-datetimepicker.css">
	<link rel="stylesheet" href="/css/plugins/jasny/jasny-bootstrap.min.css">
	<link rel="stylesheet" href="/css/plugins/chosen/chosen.css" >
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

<?php //$today = date('H:m:s'); echo "<pre>"; var_dump('Fecha/hora actual: ', date('Y-m-d h:i:s', time())); echo "</pre>"?>

