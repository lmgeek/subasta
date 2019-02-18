<?php
use App\Ports;

/** G.B Con la variable port podras listar los
 * puertos que estan en base de datos
 */
$ports = Ports::get();
?>

@extends('admin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('sellerBoats.new_boat') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Error</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ str_replace('name', 'Nombre', $error)  }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('sellerBoats.boat_info') }}</h5>
                    </div>
                    <form action="{{ route('sellerboat.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="ibox-content">
                            <div class="form-group">
                                <label for="name">{{ trans('sellerBoats.name') }}</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('sellerBoats.name') }}" value="{{ old('name') }}" required minlength="6"  onkeypress="return validaName(event);"  onKeyUp="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="form-group">
                                <label for="matricula">{{ trans('sellerBoats.matricula') }}</label>
                                <input type="text" name="matricula" class="form-control" id="matricula" placeholder="{{ trans('sellerBoats.matricula') }}"  value="{{ old('matricula') }}" required minlength="4"  onkeypress="return validaMatricula(event);"  onKeyUp="this.value = this.value.toUpperCase();">
                            </div>

                            <div class="form-group">
                                <label for="arrival_id">Puerto</label>
                                <select class="form-control" id="arrival_id" name="port">
                                    <option disabled selected>Seleccione...</option>
                                    @foreach($ports as $port)
                                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{--<div class="form-group">
                                <label for="alias">Alias</label>
                                <input type="text" name="alias1" class="form-control" id="alias1" placeholder="Alias" value = "{{"Barco ".Boat::RomanNumber(count(Boat::filterForSellerNickname(Auth::user()->id))+1)}}" required minlength="10" disabled="true" >
                                <input type="hidden" name="alias" class="form-control" id="alias" placeholder="Alias" value = "{{"Barco ".Boat::RomanNumber(count(Boat::filterForSellerNickname(Auth::user()->id))+1)}}" required minlength="10" >
                            </div>--}}

                        </div>
                        <div class="ibox-footer text-right">
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Guardando...">Guardar</button>
                            <a href="{{ route('sellerboat.index') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validaName(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toString();
            //Se define todo lo que se quiere que se muestre
            caracter = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789";
            especiales = [6, 8, 32, 35, 39, 45, 46, 176];

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

        function validaMatricula(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toString();
            //Se define todo lo que se quiere que se muestre
            caracter = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789";
            especiales = [6, 8, 39, 45, 127, 176];

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

        //neww
        // $(document).ready(function () {
        //
        //     $(document).on('keydown keyup',"#exampleInputEmail1",function(e){
        //         var evt = e || window.event;
        //         var x = evt.key;
        //         var str = this.value;
        //         var index = str.indexOf(' ');
        //         var check = x == 0 ? 0: (parseInt(x) || -1);
        //         console.log(x);
        //         if (index == 0){
        //             str = "";
        //         }
        //         if ( index > -1) {
        //             str = str.substr( 0, index + 1 ) +
        //                 str.slice( index ).replace( /\s/g, '' );
        //         }
        //
        //         // str = str.replace(/[^\,]/g,"");
        //         str = str.replace(/([\ \t]+(?=[\ \t])|^\s+|\s+$)/g, '');
        //
        //         $(this).val(str);
        //
        //         if (check === -1 && x != "Backspace" && x != ','){
        //             return false;
        //         }
        //
        //     });
        // });
        //neww
    </script>
@endsection
@section('scriptsanalytics')
    @if(isset($request))
    gtag('event', '<?=$request->e?>', {
    'event_category':'<?=$request->t?>',
    'event_label':'ID Barco: <?=$request->id?> Usuario: <?=Auth::user()->nickname?>',
    });
    @endif
@endsection

{{--strVal.replace(/([\ \t]+(?=[\ \t])|^\s+|\s+$)/g, '');--}}