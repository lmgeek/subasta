    <li @if (Request::is('home*')) class="active" @endif>
        <a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('auction*') && Request::input('type') != \App\Auction::AUCTION_PRIVATE ) class="active" @endif>
        <a href="{{ url('/auction') }}"><i class="fa fa-usd"></i> <span class="nav-label">{{ trans('auction.auction_list') }}</span></a>
    </li>
	<li @if (Request::input('type') == \App\Auction::AUCTION_PRIVATE ) class="active" @endif>
        <a href="{{ url('/auction?type=' . \App\Auction::AUCTION_PRIVATE) }}"><i class="fa fa-lock"></i> <span class="nav-label">{{ trans('auction.auction_private') }}</span></a>
    </li>
	
	<li @if (Request::is('bids*')) class="active" @endif>
        <a href="{{ url('/bids') }}"><i class="fa fa-shopping-cart"></i> <span class="nav-label">{{ trans('auction.bids') }}</span></a>
    </li>

