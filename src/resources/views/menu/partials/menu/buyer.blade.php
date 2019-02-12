    <li>
        <a href="/"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="/home"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    <li @if (Request::is('auction*') && Request::input('type') != \App\Constants::AUCTION_PRIVATE ) class="active" @endif >
        <a  href="/auction" ><em class="fa fa-usd"></em> <span class="nav-label">{{ trans('auction.auction_list') }}</span></a>
    </li>
	<li @if (Request::input('type') == \App\Constants::AUCTION_PRIVATE ) class="active" @endif >
        <a  href="/auction?type=private" ><em class="fa fa-lock"></em> <span class="nav-label">{{ trans('auction.auction_private') }}</span></a>
    </li>
	
	<li @if (Request::is('bids*')) class="active" @endif>
        <a  href="/bids"><em class="fa fa-shopping-cart"></em> <span class="nav-label">{{ trans('auction.bids') }}</span></a>
    </li>

