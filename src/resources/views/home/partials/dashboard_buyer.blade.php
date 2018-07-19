@if (Session::has('register_message'))
    <br>
    <div class="alert alert-info">{{trans('register.confirm_register')}}</div>
@endif
