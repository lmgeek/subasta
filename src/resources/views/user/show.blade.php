@extends('admin')


@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('users.user_detail') }}</h2>
        </div>
        <div class="col-lg-3 text-right">
            <h2>
                <a href="{{ route('users.index') }}" class="btn btn-danger">{{ trans('general.back') }}</a>
            </h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        @include('user.partials.aprove')
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">

					<div class="ibox float-e-margins">
						<div class="ibox-title label-{{ $user->type }}">
							<h5>{{ trans("general.users_type.$user->type") }}</h5>
						</div>
						<div class="ibox-content">
							<span class="label label-{{ \App\ViewHelper::userStatusClass($user->status) }} badge-user-status">{{ trans("general.status.$user->status") }}</span>
							<dl>
								<dt>{{ trans('users.name') }}</dt>
								<dd>{{ $user->name }}</dd>
								<br>
								<dt>{{ trans('users.email') }}</dt>
								<dd>{{ $user->email }}</dd>
								<br>
								<dt>{{ trans('users.phone') }}</dt>
								<dd>{{ $user->phone }}</dd>
								<br>

								@include(\App\ViewHelper::includeUserInfo($user->type))
								@if ($user->status == \App\User::RECHAZADO)
									<dt>{{ trans('users.rebound') }}</dt>
									<dd>
										<p>{{ $user->rebound }}</p>
									</dd>
								@endif
							</dl>
						</div>
					</div>

            </div>
			<div class="col-lg-4">

					<div class="widget style1 lazur-bg">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-usd fa-3x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
								<h2 class="font-bold">
								@if ($user->type == \App\User::VENDEDOR )
									Total ventas: {{ number_format($total,2,',','.') }}
								@endif
								@if ($user->type == \App\User::COMPRADOR )
									Total Compras: {{ number_format($total,2,',','.') }}
								@endif

								</h2>
                            </div>
                        </div>
                    </div>
					@if ($user->type == \App\User::VENDEDOR )
						<div class="widget style1 lazur-bg">
							<div class="row vertical-align">
								<div class="col-xs-3">
									<i class="fa fa-usd fa-3x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<h3 class="font-bold" style="font-size:28px;">
									@if ($user->type == \App\User::VENDEDOR )
										Ventas privadas: {{ number_format($total2,2,',','.') }}
									@endif
									</h3>
								</div>
							</div>
						</div>
					@endif
					<div class="widget navy-bg p-lg text-center">
						<div class="m-b-md">
							<i class="fa fa-thumbs-up fa-4x"></i>
							<h1 class="m-xs">{{ $score }} % </h1>
							<h3 class="font-bold no-margins">
								@if ($user->type == \App\User::VENDEDOR )
									De Compradores
								@endif
								@if ($user->type == \App\User::COMPRADOR )
									De Vendedores
								@endif

							</h3>
							<small>lo recomiendan</small>
							@if ($user->rating != null )
								<h5>Total Votos</h5>
								<div>
									<span data-toggle="tooltip" data-placement="top" title="Votos Positivos" >{{ $user->rating->positive }} <i class="fa fa-plus-circle"></i></span> |
									<span data-toggle="tooltip" data-placement="top" title="Votos Negativos">{{ $user->rating->negative }} <i class="fa fa-minus-circle"></i></span> |
									<span data-toggle="tooltip" data-placement="top" title="Votos Neutro">{{ $user->rating->neutral }} <i style="" class="fa fa-dot-circle-o"></i></span>
								</div>
							@endif
						</div>
					</div>


			</div>
        </div>
		<div class="row">
			<div class="col-lg-12">
				   @include('user.partials.summary'.$user->type)
			</div>
		</div>
    </div>
	<script>
		$(function(){ $('[data-toggle="tooltip"]').tooltip(); });
	</script>
@endsection