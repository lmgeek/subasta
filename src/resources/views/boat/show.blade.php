@extends('admin')


@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('boats.boat_detail') }}</h2>
        </div>
        <div class="col-lg-3 text-right">
            <h2>
                <a href="{{ route('boats.index') }}" class="btn btn-danger">{{ trans('general.back') }}</a>
            </h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
         @include('boat.partials.aprove') 
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="ibox float-e-margins">
                   
                    <div class="ibox-content">
                        <span class="label label-{{ \App\ViewHelper::boatStatusClass($boat->status) }} badge-user-status">{{ trans("general.status.$boat->status") }}</span>
                        <dl>
                            <dt>{{ trans('boats.name') }}</dt>
                            <dd>{{ $boat->name }}</dd>
                            <br>
							<dt>{{ trans('boats.buyer.label') }}</dt>
							<dd>{{ $boat->user->name }}</dd>
							<br>
                            <dt>{{ trans('boats.matricula') }}</dt>
                            <dd>{{ $boat->matricula }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection