<?php $batches =  $lastArrive->batch  ?>
@if(count($batches) > 0)
	<script src="/js/plugins/star_rating/jquery.raty.js"></script>
	<link rel="stylesheet" href="/css/plugins/star_rating/jquery.raty.css">
	<table class="table table-hover">
	  <tbody>	
			<thead>
			<tr>
				<td><strong>{{ trans("sellerBoats.product") }}</strong></td>
				<td><strong>{{ trans("sellerBoats.total") }}</strong></td>
				<td><strong>{{ trans("sellerBoats.sales") }}</strong></td>
				<td><strong>{{ trans("sellerBoats.assigned_auction") }}</strong></td>
				<td><strong>{{ trans("sellerBoats.type_sales") }}</strong></td>
				<td></td>
			</tr>
			</thead>
	@foreach($batches as $batch)
		<?php
			$assigned_auction = $batch->status->assigned_auction;
			$auction_sold = $batch->status->auction_sold;
			$private_sold = $batch->status->private_sold;											
			$remainder = $batch->status->remainder;
			
			$total_sold = $auction_sold  + $private_sold;
			$total_unsold = $assigned_auction + $remainder;
			
			$total_sold_unsold = $total_sold + $total_unsold ;
			
			
		?>
			 <tr>
				<td>
					<div id="estrellas_{{ $batch->id }}" style="display:none"><div><strong>{{ trans('sellerBoats.caliber') }}: </strong><span>{{ trans('general.product_caliber.'.$batch->caliber) }}</span><br><strong>{{ trans('sellerBoats.quality') }}: </strong><div id='quality_{{ $batch->id }}' class='text-warning' style='font-size: 8px; display: inline-block;'></div></div></div>

					{{ $batch->product->name  }}
					<em class="fa fa-info-circle text-info" id="indo_{{ $batch->id }}" data-toggle="tooltip" data-placement="top" title="dddd"></em>
					<script>
						$(document).ready(function(){
							$('#quality_{{ $batch->id }}').raty({
								readOnly: true,
								score: {{ $batch->quality }},
								starType: 'i',
								hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
							});

							$('#indo_{{ $batch->id }}').attr('title',$('#estrellas_{{ $batch->id }}').html());

							$('#indo_{{ $batch->id }}').tooltip({
								html:true
							});
						});
					</script>
				</td>
				<td> {{ $total_sold_unsold  }} </td>
				<td>
					<div>
						<strong>{{ trans("sellerBoats.solds") }} :</strong> {{$total_sold}}
						<div class="" style="width:94px;">
							<div data-toggle="tooltip" data-placement="right" class="progress progress-mini" title="<?php echo ($total_sold/$total_sold_unsold)*100 ?>%" style="background-color:#ddd;height:8px">
								<div  style="height:8px;width: <?php echo ($total_sold/$total_sold_unsold)*100 ?>%;" title="Vendidos: {{$total_sold}}" class="progress-bar"></div>
							</div>
							
						</div>
					</div>
				</td>
				<td>
					<table>
						<tr>
							<td>
								<strong style="color:#2980b9">{{ trans("sellerBoats.assigned")  }} &nbsp:</strong> {{$assigned_auction}}
							</td>
							<td rowspan=2>
								<div class="" style="width:30px; margin-left:5px">
									<span class="total-assigned-auction-and-unassigned-{{$batch->id}}"></span>
								</div>
							</td>
						</tr>
						<tr >
							<td>
								<strong style="color:#a7b1c2">{{ trans("sellerBoats.unassigned")  }} : </strong> {{$remainder}}	
							</td>
						</tr>
					</table>
					<script>
						$(".total-assigned-auction-and-unassigned-{{$batch->id}}").sparkline([<?php echo $assigned_auction ?>, <?php echo $remainder ?>], {
							type: 'pie',
							//tooltipFormat: '@{{offset:offset}} @{{value}}',
							tooltipValueLookups: {
								'offset': {
									0: '{{trans("sellerBoats.assigned_auction")}}',
									1: '{{trans("sellerBoats.unassigned_auction")}}'
								}
							},
							height: '25px',
							sliceColors: ['#2980b9', '#a7b1c2']});
					</script>
				</td>
				
				<td>
					<table>
						<tr>
							<td>
								<strong style="color:#8e44ad">{{ trans("sellerBoats.auctions")  }} :</strong> {{$auction_sold}}
							</td>
							<td rowspan=2>
								<div class="" style="width:30px; margin-left:5px">
									<span class="total-sold-auction-and-private-sale-{{$batch->id}}"></span>
								</div>
							</td>
							
						</tr>
						<tr >
							<td>
								<strong style="color:#16a085">{{ trans("sellerBoats.private")  }} &nbsp: </strong> {{$private_sold}}	
							</td>
						</tr>
					</table>
					<script>
						$(".total-sold-auction-and-private-sale-{{$batch->id}}").sparkline([<?php echo $auction_sold ?>, <?php echo $private_sold ?>], {
							type: 'pie',
							//tooltipFormat: '@{{offset:offset}} @{{value}}',
							tooltipValueLookups: {
								'offset': {
									0: '{{trans("sellerBoats.sold_at_auction")}}',
									1: '{{trans("sellerBoats.sold_at_private_sale")}}'
								}
							},
							height: '25px',
							sliceColors: ['#8e44ad', '#16a085']});
					</script>
				</td>
				 <td>
					 @if($remainder>0)
					 	<a href="{{ route('auction.create_from_batch',$batch) }}" class="btn btn-action">Crear subasta</a>
					 @endif
				 </td>
			 </tr>
	@endforeach
		<tr>
			<td colspan="6"></td>
		</tr>  
	  </tbody>
	 </table>
	 <script>
		$(function () {
				  $('[data-toggle="tooltip"]').tooltip()
				})
	 </script>
@endif

<style>
	.tooltip-inner {
		white-space:pre-wrap;
	}
</style>
