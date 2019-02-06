
        <?php

        $vendido = 0;
        foreach ($a->bids()->where('status','<>',\App\Constants::NO_CONCRETADA)->get() as $b) {
            $vendido+= $b->amount;
        }

        $total = $a->amount;
        $disponible = $total-$vendido;
        ?>
		@if ($disponible > 0)
				@if($status == \App\Constants::FUTURE)
					<div class="progress">
						<div style="width: 100%;" class="progress-bar"></div>
					</div>
					{{$total}} {{ trans('auction.drawers') }}
				@else
					<div class="progress">
						<div style="width: {{($disponible*100)/$total}}%;" class="progress-bar"></div>
					</div>
					<span style="color: #7D7D7D;">{{$disponible}}/{{$total}} {{ trans('auction.drawers') }}</span>
				@endif
		@else
			<div class=" font-bold text-danger">{{ trans('auction.no_stock') }} </div>
		@endif
