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
                                        <input type="text" name="nombre" class="form-control" id="name" value="{{ old('nombre') }}">
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
                                        <input type="number" name="weigth" class="form-control" id="weigth" value="{{ old('weigth') }}">
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
                                <div class="col-md-12">


                                </div>
                                <div class="ibox-footer text-right">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <a href="{{ route('products.index') }}" type="button" class="btn btn-danger">Cancelar</a>
                                </div>
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
                    else if (this.width.toFixed(0) != 172 && this.height.toFixed(0) != 102) {
                        alert('La imagen no puede ser adjuntada. Las medidas deben ser: 172 x 102 pixeles');
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