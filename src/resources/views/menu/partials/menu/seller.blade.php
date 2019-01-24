    <li>
        <a href="{{ url('/') }}"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="{{ url('/home') }}"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    <li @if (Request::is('sellerboat*')) class="active" @endif  @if(isset($approved) && !$approved) class="disabled" @endif>
        <a   href="{{ url('/sellerboat') }}"><em class="fa fa-ship"></em> <span class="nav-label">{{ trans('sellerBoats.title') }}</span></a>
    </li>
    <li @if (Request::is('sellerbatch*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif>
        <a  href="{{ url('/sellerbatch') }}"><em class="fa fa-database"></em> <span class="nav-label">{{ trans('sellerBoats.listbatch') }}</span></a>
    </li>
    <li @if (Request::is('sellerAuction*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  href="{{ url('/sellerAuction') }}"><em class="fa fa-usd"></em> <span class="nav-label">Mis Subastas</span></a>
    </li>
    <li @if (Request::is('sales*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a href="{{ url('/sales') }}"><em class="fa fa-money"></em><span class="nav-label">Ventas de Subastas</span></a>
    </li>
	<li @if (Request::is('privatesales*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  href="{{ url('/privatesales') }}"><em class="fa fa-money"></em> <span class="nav-label">Ventas Privadas</span></a>
    </li>
