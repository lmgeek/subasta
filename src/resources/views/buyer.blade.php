<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{trans('register.title_buyer')}}</title>

    <link href="{{ asset('/landing/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                {{--<h1 class="logo-name">IN+</h1>--}}

            </div>
			<h3>{{trans('register.title_buyer')}}</h3>
			<p>{{ trans('register.copybuyer')  }}</p>
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			
            <form class="m-t" role="form" method="POST" action="/registro/comprador">
                 {{ csrf_field() }}
				<div class="form-group">
                    <input name="name" id="name" type="text" class="form-control" placeholder="{{trans('register.name')}}" required="" value="{{ old('name') }}">
                </div>
				<div class="form-group">
                    <input name="dni" id="dni" type="text" class="form-control" placeholder="{{trans('register.dni')}}" value="{{ old('dni') }}" required="">
                </div>
                <div class="form-group">
                    <input name="email" id="email" type="email" class="form-control" placeholder="{{trans('register.email')}}" value="{{ old('email') }}" required="">
                </div>
                <div class="form-group">
                    <input name="password" id="password" type="password" class="form-control" placeholder="{{trans('register.password')}}" required="">
                </div>
				<div class="form-group">
                    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control"  placeholder="{{trans('register.confirm_password')}}" required="">
                </div>
				<div class="form-group">
                    <input name="phone" id="phone" type="text" class="form-control" placeholder="{{trans('register.phone')}}" value="{{ old('phone') }}"  required="">
                </div>
                <div class="form-group">
                       <!-- <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>-->
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{trans('register.register')}} {{ trans('register.buyer') }}</button>

                <p class="text-muted text-center"><small>{{trans('register.ifexist')}}</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/auth/login">{{trans('register.login')}}</a>
            </form>
           
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
</body>

</html>
