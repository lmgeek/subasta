<div class="dropdown profile-element">
    {{--<span>--}}
        {{--<img alt="image" class="img-circle" width="48" height="48" src="/img/noprofile.jpg" />--}}
        {{--<img src="/img/logo__large_plus.png" alt="" style="max-width: 49px;"/>--}}
    {{--</span>--}}
    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name . " " . Auth::user()->lastname }}</strong>
        </span> <span class="text-muted text-xs block">{{ Auth::user()->email }} <strong class="caret"></strong></span> </span>
    </a>
    <ul class="dropdown-menu animated fadeInRight m-t-xs">
        <li>
            <a href="/">{{ Lang::get('Inicio') }}</a>
        </li>
        {{-- <li>
            <a href="/profile/changepassword">{{ Lang::get('profile.change_password.title') }}</a>
        </li> --}}
        {{--<li class="divider"></li>--}}
        <li>
            <a href="/auth/logout">{{ Lang::get('general.logout') }}</a>
        </li>
    </ul>
</div>