<div class="col-lg-12 text-center">
    <a href="{{ url('sellerAuction?status='.\App\Constants::MY_IN_CURSE) }}" class="btn @if($status == \App\Constants::MY_IN_CURSE) btn-success @endif">{{ trans('auction.auction_in_curse') }}</a>
    <a href="{{ url('sellerAuction?status='.\App\Constants::MY_FUTURE) }}" class="btn @if($status == \App\Constants::MY_FUTURE) btn-success @endif">{{ trans('auction.auction_future') }}</a>
    <a href="{{ url('sellerAuction?status='.\App\Constants::MY_FINISHED) }}" class="btn @if($status == \App\Constants::MY_FINISHED) btn-success @endif">{{ trans('auction.auction_finished') }}</a>
</div>