@extends('admin')


@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('sellerBoats.boat_arrive_title') }}</h2>
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
                        <h5>{{ trans('sellerBoats.boat_arrive_info') }}</h5>
                    </div>
                    <form action="{{ route('sellerboat.arrive') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="ibox-content">
                            <div class="form-group">
                                <label for="boat_id">{{ trans('sellerBoats.boat') }}</label>
                                <select class="form-control" id="boat_id" name="barco">
                                    <option>Seleccione...</option>
                                    @foreach($boats as $boat)
                                        <option @if($boat_id == $boat->id) selected @endif value="{{ $boat->id }}">{{ $boat->nickname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="arrival_id">Puerto</label>
                                <select class="form-control" id="arrival_id" name="puerto">
                                    <option disabled selected>Seleccione...</option>
                                    @foreach($ports as $port)
                                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="boat">{{ trans('sellerBoats.datetime') }}</label>
                                <div style="overflow:hidden; border: 1px solid #E7EAEC; padding: 15px 15px 0px 15px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="datetimepicker12"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="date" name="date" value="">
                            </div>
                        </div>
                        <div class="ibox-footer text-right">
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Guardando...">{{ trans('sellerBoats.save_arrive') }}</button>
                            <a href="{{ url('sellerbatch') }}" class="btn btn-danger">{{ trans('sellerBoats.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/plugins/moment/moment.js"></script>
    <script src="/js/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">
        var dp;
        $(function () {
            dp = $('#datetimepicker12').datetimepicker({
                locale: '{{ Config::get('app.locale') }}',
                inline: true,
                sideBySide: true
            });

            $("#date").val(dp.data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:SS'));

            dp.on("dp.change", function(e) {
                $("#date").val(dp.data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:SS'));
            });

        });
    </script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="/css/plugins/datetimepicker/bootstrap-datetimepicker.css">
@endsection
