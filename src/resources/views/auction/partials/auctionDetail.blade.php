@can('viewOperations',$a)
	@if($a->type == App\Auction::AUCTION_PRIVATE)
	<div class="row" style="margin-top: 5px;margin-left:10px;">
	@else
	<div class="row" style="margin-top: 22px;margin-left:10px;">
	@endif
		<div class="">
			@if($a->type == App\Auction::AUCTION_PRIVATE)
				<a href="#" data-target="#ver_participantes" auction="{{ $a->id }}" class="btn btn-success ver_participantes">Ver participantes</a>
			@endif
			<a href="{{ route('auction.operations',$a) }}" class="btn btn-info">{{ trans('auction.view_operations') }}</a>
			@if(isset($sellerAuction) and $sellerAuction==true and $status == \App\Auction::MY_FINISHED )
				<a href="{{ route('auction.export',$a) }}" target="_blank" class="btn btn-success">Exportar</a>
			@endif
		</div>
	</div>
@endcan

   