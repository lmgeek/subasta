@include('landing3/partials/common')
</head>
<body class="gray">
<div class="middle-box text-center loginscreen animated fadeInDown" style="width: 30%; margin: auto;">
        <div>
            <div>

                <img alt="image" src="/landing3/images/logo2.png" width="50%" style="padding: 50px 0px;"/>

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
                    <input name="name" id="name" type="text" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Nombre es obligatorio')" class="form-control" placeholder="{{trans('register.name')}}" value="{{ old('name') }}" >
                </div>
                <div class="form-group">
                    <input name="lastname" id="lastname" type="text" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Apellido es obligatorio')"class="form-control" placeholder="{{trans('register.lastname')}}"  value="{{ old('lastname') }}">
                </div>
                <div class="form-group">
                    <input name="alias" id="alias" type="text" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Alias es obligatorio')"class="form-control" placeholder="{{trans('register.nickname')}}" maxlength="10" value="{{ old('alias') }}" onkeypress="return blankSpace(event)">
                </div>
				<div class="form-group">
                    <input name="cuit" id="cuit" type="text" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo CUIT es obligatorio, mínimo 13 caracteres con guiones (-)')"class="form-control" placeholder="{{trans('register.cuit')}}" value="{{ old('cuit') }}" >
                </div>
                <div class="form-group">
                    <input name="email" id="email" type="email" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Email es obligatorio')" class="form-control" placeholder="{{trans('register.email')}}" value="{{ old('email') }}" >
                </div>
                <div class="form-group">
                    <input name="password" id="password" type="password" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Contraseña es obligatorio')" class="form-control" placeholder="{{trans('register.password')}}" >
                </div>
				<div class="form-group">
                    <input name="password_confirmation" id="password_confirmation" type="password" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Confirmar Contraseña es obligatorio')"class="form-control"  placeholder="{{trans('register.confirm_password')}}" >
                </div>
				<div class="form-group">
                    <input  type="tel" name="phone" id="phone" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Teléfono es obligatorio')" class="form-control" placeholder="{{trans('register.phone')}}" value="{{ old('phone') }}" onkeypress="return telefono(event);">
                </div>
                <div class="form-group">
                       <!-- <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>-->
                </div>
                <input type="hidden" name="dni" value="12345678901">
                <button type="submit" class="button block full-width m-b">{{trans('register.register')}} {{ trans('register.seller') }}</button>

                <p class="text-muted text-center"><small>{{trans('register.ifexist')}}</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/auth/login">{{trans('register.login')}}</a>
            </form>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.mask.js"></script>
    <script src="/landing3/js/netlabs-subastas3.js"></script>;
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

/*        G.B funcion para no permitir acentos, solo debemos pasar el id del tag*/
    /*  doesNotAllowAccents("#name");
       doesNotAllowAccents("#lastname");*/
        $(document).on('keydown keyup',".number",onlyNumberWithComma);

        $(".number").blur(function(){
            var insert = $(this).val().replace(',', '.');
            var num = parseFloat(insert);
            var cleanNum = num.toFixed(2).replace(".", ",");
            var error = document.getElementById('error');
            error.style.display = 'none';
            $(this).val(cleanNum);
            if(cleanNum == "NaN"){
                $(this).val('');
            }
            if(num/cleanNum < 1){
                $('#error').text('Please enter only 2 decimal places, we have truncated extra points');
            }
        });
        function telefono(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toString();
            //Se define todo lo que se quiere que se muestre
            caracter = "0123456789-()*#+";
            especiales = [];

            tecla_especial = false;
            for (var i in especiales) {
                if (key == especiales[i]) {
                    tecla_especial = true;
                    break;
                }
            }
            if (caracter.indexOf(tecla) == -1 && !tecla_especial) {
                // alert('Tecla no aceptada');
                return false;
            }
        }
        function onlyNumberWithComma(e){
            var evt = e || window.event;
            var x = evt.key;
            var str = this.value;
            var index = str.indexOf(',');
            var check = x == 0 ? 0: (parseInt(x) || -1);
            if (index == 0){
                str = "";
            }
            if ( index > -1) {
                str = str.substr( 0, index + 1 ) +
                    str.slice( index ).replace( /,/g, '' );
            }

            str = str.replace(/[^\d|\,]/g,"");

            $(this).val(str);

            if (check === -1 && x != "Backspace" && x != ','){
                return false;
            }
        }
    </script>
</body>

</html>
