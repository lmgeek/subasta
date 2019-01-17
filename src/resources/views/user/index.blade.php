@extends('admin')



@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('users.title') }}</h2>
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
                    <form>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-noraml">Tipo de usuario</label>
                                        <select data-placeholder="Seleccione una o varias opciones" name="type[]" class="chosen-select" multiple tabindex="4">
                                            <option @if (!is_null($request->get('type')) and in_array(\App\User::COMPRADOR,$request->get('type'))) selected @endif value="{{ \App\User::COMPRADOR }}">{{ trans('general.users_type.'.\App\User::COMPRADOR) }}</option>
                                            <option @if (!is_null($request->get('type')) and in_array(\App\User::VENDEDOR,$request->get('type'))) selected @endif value="{{ \App\User::VENDEDOR }}">{{ trans('general.users_type.'.\App\User::VENDEDOR) }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-noraml">Estado</label>
                                        <select data-placeholder="Seleccione una o varias opciones" name="status[]" class="chosen-select" multiple tabindex="4">
                                            <option @if (!is_null($request->get('status')) and in_array(\App\User::PENDIENTE,$request->get('status'))) selected @endif value="{{ \App\User::PENDIENTE }}">{{ trans('general.status.'.\App\User::PENDIENTE) }}</option>
                                            <option @if (!is_null($request->get('status')) and in_array(\App\User::APROBADO,$request->get('status'))) selected @endif value="{{ \App\User::APROBADO }}">{{ trans('general.status.'.\App\User::APROBADO) }}</option>
                                            <option @if (!is_null($request->get('status')) and in_array(\App\User::RECHAZADO,$request->get('status'))) selected @endif value="{{ \App\User::RECHAZADO }}">{{ trans('general.status.'.\App\User::RECHAZADO) }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="font-noraml">Nombre</label>
										<input type="text" value="<?= (null != $request->get('name')) ? $request->get('name') : '' ?>" class="form-control input-sm" name="name" id="name"/>
									</div>
								</div>
								<div class="col-md-6">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                </div>
							</div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('users.users') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover dataTables-example">
                            <thead>
                            <tr role="row">
                                <th class="sorting">
                                    {{ trans('users.type') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('users.name') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('users.email') }}
                                </th>
                                <th class="sorting">
                                    {{ trans('users.status') }}
                                </th>
                                <th>
                                    {{ trans('users.actions.title') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td> <!--class="color-{{ $user->type }}" -->
                                            {{ trans("general.users_type.$user->type") }}
                                        @if ($userRating[$user->id] > 0)
                                                <i data-toggle="tooltip" data-placement="right" title="{{ $userRating[$user->id]  }}% {{trans('users.reputability.'.$user->type)}}" class="fa fa-info-circle" ></i>
                                            @endif

                                        </td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="label label-{{ $user->status }}">{{ trans("general.status.$user->status") }}</span>
                                        </td>
                                        <td>
                                            @can('seeUserDetail',Auth::user())
                                                <a href="{{ route('users.show',$user) }}" class="btn-action">{{ trans('users.actions.view') }}</a>
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
    <script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
    <script src="/js/plugins/chosen/chosen.jquery.js"></script>

    <script>
        $(document).ready(function () {
			$('[data-toggle="tooltip"]').tooltip();

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

            $('.chosen-select').chosen({width:"100%"});

        });

    </script>
@endsection

@section('stylesheets')
    <link href="/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    {{--<link href="/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">--}}
    <link href="/css/plugins/chosen/chosen.css" rel="stylesheet">
@endsection