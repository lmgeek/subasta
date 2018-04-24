@if (Session::has('register_message'))
    <br>
    <div class="alert alert-info">{{trans('register.confirm_register')}}</div>
@endif

{{-- @include("home.partials.widget_arrive_".Auth::user()->type) --}}