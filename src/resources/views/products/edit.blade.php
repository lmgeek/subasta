@extends('admin')

@section('content')
    <style>
        a {
            text-decoration: none;
            color: #953b39;
            font-size: 1.1em;
        }
        a:hover {
            color: #a81a20;
            font-weight: bold;
        }
        #preview {
            max-width: 300px;
        }
        #preview img {
            width: 100%;
        }
    </style>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('products.edit') }}</h2>
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
                        <form action="{{ route('products.update',$product) }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="relationship" value="{{ $const }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">codigo Pesquero</label>
                                        <input type="text" name="codigo" class="form-control" @if ($const <> 0 ) disabled @endif id="codigo" max="10" value="@if (is_null(old('codigo'))){{ $product->fishing_code }}@else{{ old('codigo') }}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">{{ trans('products.name') }}</label>
                                        <input type="text" name="nombre" class="form-control" @if ($const <> 0 ) disabled @endif id="name" value="@if (is_null(old('nombre'))){{ $product->name }}@else{{ old('nombre') }}@endif">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label >Calibre del Producto</label>
                                        {{--<input type="number" name="weigth" class="form-control" id="weigth" value="@if (is_null(old('weigth'))){{ $product->weigth }}@else{{ old('weigth') }}@endif">--}}
                                        <br>
                                        <small><p style="color: gray; font-style: italic;">   Sólo permite 2 decimales</p></small>
                                        <div class="col-md-4">
                                            <label for="weigth_small">Chico</label>
                                            <input type="text"  name="weight_small" class="form-control number" id="weigth_small" value="@if (is_null(old('weight_small'))){{ number_format($datail['weight'],2,",","") }}@else{{ old('weight_small') }}@endif">
                                            <label for="unit">{{ trans('products.unit') }}</label>
                                            <select class="form-control" name="unidadp" id="unidadp">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $unidad = (is_null(old('unidadp'))) ? $datail['presentation_unit'] : old('unidadp');
                                                ?>
                                                @foreach(\App\Product::units() as $u)
                                                    <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="unit">Unida de venta</label>
                                            <select class="form-control" name="salep" id="salep">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $uni = (is_null(old('salep'))) ? $datail['sale_unit'] : old('salep');
                                                ?>
                                                @foreach(\App\Product::SALE() as $a)
                                                    <option @if( $uni == $a) selected @endif value="{{ $a }}">{{ trans($a) }}</option>
                                                @endforeach
                                            </select>
                                            <select class="form-control" name="statusp" id="statusp">
                                                <?php
                                                $unidad = (is_null(old('statusp'))) ? $datail['presentation_unit'] : old('statusp');
                                                ?>
                                                @foreach(\App\Product::status() as $u)
                                                    <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ $u }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="weigth_medium">Mediano</label>
                                            <input type="text" name="weight_medium" class="form-control number" id="weigth_medium" value="@if (is_null(old('weight_medium'))){{ number_format($datail2['weight'],2,",","") }}@else{{ old('weight_medium') }}@endif">
                                            <label for="unit">{{ trans('products.unit') }}</label>
                                            <select class="form-control" name="unidadm" id="unidadm">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $unidad = (is_null(old('unidadm'))) ? $datail2['presentation_unit'] : old('unidadm');
                                                ?>
                                                @foreach(\App\Product::units() as $u)
                                                    <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="unit">Unida de venta</label>
                                            <select class="form-control" name="salem" id="salem">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $uni = (is_null(old('salem'))) ? $datail2['sale_unit'] : old('salem');
                                                ?>
                                                @foreach(\App\Product::SALE() as $a)
                                                    <option @if( $uni == $a) selected @endif value="{{ $a }}">{{ trans($a) }}</option>
                                                @endforeach
                                            </select>
                                            <select class="form-control" name="statusm" id="statusm">
                                                <?php
                                                $unidad = (is_null(old('statusm'))) ? $datail2['presentation_unit'] : old('statusm');
                                                ?>
                                                @foreach(\App\Product::status() as $u)
                                                    <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ $u }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="weigth_big">Grande</label>
                                            <input type="text" name="weight_big" class="form-control number" id="weigth_big" value="@if (is_null(old('weight_big'))){{ number_format($datail3['weight'],2,",","") }}@else{{ old('weight_big') }}@endif">
                                            <label for="unit">{{ trans('products.unit') }}</label>
                                            <select class="form-control" name="unidadg" id="unidadg">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $unidad = (is_null(old('unidadg'))) ? $datail3['presentation_unit'] : old('unidadg');
                                                ?>
                                                @foreach(\App\Product::units() as $u)
                                                    <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                                @endforeach
                                            </select>
                                            <label for="unit">Unida de venta</label>
                                            <select class="form-control" name="saleg" id="saleg">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                $uni = (is_null(old('saleg'))) ? $datail3['sale_unit'] : old('saleg');
                                                ?>
                                                @foreach(\App\Product::SALE() as $a)
                                                    <option @if( $uni == $a) selected @endif value="{{ $a }}">{{ trans($a) }}</option>
                                                @endforeach
                                            </select>
                                            <select class="form-control" name="statusg" id="statusg">
                                                <?php
                                                $unidad = (is_null(old('statusg'))) ? $datail3['presentation_unit'] : old('statusg');
                                                ?>
                                                @foreach(\App\Product::status() as $u)
                                                    <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ $u }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-md-12 alert alert-warning alert-dismissable" id="error" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong><em class="fa fa-info-circle"></em></strong> El peso debe ser mayor a 0,00.
                                    </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile">{{ trans('products.image') }}</label>
                                        <input type="file" id="imagen" name="imagen" onchange="readImage()">
                                        <p class="help-block">{{ trans('products.image_upload_help') }}</p>
                                    </div>
                                    @if (!is_null($product->image_name) and file_exists('img/products/'.$product->image_name) )
                                        <img src="/img/products/{{$product->image_name}}" class="img-responsive" alt="" id="actual_img">
                                    @endif
                                    <div id="preview"></div>
                                    <a href="#" onclick="resetFile()" id="trash">
                                        <span><em class="fa fa-trash"></em> Limpiar imagen</span>
                                    </a>
                                </div>
                            </div>
                                {{--<div class="col-md-12" style="margin-bottom: 20px">--}}
{{--                                    <label for="">{{ trans('products.actual_image') }}</label>--}}

                                {{--</div>--}}




                            <div class="ibox-footer text-right">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <a href="{{ URL::previous() }}" type="button" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
            if(insert == 0){
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



        //Limpiar input type=file
        function resetFile() {
            const uploadFile = document.getElementById('imagen');
            var Preview = document.getElementById("preview");
            var trash = document.getElementById('trash');
            uploadFile.value = '';
            Preview.innerHTML = '';
            $('#actual_img').hide();
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
                $('#actual_img').show();
                Preview.innerHTML = '';
                return false;
            }
            else {
                var img = new Image();
                img.onload = function () {
                    if ( uploadFile.size > 2097152 ) {
                        alert('La imagen no puede ser adjuntada. El tamaño no puede exceder los 2MB')
                        resetFile();
                        $('#actual_img').show();
                        return false;
                    // } else if (this.width.toFixed(0) != 172 && this.height.toFixed(0) != 102) {
                    } else if (this.width.toFixed(0) != 630 && this.height.toFixed(0) != 404) {
                        alert('La imagen no puede ser adjuntada. Las medidas deben ser: 172 x 102 pixeles');
                        resetFile();
                        $('#actual_img').show();
                        Preview.innerHTML = '';
                        return false;
                    } else {
                        $('#actual_img').hide();
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