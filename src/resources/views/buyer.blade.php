<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{trans('register.title_buyer')}}</title>

    <link href="/landing/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

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
                    <input name="name" id="name" type="text" class="form-control" placeholder="{{trans('register.name')}}"  value="{{ old('name')}}">
                </div> 
                <div class="form-group">
                    <input name="lastname" id="lastname" type="text" class="form-control" placeholder="{{trans('register.lastname')}}"  value="{{ old('lastname')}}">
                </div>
                <div class="form-group">
                    <input name="alias" id="alias" type="text" class="form-control" placeholder="{{trans('register.nickname')}}"  maxlength="10" value="{{ old('alias') }}" onkeypress="return  blankSpace(event)">
                </div>
				<div class="form-group">
                    <input name="dni" id="dni" type="text" class="form-control" placeholder="{{trans('register.dni')}}" value="{{ old('dni')}}" >
                </div>
                <div class="form-group">
                    <input name="email" id="email" type="text" class="form-control" placeholder="{{trans('register.email')}}" value="{{ old('email')}}" >
                </div>
                <div class="form-group">
                    <input name="password" id="password" type="password" class="form-control" placeholder="{{trans('register.password')}}" >
                </div>
				<div class="form-group">
                    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control"  placeholder="{{trans('register.confirm_password')}}" >
                </div>
				<div class="form-group">
                    <input name="phone" id="phone" type="text" min="8" class="form-control" placeholder="{{trans('register.phone')}}" value="{{ old('phone')}}"  >
                </div>
                <div class="form-group">
                       <!-- <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>-->
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{trans('register.register')}} {{ trans('register.buyer') }}</button>
                <input type="hidden" name="cuit" value="12345678901123">
                <p class="text-muted text-center">{{trans('register.ifexist')}}</p>
                <a class="btn btn-sm btn-white btn-block" href="/auth/login">{{trans('register.login')}}</a>
            </form>
           
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/jquery.mask.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/landing3/js/netlabs-subastas3.js"></script>;
    <!-- iCheck -->
    <script src="/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

//            $("#phone").mask('0000-0000-0000', {reverse:true});
        });
    </script>
</body>

</html>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             