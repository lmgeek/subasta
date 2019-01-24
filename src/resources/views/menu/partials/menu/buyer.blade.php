    <li>
        <a href="{{ url('/') }}"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="{{ url('/home') }}"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    <li @if (Request::is('auction*') && Request::input('type') != \App\Auction::AUCTION_PRIVATE ) class="active" @endif >
        <a  href="{{ url('/auction') }}" ><em class="fa fa-usd"></em> <span class="nav-label">{{ trans('auction.auction_list') }}</span></a>
    </li>
	<li @if (Request::input('type') == \App\Auction::AUCTION_PRIVATE ) class="active" @endif >
        <a  href="{{ url('/auction?type=' . \App\Auction::AUCTION_PRIVATE) }}" ><em class="fa fa-lock"></em> <span class="nav-label">{{ trans('auction.auction_private') }}</span></a>
    </li>
	
	<li @if (Request::is('bids*')) class="active" @endif>
        <a  href="{{ url('/bids') }}"><em class="fa fa-shopping-cart"></em> <span class="nav-label">{{ trans('auction.bids') }}</span></a>
    </li>

