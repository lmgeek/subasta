@extends('admin')

@section('content')
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">{{ trans('products.name') }}</label>
                                        <input type="text" name="nombre" class="form-control" id="name" value="@if (is_null(old('nombre'))){{ $product->name }}@else{{ old('nombre') }}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit">{{ trans('products.unit') }}</label>
                                        <select class="form-control" name="unidad" id="unidad">
                                            <?php
                                            $unidad = (is_null(old('unidad'))) ? $product->unit : old('unidad');
                                            ?>
                                            @foreach(\App\Product::units() as $u)
                                                <option @if( $unidad == $u) selected @endif value="{{ $u }}">{{ trans('general.product_units.'.$u) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weigth">{{ trans('products.weigth') }}</label>
                                        <input type="number" name="weigth" class="form-control" id="weigth" value="@if (is_null(old('weigth'))){{ $product->weigth }}@else{{ old('weigth') }}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile">{{ trans('products.image') }}</label>
                                        <input type="file" id="imagen" name="imagen" onchange="readImage()">
                                        <p class="help-block">{{ trans('products.image_upload_help') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 20px">
                                    <label for="">{{ trans('products.actual_image') }}</label>
                                    @if (!is_null($product->image_name) and file_exists('img/products/'.$product->image_name) )
                                        <img src="{{ asset('/img/products/'.$product->image_name) }}" class="img-responsive" alt="" id="actual_img">
                                    @endif
                                    <div id="preview"></div>
                                </div>


                            </div>
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
        //Limpiar input type=file
        function resetFile() {
            const file = document.getElementById('imagen');
            file.value = '';
        }

        function readImage(){
            var uploadFile = document.getElementById('imagen').files[0];
            var Preview = document.getElementById("preview");

            if (!window.FileReader) {
                alert('El navegador no soporta la lectura de archivos');
                return;
            }

            if (!(/\.(jpg|jpeg|png|gif)$/i).test(uploadFile.name)) {
                alert('El archivo a adjuntar no es una imagen');
                resetFile();
                $('#actual_img').show();
                return false;
            }
            else {
                var img = new Image();
                img.onload = function () {
                    if (this.width.toFixed(0) != 172 && this.height.toFixed(0) != 102) {
                        alert('Las medidas deben ser: 172 x 102 pixeles');
                        resetFile();
                        $('#actual_img').show();
                        return false;
                    }
                    else if (uploadFile.size > 2097152)
                    {
                        alert('El peso de la imagen no puede exceder los 2MB')
                        resetFile();
                        $('#actual_img').show();
                        return false;
                    }
                    else {
                        $('#actual_img').hide();
                        $('#clear_img').show();
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