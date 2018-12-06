    <li>
        <a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    <li @if (Request::is('sellerboat*')) class="active" @endif  @if(isset($approved) && !$approved) class="disabled" @endif>
        <a   href="{{ url('/sellerboat') }}"><i class="fa fa-ship"></i> <span class="nav-label">{{ trans('sellerBoats.title') }}</span></a>
    </li>
    <li @if (Request::is('sellerbatch*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif>
        <a  href="{{ url('/sellerbatch') }}"><i class="fa fa-database"></i> <span class="nav-label">{{ trans('sellerBoats.listbatch') }}</span></a>
    </li>
    <li @if (Request::is('sellerAuction*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  href="{{ url('/sellerAuction') }}"><i class="fa fa-usd"></i> <span class="nav-label">Mis Subastas</span></a>
    </li>
    <li @if (Request::is('sales*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a href="{{ url('/sales') }}"><i class="fa fa-money"></i><span class="nav-label">Ventas de Subastas</span></a>
    </li>
	<li @if (Request::is('privatesales*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  href="{{ url('/privatesales') }}"><i class="fa fa-money"></i> <span class="nav-label">Ventas Privadas</span></a>
    </li>
