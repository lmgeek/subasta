<div class="col-lg-12 text-center">
    <a href="{{ url('sellerAuction?status='.\App\Auction::MY_IN_CURSE) }}" class="btn @if($status == \App\Auction::MY_IN_CURSE) btn-success @endif">{{ trans('auction.auction_in_curse') }}</a>
    <a href="{{ url('sellerAuction?status='.\App\Auction::MY_FUTURE) }}" class="btn @if($status == \App\Auction::MY_FUTURE) btn-success @endif">{{ trans('auction.auction_future') }}</a>
    <a href="{{ url('sellerAuction?status='.\App\Auction::MY_FINISHED) }}" class="btn @if($status == \App\Auction::MY_FINISHED) btn-success @endif">{{ trans('auction.auction_finished') }}</a>
</div>