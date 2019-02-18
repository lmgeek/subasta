<?php
use App\Boat;

/*
    NOTA:

    Se instancia la clase barco pora usar la funcion referencePort()
    esta funcion permite bucar el puerto de referencia asociado a un
    barco, todo lo que se debe hacer  es llamar la funcion y pasar como
    parametro "$barco->reference_port" ejemplo:

    {{$objt->referencePort($barco->reference_port)}}

    La variable "$CantidadBarco" tiene la contidad de barco que un usurio
    pose, solo debes colocarla en el lugar que va, ejemplo:

    <td>{{$CantidadBarco}}</td>

    La variable "$infoBarco" tiene la informacion del baco, es decir
    su nombre, matricula, puerto de referencia etc.

*/

$objt = new Boat();

$CantidadBarco = count($objt->filterForSellerNickname(Auth::user()->id));

$infoBarco = $objt->filterForSellerNickname(Auth::user()->id);

?>


@extends('admin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('sellerBoats.title') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            @if ($request->session()->has('confirm_msg'))
                <div class="alert alert-success">
                    {{ $request->session()->get('confirm_msg') }}
                </div>
            @endif
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('sellerBoats.boats') }}</h5>

                        <div class="ibox-tools">
                            @can('addBoat',new App\Boat)
                            <a href="{{ route('sellerboat.create') }}" class="btn-action">
                                <em class="fa fa-plus text-success"></em> {{ trans('sellerBoats.new_boat') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover dataTables-example">
                            <thead>
                            <tr role="row">
                                <th class="sorting">
                                    {{ trans('sellerBoats.name') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('sellerBoats.matricula') }}
                                </th>
                                 <th>
                                    {{ trans('boats.port.title') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('sellerBoats.status.title') }}
                                </th>
                                <th>
                                    {{ trans('sellerBoats.actions.title') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($barcos as $barco)
                                    <tr>
                                        <td>
                                            {{ $barco->name }}
                                        </td>
                                        <td>{{ $barco->matricula }}</td>

                                        <td>{{$objt->referencePort($barco->reference_port)}}</td>

                                        <td>
                                            <span class="label label-{{ $barco->status }}">{{ trans("general.status.$barco->status") }}</span>
											@if ($barco->status == \App\Constants::RECHAZADO)
													<em data-toggle="tooltip" data-placement="top" title="{{ $barco->rebound }}"  class="fa fa-info-circle"></em>
											@endif
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="/js/plugins/dataTables/dataTables.tableTools.min.js"></script>

    {{--<script>--}}
        {{--$(document).ready(function () {--}}
            {{--$('.dataTables-example').DataTable({--}}
                {{--"aaSorting": [],--}}
                {{--language:--}}
                {{--{--}}
                    {{--url: "https://cdn.datatables.net/plug-ins/1.10.7/i18n/Spanish.json"--}}
                {{--},--}}
                {{--responsive: true,--}}
                {{--"aoColumnDefs": [--}}
                    {{--{ 'bSortable': false, 'aTargets': [ -1 ] }--}}
                {{--],--}}
                {{--"columns": [--}}
                    {{--{ "width": "25%" },--}}
                    {{--null              ,--}}
                    {{--{ "width": "15%" },--}}
                    {{--{ "width": "15%" }--}}
                {{--]--}}
            {{--});--}}

			{{--$(function () {--}}
			  {{--$('[data-toggle="tooltip"]').tooltip()--}}
			{{--})--}}

        {{--});--}}

    {{--</script>--}}
@endsection

@section('style')
    <link href="/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
@endsection