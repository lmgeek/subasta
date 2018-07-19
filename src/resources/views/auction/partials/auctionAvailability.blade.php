<div class="row">
    <div class="col-md-12 text-center" style="margin-top:15px">
        <?php

        $vendido = 0;
        foreach ($a->bids()->where('status','<>',\App\Bid::NO_CONCRETADA)->get() as $b) {
            $vendido+= $b->amount;
        }

        $total = $a->amount;
        $disponible = $total-$vendido;
        ?>
		@if ($disponible > 0)
				@if($status == \App\Auction::FUTURE)
					<strong>{{ trans('auction.aviability') }} {{$total}}</strong>
					<div class="progress">
						<div style="width: 100%;" class="progress-bar"></div>
					</div>
				@else
					<strong>{{ trans('auction.aviability') }} {{$disponible}}/{{$total}}</strong>
					<div class="progress">
						<div style="width: {{($disponible*100)/$total}}%;" class="progress-bar"></div>
					</div>
				@endif
		@else
			<div class=" font-bold text-danger">{{ trans('auction.no_stock') }} </div>
		@endif
    </div>
</div>