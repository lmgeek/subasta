<div class="row border-bottom">
    <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary hidden-lg hidden-md  hidden-sm" href="#"><i class="fa fa-bars"></i> </a>
            @if (Auth::user()->status == \App\User::PENDIENTE)
                <div style="float: left; margin-top: 20px; margin-left: 20px;">
                        {{--<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">--}}
                        <span class="label label-warning">{{ trans('users.user_pending') }}</span>
                </div>
            @endif
            @if (Auth::user()->active_mail == 0)
                <div style="float: left; margin-left: 20px; margin-top: 20px ">
                    {{--<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">--}}
                    <span class="label label-warning">{{ trans('users.user_pending_mail') }}</span>
                </div>
            @endif
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li>
                Usuario : {{ Auth::user()->name . " " . Auth::user()->lastname }}
            </li>
            <li>
                <a href="{{ url('/auth/logout') }}">
                    <i class="fa fa-sign-out"></i> {{ Lang::get('general.logout') }}
                </a>
            </li>
        </ul>

    </nav>
</div>