<div class="row">
    <div class="col-md-12" style="margin-top: 5px">
        <div class="row">
			<div class="col-md-12 ">
				@if (!is_null($a->batch->product->image_name) and file_exists('img/products/'.$a->batch->product->image_name) )
					<img src="/img/products/{{$a->batch->product->image_name}}" style="max-width: 80px;max-height: 80px" alt="">
				@endif
			</div>
		</div>
		<div class="row" >
			{{-- <input type="hidden" class="auctionIds" value="{{ $a->id }}" /> --}}
            <div class="col-md-12 text-center"><label>{{ $a->batch->product->name }} {{ trans('general.product_caliber.'.$a->batch->caliber) }}</label></div>
        </div>
        <div class="row" >
            <div class="col-md-12 text-center"><div data-score="{{ $a->batch->quality }}" class="quality text-warning" style="font-size: 8px; display: inline-block;"></div></div>
        </div>
        <div class="row" >
            @if(isset($sellerAuction) and $sellerAuction==true)
                <div class="col-md-12 text-center"><label>{{ $a->batch->arrive->boat->name }}</label></div>
            @else
                <div class="col-md-12 text-center"><label>{{ $a->batch->arrive->boat->user->name }} <br> {{ $a->batch->arrive->boat->name }}</label></div>
            @endif
        </div>
    </div>
</div>