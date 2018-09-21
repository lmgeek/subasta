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
                        <h5>{{ trans('products.productInfo') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">{{ trans('products.name') }}</label>
                                        <input type="text" name="nombre" class="form-control" id="name" value="{{ old('nombre') }}" onkeypress="return validaName(event);">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit">{{ trans('products.unit') }}</label>
                                        {!! Form::select('unidad',$units, old('unidad'), ['class' => 'required form-control']) !!}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weigth">{{ trans('products.weigth') }}</label>
                                        <input type="text" min="0" name="weigth" class="form-control number" id="weigth" value="{{ old('weigth') }}">
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
                                        <span><i class="fa fa-trash"></i> Limpiar imagen</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 alert alert-warning alert-dismissable" id="error" style="display: none">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong><i class="fa fa-info-circle"></i></strong> El peso debe ser mayor a 0,00.
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
    <script src="{{ asset('/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ asset('/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/iCheck/icheck.min.js') }}"></script>
    <script>

        $(document).on('keydown keyup',".number",onlyNumberWithComma);

        $(".number").blur(function(){
            var insert = $(this).val().replace(',', '.');
            var num = parseFloat(insert);
            var cleanNum = num.toFixed(2).replace(".", ",");
            $(this).val(cleanNum);
            if(cleanNum == "NaN"){
                $(this).val('');
            }
            if(num/cleanNum < 1){
                $('#error').text('Please enter only 2 decimal places, we have truncated extra points');
            }
            if(insert == 0){
                // $(this).val('');
                document.getElementById('error').style.display = 'block';
            }
            console.log(insert);
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


            str = str.replace(/[^\d|\s|^\w]/g,"");

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
                    else if (this.width.toFixed(0) != 630 && this.height.toFixed(0) != 404) {
                        alert('La imagen no puede ser adjuntada. Las medidas deben ser: 630 x 404 pixeles');
                        resetFile();
                        return false;
                    }
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