@extends('admin')

@section('content')
    <?php use Carbon\Carbon; ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('auction.auction_operations',['productName'=>$auction->batch->product->name]) }}</h2>
        </div>
        <div class="col-lg-3 text-right">
            <h2>
                <a href="{{ url('sellerAuction') }}" class="btn btn-danger">{{ trans('general.back') }}</a>
            </h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 10px">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('auction.operations') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
									<th></th>
                                    <th>{{ trans('auction.buyer') }}</th>
                                    <th>{{ trans('auction.amount') }}</th>
                                    <th>{{ trans('auction.unity_price') }}</th>
                                    <th>{{ trans('auction.price') }}</th>
                                    <th>{{ trans('auction.date') }}</th>
                                    <th>{{ trans('auction.status') }}</th>
                                    <th>{{ trans('auction.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auction->bids as $b)
                                    <tr>
										<td>
											@if ($b->seller_calification == \App\Bid::CALIFICACION_POSITIVA)
												<span class="text-navy" data-toggle="tooltip" data-placement="top" title="{{ $b->seller_calification_comments }}"  style="font-size:18px;"><i class="fa fa-plus-circle"></i> </span>
											@endif
											@if ($b->seller_calification == \App\Bid::CALIFICACION_NEGATIVA)
												<span class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ $b->seller_calification_comments }}"  style="font-size:18px;"><i class="fa fa-minus-circle"></i> </span>
											@endif
											@if ($b->seller_calification == \App\Bid::CALIFICACION_NEUTRAL)
												<span data-toggle="tooltip" data-placement="top" title="{{ $b->seller_calification_comments }}"  style="color:#BABABA;font-size:18px;"><i class="fa fa-dot-circle-o"></i> </span>
											@endif
										</td>
                                        <td>
                                            <a href="#" class="showUserInfo" data-name="{{ $b->user->name }}" data-phone="{{ $b->user->phone }}" data-email="{{ $b->user->email }}">
                                                {{ $b->user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $b->amount }} {{ trans('general.product_units.'.$auction->batch->product->unit) }}</td>
                                        <td style="text-align: right">$ {{ number_format($b->price,2,',','.') }}</td>
                                        <td style="text-align: right">$ {{ number_format($b->price * $b->amount,2,',','.') }}</td>
                                        <td style="text-align: right">{{ Carbon::parse($b->bid_date)->format('H:i:s d/m/Y') }}</td>
                                        <td>
                                            @if ($b->status == \App\Bid::PENDIENTE)
                                                {{ trans('general.bid_status.'.$b->status) }}
                                            @else
                                                <a href="#" class="showStatusInfo" data-status="{{ trans('general.bid_status.'.$b->status) }}"  data-userCal="{{ trans('general.buyer_qualification.'.$b->user_calification) }}" data-UserCalCom="{{ $b->user_calification_comments }}" data-reason="{{ $b->reason }}">
                                                    {{ trans('general.bid_status.'.$b->status) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('qualifyBid',$b)
                                                @if ($b->status == \App\Bid::PENDIENTE)
                                                    <a href="{{ route('auction.operations.process',$b) }}" class="btn-action">{{ trans('auction.process') }}</a>
                                                @endif
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
    <div class="modal inmodal fade" id="userInfo" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ trans('auction.buyer_info') }}</h3>
                </div>
                <div class="modal-body text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: right; width:50%; padding-right: 5px; font-weight: bold">{{ trans('auction.name') }}:</td>
                            <td style="text-align: left" id="userName"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width:50%; padding-right: 5px; font-weight: bold">{{ trans('auction.phone') }}:</td>
                            <td style="text-align: left" id="userPhone"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width:50%; padding-right: 5px; font-weight: bold">{{ trans('auction.email') }}:</td>
                            <td style="text-align: left" id="userEmail"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal fade" id="statusInfo" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ trans('auction.operation_status') }}</h3>
                </div>
                <div class="modal-body text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: right; width:30%; padding-right: 5px; font-weight: bold">{{ trans('auction.status') }}:</td>
                            <td style="text-align: left" id="status"></td>
                        </tr>
                        <tr id="negativeReason" style="display: none;">
                            <td style="text-align: right; width:30%; padding-right: 5px; font-weight: bold">{{ trans('auction.reason') }}:</td>
                            <td style="text-align: left" id="reason"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width:30%; padding-right: 5px; font-weight: bold">{{ trans('auction.buyer_qualification') }}</td>
                            <td style="text-align: left" id="userCal"></td>
                        </tr>
                        <tr id="comments">
                            <td style="text-align: right; width:30%; padding-right: 5px; font-weight: bold">{{ trans('auction.comments') }}:</td>
                            <td style="text-align: left" id="UserCalCom"></td>
                        </tr>
                    </table>
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

    <script>
        $(document).ready(function () {
		
			
			$('[data-toggle="tooltip"]').tooltip()
			

            $('.showStatusInfo').click(function(){

                var status = $(this).data('status');
                var reason = $(this).data('reason');
                var userCal = $(this).data('usercal');
                var UserCalCom = $(this).data('usercalcom');

                $('#status').html(status);

                if(status == '{{ trans('general.bid_status.'.\App\Bid::NO_CONCRETADA) }}'){
                    $('#negativeReason').show();
                }
                $('#reason').html(reason);

                $('#userCal').html(userCal);
                if(userCal == '{{ trans('general.buyer_qualification.'.\App\Bid::CALIFICACION_POSITIVA) }}' && UserCalCom == "" ) {
                    $("#comments").hide();
                }
                $('#UserCalCom').html(UserCalCom);

                $('#statusInfo').modal('show');
                return false;
            });

            $('.showUserInfo').click(function(){

                var name = $(this).data('name');
                var phone = $(this).data('phone');
                var email = $(this).data('email');

                $('#userName').html(name);
                $('#userPhone').html(phone);
                $('#userEmail').html(email);

                $('#userInfo').modal('show');
                return false;
            });



            $('.dataTables-example').DataTable({
                "bFilter": false,
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
                    { "width": "15%" },
                    { "width": "15%" },
                    { "width": "15%" },
                    { "width": "20%" },
                    null
                ]
            });

            $('.chosen-select').chosen({width:"100%"});

        });

    </script>
@endsection

@section('stylesheets')

@endsection

