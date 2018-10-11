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
    @if (Session::has('register_message'))
        <br>
        <div class="alert alert-info">{{trans('register.confirm_register_mail')}}</div>
    @endif
        @if (Session::has('register_message_fail'))
            <br>
            <div class="alert alert-info">{{trans('register.confirm_register_mail_fail')}}</div>
        @endif
    <div>
        <div>
            {{--<h1 class="logo-name">IN+</h1>--}}
            <img alt="image"  src="{{ asset('/landing/img/logo_subastas.png') }}" width="100%" style="padding: 50px 0px;" />
        </div>
        <h3>Bienvenido a subasta del Mar</h3>
        <p>
            {{--Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.--}}
        </p>
        <p>
            Ingrese por favor
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
        <form class="m-t" role="form" action="{{ url('auth/login') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Email" name="email" required="" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Contraseña"  name="password" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Entrar</button>

            <a href="{{ url('password/email') }}"><small>{{ trans('login.forgot_pass') }}</small></a>
            <p class="text-muted text-center"><small>{{ trans('login.no_account') }}</small></p>
            <a class="btn btn-sm btn-white" href="{{ url('registro/comprador') }}">Registrar Comprador</a>
            <a class="btn btn-sm btn-white" href="{{ url('registro/vendedor') }}">Registrar Vendedor</a>
        </form>
        <p class="m-t"> <small>Subastas del Mar &copy; <? echo date('Y'); ?></small> </p>
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{ asset('/js/jquery-2.1.1.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

</body>

</html>
