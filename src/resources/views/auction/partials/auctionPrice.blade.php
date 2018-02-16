{{--precio abajo--}}
{{--<div class="col-md-3 text-center">--}}
    {{--<label for="">{{ trans('auction.next_price_update') }}</label><br>--}}
    {{--<input type="text"--}}
        {{--class="dial dialInterval m-r"--}}
        {{--value="50"--}}
        {{--data-max="60"--}}
        {{--data-rotation="anticlockwise"--}}
        {{--data-min="0"--}}
        {{--name="amount"--}}
        {{--data-fgColor="#1AB394"--}}
        {{--data-width="75"--}}
        {{--data-height="75"--}}
        {{--data-angleArc= 250--}}
        {{--data-angleOffset=-125--}}
        {{--data-readOnly= true--}}
        {{--readonly--}}
    {{--/>--}}
    {{--<div class="priceText">$ 150,46</div>--}}
{{--</div>--}}

<div class="row" style="margin-top: 5px">
    <div class="col-lg-6 col-xs-12">
        @if($status != \App\Auction::FINISHED and $status != \App\Auction::MY_FINISHED )
            <div class="priceText currentPrice-{{ $a->id }}">$ {{ $a->start_price }}</div>
        @else
            <div class="priceText currentPrice-{{ $a->id }}">$ {{ $a->end_price }}</div>
        @endif
    </div>
    @if( ($status == \App\Auction::IN_CURSE or $status == \App\Auction::MY_IN_CURSE) and $a->active == \App\Auction::ACTIVE)
        <div class="col-lg-6 col-xs-12" style="margin-top: 8px" title="{{ trans('auction.next_price_update') }}">
            <input type="text"
                   class="dial dialInterval m-r"
                   value="{{ $a->getTimeToNextInterval() }}"
                   data-max="{{ $a->interval * 60 }}"
                   {{--data-rotation="anticlockwise"--}}
                   data-min="0"
                   name="amount"
                   data-fgColor="#1AB394"
                   data-width="70"
                   data-height="70"
				   auctionId = "<?php echo $a->id ?>"
				   active = "<?php echo $a->active ?>"
                   data-angleArc= 250
                   data-angleOffset=-125
                   data-readOnly= true
                   readonly
                    />
        </div>
    @endif
</div>
