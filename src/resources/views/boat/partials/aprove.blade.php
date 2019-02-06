@can('evaluateBoat',$boat)
    @if ($boat->status == \App\Constants::PENDIENTE)
        <div class="row">
            <div class="col-lg-12">
                <div class="jumbotron alert-warning">
                    <div class="container text-center">
                        <p><strong>{{ trans('boats.pending_message') }}</strong></p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">{{ trans('boats.accept_boat') }}</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">{{ trans('boats.reject_boat') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal inmodal fade" id="confirmModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <form action="{{ route('boats.approve', $boat) }}" method="post" style="display: inline-block;">
                <div class="modal-content">
                    {{--<div class="modal-header">--}}
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    {{--</div>--}}
                    <div class="modal-body text-center">
                        <h3>{{ trans('boats.accept_boat_confirm_msg') }}</h3>
                    </div>
                    <div class="modal-footer">

                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Aprobando...">{{ trans('general.accept') }}</button>

                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                    </div>
                </div>
                </form>
            </div>
        </div>


        <div class="modal inmodal fade" id="rejectModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form action="{{ route('boats.reject', $boat) }}" class="form-group" method="post" style="display: inline-block;">
                        {{ csrf_field() }}
                        {{--<div class="modal-header">--}}
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{--</div>--}}
                        <div class="modal-body text-center">
                            <h3>{{ trans('boats.reject_boat_confirm_msg') }}</h3>
                        </div>
                        <textarea class="form-control" style="margin 5px" name="motivo" id="" cols="30" rows="10" required placeholder="Motivo del rechazo"></textarea>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Rechazando..." >{{ trans('general.reject') }}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endif
@endcan