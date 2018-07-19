@extends('admin')



@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('boats.title') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            @if ($request->session()->has('confirm_msg'))
                <div class="alert alert-success">
                    {{ $request->session()->get('confirm_msg') }}
                </div>
            @endif
            @include('boat.partials.boat_filter')
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('boats.boats') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover dataTables-example">
                            <thead>
                            <tr role="row">
                                <th class="sorting">
                                    {{ trans('boats.buyer.label') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('boats.name') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('boats.matricula') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('boats.status') }}
                                </th>
                                <th>
                                    {{ trans('boats.actions.title') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($boats as $boat)
                                    <tr>
                                        <td class="">
                                            {{ $boat->user->name }}
                                        </td>
                                        <td>{{ $boat->name }}</td>
                                        <td>{{ $boat->matricula }}</td>
                                        <td>

												<span class="label label-{{ $boat->status }}">{{ trans("general.status.$boat->status") }}</span>

												@if ($boat->status == \App\Boat::RECHAZADO)
													<i data-toggle="tooltip" data-placement="top" title="{{ $boat->rebound }}"  class="fa fa-info-circle"></i>
												@endif

                                        </td>
                                        <td>
                                            @can('seeBoatDetail',$boat)
                                                <a href="{{ route('boats.show',$boat) }}" class="btn-action">{{ trans('boats.actions.view') }}</a>
                                            @endcan
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
    <script src="{{ asset('/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('/js/plugins/dataTables/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('/js/plugins/dataTables/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.dataTables-example').DataTable({
                "aaSorting": [],
                language:
                {
                    url: "http://cdn.datatables.net/plug-ins/1.10.7/i18n/Spanish.json"
                },
                responsive: true,
                "aoColumnDefs": [
                    { 'bSortable': false, 'aTargets': [ -1 ] }
                ],
                "columns": [
                    { "width": "15%" },
                    { "width": "25%" },
                    { "width": "25%" },
                    { "width": "15%" },
                    null
                ]
            });

			$(function () {
			  $('[data-toggle="tooltip"]').tooltip()
			})

            $('.chosen-select').chosen({width:"100%"});

        });

    </script>
@endsection

@section('stylesheets')
    <link href="{{ asset('/css/plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
@endsection