<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{trans('register.title_seller')}}</title>

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
            <h3>{{trans('register.title_seller')}}</h3>
			<p>{{ trans('register.copyseller')  }}</p>
            @if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<form class="m-t" role="form" method="POST" action="/registro/vendedor">
                 {{ csrf_field() }}
				<div class="form-group">
                    <input name="name" id="name" type="text" class="form-control" placeholder="{{trans('register.name')}}" value="{{ old('name') }}" >
                </div>
                <div class="form-group">
                    <input name="lastname" id="lastname" type="text" class="form-control" placeholder="{{trans('register.lastname')}}"  value="{{ old('lastname') }}">
                </div>
                <div class="form-group">
                    <input name="alias" id="alias" type="text" class="form-control" placeholder="{{trans('register.nickname')}}"  maxlength="10" value="{{ old('alias') }}" onkeypress="return check(event)">
                </div>
				<div class="form-group">
                    <input name="cuit" id="cuit" type="text" class="form-control" placeholder="{{trans('register.cuit')}}" value="{{ old('cuit') }}" >
                </div>
                <div class="form-group">
                    <input name="email" id="email" type="email" class="form-control" placeholder="{{trans('register.email')}}" value="{{ old('email') }}" >
                </div>
                <div class="form-group">
                    <input name="password" id="password" type="password" class="form-control" placeholder="{{trans('register.password')}}" >
                </div>
				<div class="form-group">
                    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control"  placeholder="{{trans('register.confirm_password')}}" >
                </div>
				<div class="form-group">
                    <input name="phone" id="phone" type="text" class="form-control" placeholder="{{trans('register.phone')}}" value="{{ old('phone') }}" >
                </div>
                <div class="form-group">
                       <!-- <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>-->
                </div>
                <input type="hidden" name="dni" value="12345678901">
                <button type="submit" class="btn btn-primary block full-width m-b">{{trans('register.register')}} {{ trans('register.seller') }}</button>

                <p class="text-muted text-center"><small>{{trans('register.ifexist')}}</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/auth/login">{{trans('register.login')}}</a>
            </form>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.mask.js"></script>
    <!-- iCheck -->
    <script src="/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $("#cuit").mask('00-00000000-0');
            $("#phone").mask('(00)0000-0000');
        });

        $(document).on('keydown keyup',"#name",function(e){
            var evt = e || window.event;
            var x = evt.key;
            var str = this.value;
            var index = str.indexOf(',');
            var check = x == 0 ? 0: (parseInt(x) || -1);
            console.log(x);


            str = str.replace(/[^\d|\s|^\w]/g,"");

            $(this).val(str);

        });

        $(document).on('keydown keyup',"#lastname",function(e){
            var evt = e || window.event;
            var x = evt.key;
            var str = this.value;
            var index = str.indexOf(',');
            var check = x == 0 ? 0: (parseInt(x) || -1);
            console.log(x);


            str = str.replace(/[^\d|\s|^\w]/g,"");

            $(this).val(str);

        });


        //Evitar escribir espacio
        function check(e) {
            tecla = (document.all) ? e.keyCode : e.which;

            //Tecla de retroceso para borrar, siempre la permite
            if (tecla == 8) {
                return true;
            }

            // Patron de entrada, en este caso solo acepta numeros y letras
            patron = /[A-Za-z0-9]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }

    </script>
</body>

</html>
