<div class="row" style="margin-top: 10px">
    <?php use Carbon\Carbon;
    $fechaActual = date('Y-m-d H:i:s');
    $fechaInicial = $a->start;
    $fechaFinal = $a->end;
    $segundos = strtotime($fechaFinal) - strtotime($fechaActual);
    $segFinal = strtotime($fechaFinal) - strtotime($fechaInicial);?>
    @if($status == \App\Auction::IN_CURSE or $status == \App\Auction::MY_IN_CURSE)
    <div class="col-md-6">
        <label for="">{{ trans('auction.completion') }}</label><br>
        <label for="">{{ Carbon::parse($a->end)->format('H:i:s d/m/Y') }}</label>
    </div>
    <div class="col-md-6 timerDiv text-left">
        @if($status == \App\Auction::IN_CURSE or $status == \App\Auction::MY_IN_CURSE)
            @if ($a->getAuctionLeftTimeInMinutes() <= env('AUCTION_GRAPH_MAX_MINUTES',360))
            <input type="text"
                   class        = "dial dialLeft m-r"
                   {{--value        = "{{ $a->getAuctionLeftTimeInMinutes() }}"--}}
                   {{--data-max     = "{{ env('AUCTION_GRAPH_MAX_MINUTES',360) }}"--}}
                   value="{{ $segundos }}"
                   data-max="{{ $segFinal }}"
                   data-min     = "0"
                   name         = "amount"
                   data-fgColor = "#1AB394"
                   data-width   = "70"
                   data-height  = "70"
                   data-angleArc= 250
                   data-angleOffset=-125
                   data-readOnly= true
                   readonly
                />
            @else
                <div class='more'>
                    {{ trans('auction.more_than',['hour'=>env('AUCTION_GRAPH_MAX_MINUTES',360)/60]) }}
                </div>
            @endif
        @endif
    </div>
    @elseif($status == \App\Auction::FINISHED or $status == \App\Auction::MY_FINISHED)
            <div class="col-md-6">
                <label for="">{{ trans('auction.finished') }}</label><br>
                <label for="">{{ Carbon::parse($a->end)->format('H:i:s d/m/Y') }}</label>
            </div>
            <div class="col-md-6">
            </div>
    @else
        <div class="col-md-6">
            <label for="">{{ trans('auction.start') }}</label><br>
            <label for="">{{ Carbon::parse($a->start)->format('H:i:s d/m/Y') }}</label>
        </div>
        <div class="col-md-6">
        </div>
    @endif
</div>