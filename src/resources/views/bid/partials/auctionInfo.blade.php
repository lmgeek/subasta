<div class="row">
    <div class="col-md-12" style="margin-top: 5px">
        <div class="row" >
			
            <div class="col-md-12 text-center"><label>{{ $a->auction->batch->product->name }} {{ trans('general.product_caliber.'.$a->auction->batch->caliber) }}</label></div>
        </div>
        <div class="row" >
            <div class="col-md-12 text-center"><div data-score="{{ $a->auction->batch->quality }}" class="quality text-warning" style="font-size: 8px; display: inline-block;"></div></div>
        </div>
        <div class="row" >
            @if(isset($sellerAuction) and $sellerAuction==true)
                <div class="col-md-12 text-center"><label>{{ $a->auction->batch->arrive->boat->name }}</label></div>
            @else
                <div class="col-md-12 text-center"><label>{{ $a->auction->batch->arrive->boat->user->name }} <br> {{ $a->auction->batch->arrive->boat->name }}</label></div>
            @endif
        </div>
    </div>
</div>