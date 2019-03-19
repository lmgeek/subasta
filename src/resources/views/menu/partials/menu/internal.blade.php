    <li>
        <a href="/"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    <li @if (Request::is('home*')) class="active" @endif>
        <a href="/home"><em class="fa fa-dashboard"></em> <span class="nav-label">{{ Lang::get('Dashboard') }}</span></a>
    </li>
    @can('seeUsersList',Auth::user())
        <li @if (Request::is('users*')) class="active" @endif>
            <a href="/users"><em class="fa fa-users"></em> <span class="nav-label">{{ trans('users.title') }}</span></a>
        </li>
    @endcan
    @can('seeAllBoatsList')
        <li @if (Request::is('boats*')) class="active" @endif>
            <a href="{{url('/barcos')}}"><em class="fa fa-ship"></em> <span class="nav-label">{{ trans('boats.title') }}</span></a>
        </li>
    @endcan
    <li @if (Request::is('product*')) class="active" @endif>
        <a href="/products"><em class="fa fa-cubes"></em> <span class="nav-label">{{ trans('products.title') }}</span></a>
    </li>
