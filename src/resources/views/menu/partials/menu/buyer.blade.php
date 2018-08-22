    <li>
        <a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    <li @if (Request::is('auction*') && Request::input('type') != \App\Auction::AUCTION_PRIVATE ) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  @if(isset($approved) && $approved)="{{ url('/auction') }}" @endif ><i class="fa fa-usd"></i> <span class="nav-label">{{ trans('auction.auction_list') }}</span></a>
    </li>
	<li @if (Request::input('type') == \App\Auction::AUCTION_PRIVATE ) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  @if(isset($approved) && $approved) href="{{ url('/auction?type=' . \App\Auction::AUCTION_PRIVATE) }}" @endif><i class="fa fa-lock"></i> <span class="nav-label">{{ trans('auction.auction_private') }}</span></a>
    </li>
	
	<li @if (Request::is('bids*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif>
        <a  @if(isset($approved) && $approved) href="{{ url('/bids') }}" @endif><i class="fa fa-shopping-cart"></i> <span class="nav-label">{{ trans('auction.bids') }}</span></a>
    </li>

