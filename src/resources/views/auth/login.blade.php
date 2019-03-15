@include('landing3/partials/common')
</head>
<body class="gray">
        <div class="middle-box text-center loginscreen animated fadeInDown" style="width: 30%; margin: auto;">
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
                    <img alt="image" src="/landing3/images/logo2.png" width="50%" style="padding: 50px 0px;"/>
                </div>
                <h3>Bienvenido a Subastas del Mar</h3>

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
                        <input type="email" class="form-control" placeholder="Email" name="email" required=""
                               value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Contraseña" name="password"
                               required="">
                    </div>
                    <button type="submit" class="button block full-width m-b">Entrar</button>

                    <a href="{{ url('password/email') }}">
                        <small>{{ trans('login.forgot_pass') }}</small>
                    </a>
                    <p class="text-muted text-center">
                        <small>{{ trans('login.no_account') }}</small>
                    </p>
                    <a class="btn btn-sm btn-white" href="{{ url('registro/comprador') }}">Registrar Comprador</a><br>
                    <a class="btn btn-sm btn-white" href="{{ url('registro/vendedor') }}">Registrar Vendedor</a>
                </form>

            </div>
        </div>



@include('landing3/partials/js')

</body>
</html>