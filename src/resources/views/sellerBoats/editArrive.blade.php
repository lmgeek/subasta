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
                    <form action="{{ route('sellerboat.updatearrive') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $arrive->id }}">
                        <div class="ibox-content">
                            <div class="form-group">
                                <label for="boat_id">{{ trans('sellerBoats.boat') }}</label>
                                <select class="form-control" id="boat_id" name="boat_id" disabled>
                                    <option></option>
                                    @foreach($boats as $boat)
                                        <option @if($arrive->boat->id == $boat->id) selected @endif value="{{ $boat->id }}">{{ $boat->name }}</option>
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
                            <button type="submit" class="btn btn-primary">{{ trans('sellerBoats.save_arrive') }}</button>
                            <a href="{{ url('home') }}" class="btn btn-danger">{{ trans('sellerBoats.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/js/plugins/datetimepicker/bootstrap-datetimepicker.js') }}"></script>

    <script type="text/javascript">
        var dp;
        $(function () {
            dp = $('#datetimepicker12').datetimepicker({
                locale: '{{ Config::get('app.locale') }}',
                inline: true,
                sideBySide: true,
                defaultDate: '{{ (!is_null(old('date')))? old('date') : $arrive->date }}'
            });

            $("#date").val(dp.data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:SS'));

            dp.on("dp.change", function(e) {
                $("#date").val(dp.data("DateTimePicker").date().format('YYYY-MM-DD HH:mm:SS'));
            });

        });
    </script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/datetimepicker/bootstrap-datetimepicker.css') }}">
@endsection
