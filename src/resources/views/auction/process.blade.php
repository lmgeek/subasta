@extends('admin')

@section('content')
    <?php use Carbon\Carbon; ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('auction.process_operation') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <form action="{{ route('auction.saveProcess') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $bid->id }}">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2" style="margin-top: 10px">
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
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>{{ trans('auction.operation') }}</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-white btn_concretada"><input type="radio" value="{{ \App\Bid::CONCRETADA }}" name="concretada" id="ventaConcretada">Compra Concretada</label>
                                        <label class="btn btn-white btn_noConcretada"><input type="radio"  value="{{ \App\Bid::NO_CONCRETADA }}" name="concretada" id="ventaNoConcretada">Compra no concretada</label>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center motivoNoConcretada" style="display: none;">
                                    <textarea name="motivo_no_concretada" placeholder="Motivo" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h2>Calificar Comprador</h2>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-white btn_positivo"><input type="radio" value="{{ \App\Bid::CALIFICACION_POSITIVA }}" name="calificacion" id="calificacionPositiva">Calificacion Positiva</label>
                                        <label class="btn btn-white btn_neutro"><input type="radio"  value="{{ \App\Bid::CALIFICACION_NEUTRAL }}" name="calificacion" id="calificacionNeutra">Calificacion Neutra</label>
                                        <label class="btn btn-white btn_negativa"><input type="radio"  value="{{ \App\Bid::CALIFICACION_NEGATIVA}}" name="calificacion" id="calificacionNegativa">Calificacion Negativa</label>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <textarea name="comentariosCalificacion" placeholder="Comentarios" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-footer text-right">
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Guardando...">Guardar</button>
                            <a href="{{ route('auction.operations',$bid->auction) }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('/js/plugins/dataTables/dataTables.responsive.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('input[name="concretada"]').bind('change',function(){
                var showOrHide = ($(this).val() == '{{ \App\Bid::NO_CONCRETADA }}') ? true : false;
                $('.motivoNoConcretada').toggle(showOrHide);
            });
        });
    </script>
@endsection

@section('stylesheets')
    <style>
        .btn_concretada.active {
            background-color: #1AB394;
            color:white;
        }
        .btn_noConcretada.active {
            background-color: #ED5565;
            color:white;
        }
        .btn_positivo.active{
            background-color: #1AB394;
            color:white;
        }
        .btn_neutro.active{
            background-color: #BABABA;
            color:white;
        }
        .btn_negativa.active{
            background-color: #ED5565;
            color:white;
        }
        textarea{
            width: 50%;
        }
    </style>
@endsection

