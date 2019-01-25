<div class="row">
    <div class="col-md-12" style="margin-top: 5px">
        <div class="row" >
			<input type="hidden" class="auctionIds" value="{{ $a->id }}" />
            <div class="col-md-12 text-center">
				<label>
					{{ $a->batch->product->name }} {{ trans('general.product_caliber.'.$a->batch->caliber) }}
				</label>
			</div>
        </div>
        <div class="row" >
            <div class="col-md-12 text-center">
				<div data-score="{{ $a->batch->quality }}" class="quality text-warning" style="font-size: 8px; display: inline-block;">
				</div>
			</div>
        </div>
        <div class="row" >
            @if(isset($sellerAuction) && $sellerAuction==true)
                <div class="col-md-12 text-center"><label>{{ $a->batch->arrive->boat->name }}</label></div>
            @else
                <div class="col-md-12 text-center"><label>{{ $a->batch->arrive->boat->user->name }} 
				<?php $userId = $a->batch->arrive->boat->user->id ?>
				@if ($userRating[$userId] > 0)
					<em data-toggle="tooltip" data-placement="top" title="{{ $userRating[$userId]  }}% {{trans('users.reputability.seller')}}" class="fa fa-info-circle" ></em>
				@endif
				<br> {{ $a->batch->arrive->boat->name }}</label></div>
            @endif
			
			@if (!is_null($a->batch->product->image_name) && file_exists('img/products/'.$a->batch->product->image_name) )
				<center>
					<a href="#" data-target="#product-Modal-{{ $a->id }}"  data-toggle="modal" class="product-img" auction="{{ $a->id }}" >
						<em data-toggle="tooltip" title="Imagen del producto" class="fa fa-image">
						</em>
					</a>
				</center>
				<div class="col-md-12 text-center">
					{{ trans("auction.".$a->type) }}
				</div>
				<div class="modal  fade modal-product" id="product-Modal-{{ $a->id }}" tabindex="-1" role="dialog"  aria-hidden="true">
					<div class="modal-dialog ">
						<div class="modal-content">
						   <div class="modal-header">
							
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button><h3><center>{{ $a->batch->product->name }}</center></h3>
						   </div>
						  <div class="modal-body text-center">
							  <div class="row">
								<div class="col-md-12 text-center">
									<center><img class="img-responsive" src="{{ asset('/img/products/'.$a->batch->product->image_name) }}" style="border-radius:6px" alt="{{$a->batch->product->name}}"  ></center>
								</div>
							  </div>
						  </div>
						  
						</div>
					</div>
				</div>
			@endif
        </div>
		
    </div>
</div>