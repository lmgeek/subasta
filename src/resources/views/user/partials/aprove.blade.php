@can('evaluateUser',Auth::user())
    @if ($user->status == \App\Constants::PENDIENTE)
        <div class="row">
            <div class="col-lg-12">
                <div class="jumbotron alert-warning">
                    <div class="container text-center">
                        <p><strong>{{ trans('users.pending_message') }}</strong></p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">{{ trans('users.accept_user') }}</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">{{ trans('users.reject_user') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal inmodal fade" id="confirmModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    {{--<div class="modal-header">--}}
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    {{--</div>--}}
                    <div class="modal-body text-center">
                        <h3>{{ trans('users.accept_user_confirm_msg') }}</h3>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('users.approve', $user) }}" method="post" style="display: inline-block;">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Aprobando...">{{ trans('general.accept') }}</button>
                        </form>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('general.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal inmodal fade" id="rejectModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form action="{{ route('users.reject', $user) }}" class="form-group" method="post" style="display: inline-block;">
                        {{ csrf_field() }}
                        {{--<div class="modal-header">--}}
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{--</div>--}}
                        <div class="modal-body text-center">
                            <h3>{{ trans('users.reject_user_confirm_msg') }}</h3>
                        </div>
                        <textarea class="form-control" name="motivo" id="" cols="30" rows="10" required placeholder="Motivo del rechazo"></textarea>
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