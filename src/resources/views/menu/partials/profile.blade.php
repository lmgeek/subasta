<div class="dropdown profile-element">
    {{--<span>--}}
        {{--<img alt="image" class="img-circle" width="48" height="48" src="{{ asset('/img/noprofile.jpg') }}" />--}}
        {{--<img src="{{ asset('/img/logo__large_plus.png') }}" alt="" style="max-width: 49px;"/>--}}
    {{--</span>--}}
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong>
        </span> <span class="text-muted text-xs block">{{ Auth::user()->email }} <b class="caret"></b></span> </span>
    </a>
    <ul class="dropdown-menu animated fadeInRight m-t-xs">
        <li>
            <a href="{{ url('/') }}">{{ Lang::get('Inicio') }}</a>
        </li>
        {{-- <li>
            <a href="{{ url('/profile/changepassword') }}">{{ Lang::get('profile.change_password.title') }}</a>
        </li> --}}
        {{--<li class="divider"></li>--}}
        <li>
            <a href="{{ url('/auth/logout') }}">{{ Lang::get('general.logout') }}</a>
        </li>
    </ul>
</div>