@can('viewOperations',$a)
	@if($a->type == App\Constants::AUCTION_PRIVATE)
	<div class="row" style="margin-top: 5px;margin-left:10px;">
	@else
	<div class="row" style="margin-top: 22px;margin-left:10px;">
	@endif
		<div class="">
			<a href="{{ route('auction.operations',$a) }}" class="btn btn-info">{{ trans('auction.view_operations') }}</a>
			<a href="{{ route('auction.offers',$a) }}" class="btn btn-info">ofertas</a>
			@if($a->type == App\Constants::AUCTION_PRIVATE)
				<a href="#" data-target="#ver_participantes" auction="{{ $a->id }}" class="btn btn-success ver_participantes">Ver invitados</a>
			@endif
		@if(isset($sellerAuction) and $sellerAuction==true and $status == \App\Constants::MY_FINISHED )
				<a href="{{ route('auction.export',$a) }}" target="_blank" class="btn btn-success">Exportar</a>
			@endif
		</div>
	</div>
@endcan

   