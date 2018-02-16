@can('viewOperations',$a)
	<div class="row" style="margin-top: 22px;margin-left:10px;">
			<div class="">
				<a href="{{ route('auction.operations',$a) }}" class="btn btn-info">{{ trans('auction.view_operations') }}</a>
				@if(isset($sellerAuction) and $sellerAuction==true and $status == \App\Auction::MY_FINISHED )
					<a href="{{ route('auction.export',$a) }}" target="_blank" class="btn btn-success">Exportar</a>
				@endif
			</div>
	</div>
@endcan

   