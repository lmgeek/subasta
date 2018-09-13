<?php
	$vendido = 0;
	foreach ($a->bids()->where('status','<>',\App\Bid::NO_CONCRETADA)->get() as $b) {
		$vendido+= $b->amount;
	}

	$total = $a->amount;
	$disponible = $total-$vendido;
						
?>
<div class="row" style="margin-top: 22px;margin-left:10px;">
		 @can('canBid', \App\Auction::class)
		 <div class="">
			@if ($disponible > 0 and $status != \App\Auction::FUTURE)

				<button type="submit" class="btn btn-primary bid-button-act "  data-target="#bid-Modal-{{ $a->id }}" data-toggle="modal">{{ trans('auction.action_bid') }}</button>
			@else


				@if( Auth::user()->isSuscribe($a) )
					<label class="btn btn-primary">Subscripto</label>
				@else
					<a href="{{ url('subscribe/'.$a->id) }}" class="btn btn-action">Suscribirse</a>
				@endif

			

			@endif
		</div>
		@endcan
		
    <div class="modal inmodal fade modal-bid" id="bid-Modal-{{ $a->id }}" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3>Comprar Producto</h3>
                </div>
                <div class="modal-body text-center">
					<div>
						@include('auction.partials.auctionInfoModal')


						<strong>Disponible: <span class="s-disponible-{{ $a->id }}">{{ $disponible }}  {{ trans('general.product_units.'.$a->batch->product->unit) }}</span> <br> Precio Unitario: <span class="currentPrice-{{ $a->id }}"></span></strong>
						<div class="row">
							<div class="col-md-12">
								<form action="" method="post" style="display: inline-block;">
									{{ csrf_field() }}
									<div class="row"><br>
										<div class="col-md-12">
											<div class="col-md-6" >
												<input type="number" style="width:110px" min="<?= ($disponible>0)?'1':'0' ?>" max="{{$disponible}}" placeholder="Cantidad"  auctionId="{{ $a->id }}" class="form-control bfh-number amount-bid amount-bid-modal" id="amount-bid-{{ $a->id }}" min="1" pattern="^[0-9]+"/>
											</div>
											<div class="col-md-4" style="margin-top: 7px;">
												<strong><span class="modal-unit-{{ $a->id }}">{{ trans('general.product_units.'.$a->batch->product->unit) }}</span> </strong>
											</div>
											<input type="hidden" value="" class="hid-currentPrice-{{ $a->id }}" />
										</div>

										<div class="col-md-12">
											<div class="priceText modal-total-{{ $a->id }} modal-price "> </div>
										</div>


									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="content-danger content-danger-{{ $a->id }}">

											</div>

										</div>
									</div>
								</form>
							</div>
						</div>
					</div>



                </div>
                <div class="modal-footer">
					<button type="button"  data-loading-text="Comprando..." auctionId="{{ $a->id }}" class=" noDblClick mak-bid-{{ $a->id }} make-bid btn btn-primary">{{ trans('auction.action_bid') }}</button>
                    <button type="button" class="btn btn-danger close-btn-bid" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
		
		
</div>

