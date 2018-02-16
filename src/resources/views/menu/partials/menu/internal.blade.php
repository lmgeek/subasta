    <li @if (Request::is('home*')) class="active" @endif>
        <a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">{{ trans('general.dashboard') }}</span></a>
    </li>
    @can('seeUsersList',Auth::user())
        <li @if (Request::is('users*')) class="active" @endif>
            <a href="{{ url('/users') }}"><i class="fa fa-users"></i> <span class="nav-label">{{ trans('users.title') }}</span></a>
        </li>
    @endcan
    @can('seeAllBoatsList')
        <li @if (Request::is('boats*')) class="active" @endif>
            <a href="{{ url('/boats') }}"><i class="fa fa-ship"></i> <span class="nav-label">{{ trans('boats.title') }}</span></a>
        </li>
    @endcan
    <li @if (Request::is('product*')) class="active" @endif>
        <a href="{{ url('/products') }}"><i class="fa fa-cubes"></i> <span class="nav-label">{{ trans('products.title') }}</span></a>
    </li>
