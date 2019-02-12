    <li>
        <a href="/"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="/home"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    <li @if (Request::is('sellerboat*')) class="active" @endif  @if(isset($approved) && !$approved) class="disabled" @endif>
        <a   href="/sellerboat"><em class="fa fa-ship"></em> <span class="nav-label">{{ trans('sellerBoats.title') }}</span></a>
    </li>
    <li @if (Request::is('sellerbatch*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif>
        <a  href="/sellerbatch"><em class="fa fa-database"></em> <span class="nav-label">{{ trans('sellerBoats.listbatch') }}</span></a>
    </li>
    <li @if (Request::is('sellerAuction*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  href="/sellerAuction"><em class="fa fa-usd"></em> <span class="nav-label">Mis Subastas</span></a>
    </li>
    <li @if (Request::is('sales*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a href="/sales"><em class="fa fa-money"></em><span class="nav-label">Ventas de Subastas</span></a>
    </li>
	<li @if (Request::is('privatesales*')) class="active" @endif @if(isset($approved) && !$approved) class="disabled" @endif >
        <a  href="/privatesales"><em class="fa fa-money"></em> <span class="nav-label">Ventas Privadas</span></a>
    </li>
