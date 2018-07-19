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
                                <li>{{ $error }}</li>
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
                                <label for="exampleInputEmail1">{{ trans('sellerBoats.name') }}</label>
                                <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="{{ trans('sellerBoats.name') }}" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">{{ trans('sellerBoats.matricula') }}</label>
                                <input type="text" name="matricula" class="form-control" id="exampleInputPassword1" placeholder="{{ trans('sellerBoats.matricula') }}"  value="{{ old('matricula') }}">
                            </div>
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
@endsection