@extends('admin')

@section('content')
    <style>
        a {
            text-decoration: none;
            color: #953b39;
            font-size: 1.1em;
            display: none;
        }
        a:hover {
            color: #a81a20;
            font-weight: bold;
        }
        /*.ibox-footer {*/
            /*border-top: none!important;*/
        /*}*/

        #preview {
            max-width: 300px;
        }
        #preview img {
            width: 100%;
        }

    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('products.new') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            @if (count($errors) > 0)
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="alert alert-danger">
                        <strong>Error</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="col-lg-8 col-lg-offset-2">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Información  General del Producto</h5>
                    </div>
                    <div class="ibox-content">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Código Pesquero</label>
                                        <input type="text" name="codigo" maxlength="10"   class="form-control" value="{{ old('codigo') }}" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Código Pesquero es obligatorio')" onkeypress="return codigopesquero(event);" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">{{ trans('products.name') }}</label>
                                        <input type="text" name="name" class="form-control" id="name" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Nombre es obligatorio')" value="{{ old('name') }}" onkeypress="return nombre(event);">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="weigth">Información del Detalle de Producto</label>
                                        <br>
                                        <small><p style="color: gray; font-style: italic;">   Sólo permite 2 decimales en el peso del producto</p></small>
                                        <div class="col-md-4">
                                            <label for="weigth_small" style=" font-size: 16px;">Chico</label><br>

                                            <div class="form-group">
                                                <label for="unit">Unidad de Presentacion</label>
                                                <select class="form-control" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Unidad de Presentación del Calibre Chico es obligatorio')"name="unidadp">
                                                    <option value="">Seleccione...</option>
                                                    <?php
                                                    $unidadp = ( old('unidadp'));
                                                    ?>
                                                    @foreach(\App\Product::units() as $u)
                                                        <option @if( $unidadp == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="weigth_small">Peso aproximado</label>
                                            <input type="text" name="weight_small" class="form-control number" id="weigth_small" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Peso del Calibre Chico es obligatorio')" value="{{ old('weight_small') }}">
                                            <div class="form-group">
                                                <label for="unit">Unidad de Venta </label>
                                                <select class="form-control" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Unidad de Venta del Calibre Chico es obligatorio')"name="salep">
                                                    <option value="">Seleccione...</option>
                                                    <?php
                                                    $salep = ( old('salep'));
                                                    ?>
                                                    @foreach(\App\Product::SALE() as $u)
                                                        <option @if( $salep == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::select('statusp',$statusr, old('statusp'), ['class' => 'required form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="weigth_medium" style=" font-size: 16px;">Mediano</label><br>

                                            <div class="form-group">
                                                <label for="unit">Unidad de Presentacion</label>
                                                <select class="form-control" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Unidad de Presentación del Calibre Mediano es obligatorio')"name="unidadm">
                                                    <option value="">Seleccione...</option>
                                                    <?php
                                                    $unidadm = ( old('unidadm'));
                                                    ?>
                                                    @foreach(\App\Product::units() as $u)
                                                        <option @if( $unidadm == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="weigth_small">Peso aproximado</label>
                                            <input type="text" name="weight_medium" class="form-control number" id="weigth_medium" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Peso del Calibre Mediano es obligatorio')" value="{{ old('weight_medium') }}">
                                            <div class="form-group">
                                                <label for="unit">Unidad de Venta </label>
                                                <select class="form-control" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Unidad de Venta del Calibre Mediano es obligatorio')"name="salem">
                                                    <option value="">Seleccione...</option>
                                                    <?php
                                                    $salem = ( old('salem'));
                                                    ?>
                                                    @foreach(\App\Product::SALE() as $u)
                                                        <option @if( $salem == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::select('statusm',$statusr, old('statusm'), ['class' => 'required form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="weigth_big" style=" font-size: 16px;">Grande</label><br>

                                            <div class="form-group">
                                                <label for="unit">Unidad de Presentacion</label>
                                                <select class="form-control" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Unidad de Presentación del Calibre Grande es obligatorio')"name="unidadg">
                                                    <option value="">Seleccione...</option>
                                                    <?php
                                                    $unidadg = ( old('unidadg'));
                                                    ?>
                                                    @foreach(\App\Product::units() as $u)
                                                        <option @if( $unidadg == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="weigth_small">Peso aproximado</label>
                                            <input type="text" name="weight_big" class="form-control number" id="weigth_big" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Peso del Calibre Grande es obligatorio')" value="{{ old('weight_big') }}">
                                            <div class="form-group">
                                                <label for="unit">Unidad de Venta </label>
                                                <select class="form-control" oninput="this.setCustomValidity('')" required oninvalid="this.setCustomValidity('El campo Unidad de Venta del Calibre Grande es obligatorio')"name="saleg">
                                                    <option value="">Seleccione...</option>
                                                    <?php
                                                    $saleg = ( old('saleg'));
                                                    ?>
                                                    @foreach(\App\Product::SALE() as $u)
                                                        <option @if( $saleg == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                {!! Form::select('statusg',$statusr, old('statusg'), ['class' => 'required form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 alert alert-warning alert-dismissable" id="error" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong><em class="fa fa-info-circle"></em></strong> El peso debe ser mayor a 0,00.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile">{{ trans('products.image') }}</label>
                                        <input type="file" id="imagen" name="imagen" onchange="readImage()">
                                        <p class="help-block">{{ trans('products.image_upload_help') }}</p>
                                    </div>
                                    <div id="preview"></div>
                                    <a href="#" onclick="resetFile()" id="trash">
                                        <span><em class="fa fa-trash"></em> Limpiar imagen</span>
                                    </a>
                                </div>
                            </div>

                            <div class="ibox-footer text-right">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <a href="{{ route('products.index') }}" type="button" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/jquery.mask.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        function codigopesquero(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toString();
            //Se define todo lo que se quiere que se muestre
            caracter = "0123456789 ";
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
        function nombre(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toString();
            //Se define todo lo que se quiere que se muestre
            caracter = "qwertyuiopñlkjhgfdsazxcvbnmQWERTYUIOPÑLKJHGFDSAZXCVBNM´  ";
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
            if(insert == 0 || insert == ''){
                // $(this).val('');
                error.style.display = 'block';
                $(this).val('0,00');
                console.log(insert);
            }
        });
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



        $(document).on('keydown keyup',"#name",function(e){
            var evt = e || window.event;
            var x = evt.key;
            var str = this.value;
            var index = str.indexOf(',');
            var check = x == 0 ? 0: (parseInt(x) || -1);
            console.log(x);


            str = str.replace(/[^a-zñáéíóúöü A-ZÑÁÉÍÓÚÖÜ0-9]/g,"");
            // str = str.replace(/[^\d|\s|^\w]/g,"");

            $(this).val(str);

        });

        //Limpiar input type=file
        function resetFile() {
            const file = document.getElementById('imagen');
            var trash = document.getElementById('trash');
            var Preview = document.getElementById("preview");
            file.value = '';
            Preview.innerHTML = '';
            trash.style.display = 'none';
        }

        function readImage(){
            var uploadFile = document.getElementById('imagen').files[0];
            var Preview = document.getElementById("preview");
            var trash = document.getElementById('trash');

            if (!window.FileReader) {
                alert('El navegador no soporta la lectura de archivos');
                return;
            }

            if (!(/\.(jpg|jpeg|png|gif)$/i).test(uploadFile.name)) {
                alert('La imagen no puede ser adjuntada. Los formatos permitidos son .jpg .jpeg .png y .gif');
                resetFile();
                return false;
            }
            else {
                var img = new Image();
                img.onload = function () {
                    if (uploadFile.size > 2097152) {
                        alert('La imagen no puede ser adjuntada. El tamaño no puede exceder los 2MB')
                        resetFile();
                        return false;
                    }
                    // else if (this.width.toFixed(0) != 172 && this.height.toFixed(0) != 102) {
                  /*  if (this.width.toFixed(0) != 630 && this.height.toFixed(0) != 404) {
                        alert('La imagen no puede ser adjuntada. Las medidas deben ser: 630 x 404 pixeles');
                        resetFile();
                        return false;
                    }*/
                    else {
                        Preview.innerHTML = '';
                        trash.style.display = 'inline';
                        Preview.appendChild(this);
                    }
                };
                img.src = URL.createObjectURL(uploadFile);
            }
        }

    </script>
@endsection

@section('scripts')
@endsection

@section('stylesheets')
@endsection