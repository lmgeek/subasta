<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Subastas del mar</title>

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            {{--<h1 class="logo-name">IN+</h1>--}}
        </div>
        <h3>{{ trans('users.change_password') }}</h3>
        <p>
            {{--Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.--}}
        </p>
        
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Error</strong><br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
       <form class="m-t" method="POST" action="/password/email">
									{{ csrf_field() }}
									
										<div class="form-group">
											
										    <input class="form-control" type="email" name="email" placeholder="{{ trans('users.email') }}" value="{{ old('email') }}">
											@if (\Session::has('status') == 1)
												<br>
												<div class="alert alert-success">
													{{ trans('users.password_message') }}
												</div>
											@endif
											
										</div>
									
									<div class="ibox-footer text-right">
										<center><button type="submit" class="btn btn-primary">{{ trans('users.email_reset_send_link') }}</button></center><br>
										 <p class="text-muted text-center"><small>{{trans('register.ifexist')}}</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/auth/login">{{trans('register.login')}}</a>
									</div>
								</form>
        <p class="m-t"> <small>Subastas del mar &copy; 2015</small> </p>
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{ asset('/js/jquery-2.1.1.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

</body>

</html>
