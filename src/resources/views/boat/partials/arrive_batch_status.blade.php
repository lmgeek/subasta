<?php $batches = $lastArrive->batch  ?>
@if(count($batches) > 0)
    <script src="/js/plugins/star_rating/jquery.raty.js"></script>
    <link rel="stylesheet" href="/css/plugins/star_rating/jquery.raty.css">
    <table class="table table-hover table-bordered">
        <tbody>
        <thead>
        <tr>
            <td><strong>{{ trans("sellerBoats.product") }}</strong></td>
            <td><strong>{{ trans("sellerBoats.total") }}</strong></td>
            <td><strong>{{ trans("sellerBoats.sales") }}</strong></td>
            <td><strong>{{ trans("sellerBoats.type_sales") }}</strong></td>
            <td><strong>{{ trans("sellerBoats.assigned_auction") }}</strong></td>
            <td><strong>{{ trans("sellerBoats.nickname.title")}}</strong></td>
            <td></td>
        </tr>
        </thead>
        @foreach($batches as $batch)
            <?php
            $assigned_auction = $batch->status->assigned_auction;
            $auction_sold = $batch->status->auction_sold;
            $private_sold = $batch->privateSale->sum('amount');
            $remainder = $batch->status->remainder;
            $total = $batch->amount;
            $total_sold = $auction_sold + $private_sold;
            $total_unsold = $assigned_auction + $remainder;
            $total_sold_unsold = $total_sold + $total_unsold;
            $minEditBatch = $assigned_auction + $total_sold;
            ?>
            <tr>
                <td>
                    <div id="estrellas_{{ $batch->id }}" style="display:none">
                        <div><strong>{{ trans('sellerBoats.caliber') }}
                                : </strong><span>{{ trans('general.product_caliber.'.$batch->caliber) }}</span><br><strong>{{ trans('sellerBoats.quality') }}
                                : </strong>
                            <div id='quality_{{ $batch->id }}' class='text-warning'
                                 style='font-size: 8px; display: inline-block;'></div>
                        </div>
                    </div>

                    {{ $batch->product->name  }}
                    <em class="fa fa-info-circle text-info" data-id="{{ $batch->id }}"
                       data-quality="{{ $batch->quality }}" id="indo_{{ $batch->id }}" data-toggle="tooltip"
                       data-placement="top" title="ddddd"></em>

                </td>
                <td>
                    <span>{{ $total }} </span>
                    <a data-target="#batch-edit-Modal-{{ $batch->id }}" data-toggle="modal" href="#">
                      
                        <em data-toggle="tooltip"  title="Editar lote" class="fa fa-edit">
                        </em>
                       
                    </a>
                </td>
                <td>
                    <div>
                        <strong>{{ trans("sellerBoats.solds") }} :</strong> {{$total_sold}}
                        <div class="" style="width:94px;">
                            <div data-toggle="tooltip" data-placement="right" class="progress progress-mini"
                                 title="<?php echo ($total_sold / $total_sold_unsold) * 100 ?>%"
                                 style="background-color:#ddd;height:8px">
                                <div style="height:8px;width: <?php echo ($total_sold / $total_sold_unsold) * 100 ?>%;"
                                     title="Vendidos: {{$total_sold}}" class="progress-bar"></div>
                            </div>

                        </div>
                    </div>
                </td>

                <td>
                    <table>
                        <tr>
                            <td>
                                <strong style="color:#8e44ad">{{ trans("sellerBoats.auctions")  }}
                                    :</strong> {{$auction_sold}}
                            </td>
                            <td rowspan=2>
                                <div class="" style="width:30px; margin-left:5px">
                                    <span class="total-sold-auction-and-private-sale-{{$batch->id}}"></span>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <strong style="color:#16a085">{{ trans("sellerBoats.private")  }}
                                    &nbsp: </strong> {{$private_sold}}
                            </td>
                        </tr>
                    </table>
                    <script>
                        $(".total-sold-auction-and-private-sale-{{$batch->id}}").sparkline([<?php echo $auction_sold ?>, <?php echo $private_sold ?>], {
                            type: 'pie',
                            //tooltipFormat: '@{{offset:offset}} @{{value}}',
                            tooltipValueLookups: {
                                'offset': {
                                    0: '{{trans("sellerBoats.sold_at_auction")}}',
                                    1: '{{trans("sellerBoats.sold_at_private_sale")}}'
                                }
                            },
                            height: '25px',
                            sliceColors: ['#8e44ad', '#16a085']
                        });
                    </script>
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <strong>{{ trans("sellerBoats.unsolds") }}
                                    :</strong> {{$total_sold_unsold - $total_sold}}</td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong style="color:#2980b9">{{ trans("sellerBoats.assigned")  }}
                                    &nbsp:</strong> {{$assigned_auction}}
                            </td>
                            <td rowspan=2>
                                <div class="" style="width:30px; margin-left:5px">
                                    <span class="total-assigned-auction-and-unassigned-{{$batch->id}}"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong style="color:#a7b1c2">{{ trans("sellerBoats.unassigned")  }}
                                    : </strong> {{$remainder}}
                            </td>
                        </tr>
                    </table>
                    <script>
                        $(".total-assigned-auction-and-unassigned-{{$batch->id}}").sparkline([<?php echo $assigned_auction ?>, <?php echo $remainder ?>], {
                            type: 'pie',
                            //tooltipFormat: '@{{offset:offset}} @{{value}}',
                            tooltipValueLookups: {
                                'offset': {
                                    0: '{{trans("sellerBoats.assigned_auction")}}',
                                    1: '{{trans("sellerBoats.unassigned_auction")}}'
                                }
                            },
                            height: '25px',
                            sliceColors: ['#2980b9', '#a7b1c2']
                        });
                    </script>
                </td>

                <td>{{$boat->nickname}}</td>

                <td>
                    @if( !$batch->product->trashed() )
                        @can('createAuction',$batch)
                            @if($remainder>0)
                                <a href="{{ route('auction.create_from_batch',$batch) }}" class="btn btn-action">Crear
                                    subasta</a>
                            @endif
                            @endif

                            @can('deleteBatch',$batch)
                                <a href="{{ route('sellerboat.batch.delete',$batch) }}"
                                   class="btn btn-action deleteBatch">Borrar
                                    Lote</a>
                            @endcan

                            @can('makeDirectBid',$batch)
                                @if($remainder>0)
                                    <a href="{{ url('/priavatesale/'.$batch->id) }}" class="btn btn-action">Venta
                                        Privada</a>
                                @endif
                            @endcan
                            @endif

                            <div class="modal inmodal fade" id="batch-edit-Modal-{{ $batch->id }}" tabindex="-1"
                                 role="dialog"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <form action="{{ url('editbatch') }}" method="post" style="display: inline-block;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3>Editar Lote de {{ $batch->product->name  }} </h3>
                                                {{--<div class="alert alert-danger" id="error-modal">--}}
                                                    {{--<strong>Error</strong><br><br>--}}
                                                    {{--<ul id="error-ul">--}}
                                                    {{--</ul>--}}
                                                {{--</div>--}}
                                            </div>
                                            <div class="modal-body text-center">

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-5">
                                                            <div><h3>Vendidos</h3></div>
                                                            <div class="amountBatch">{{ $total_sold  }}</div>
                                                            <div><h3>Asignados</h3></div>
                                                            <div class="amountBatch">{{ $assigned_auction }}</div>

                                                        </div>
                                                        <div class="col-lg-1">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div><h3>Total</h3></div>
                                                            <br>
                                                            <div class="text-center">
                                                                <input type="hidden" name="hBatchId"
                                                                       value="{{ $batch->id  }}">
                                                                <input type="number" min="1"
                                                                      @if ($assigned_auction  != 0 || $private_sold != 0)
                                                                      disabled
                                                                      @endif
                                                                       value="{{ $total_sold_unsold  }}" name="amount"
                                                                       class="form-control bfh-number" required/>
                                                                <small>{{ trans('general.product_units.'.$batch->product->unit) }}</small>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="modal-footer">

                                                {{ csrf_field() }}
                                                <button type="submit"
                                                        @if ($assigned_auction  != 0 || $private_sold != 0)
                                                            disabled
                                                        @endif
                                                        class="btn btn-primary">{{ trans('general.accept') }}</button>

                                                <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">{{ trans('general.cancel') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                </td>
            </tr>

            @endforeach

            </tbody>
    </table>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').each(function (k, v) {
                var id = $(v).data('id');
                var quality = $(v).data('quality');

                $('#quality_' + id).raty({
                    readOnly: true,
                    score: quality,
                    starType: 'i',
                    hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
                });

                $(v).attr('title', $('#estrellas_' + id).html());

                $(v).tooltip({
                    html: true
                });
            })

        });

        $(document).ready(function () {
            $(".deleteBatch").unbind().click(function () {
                if (!confirm('¿Está seguro que desea borrar este lote?')) {
                    return false;
                }
            });

            if ($("#error-modal").hide()){
                console.log("nada");
            }
            $("#error-ul").empty();

            $('#batch-edit-Modal-{{ $batch->id }}').on('hidden.bs.modal', function () {
                $("#error-modal").hide();
                $("#error-ul").empty();
                console.log({{ $batch->id }});
            });


            $("#add_batch").click(function () {
                if (checkForm()) {
                    $('.btn-save-batch').attr('disabled', false);
                    $('#addBatchModal').modal('hide');
                }
            });

            function checkForm() {
                var isOK = true;
                var msg = [];
                console.log($('#quality').raty('score'));

                if ($("#amount").val() <= 0) {
                    isOK = false;
                    msg.push('{{ trans('sellerBoats.batch_must_amount_positive') }}');
                }

                if (!isOK) {
                    // $("#error-modal").show();
                    $("#error-ul").empty();
                    msg.forEach(function (index) {
                        $("#error-ul").append("<li>" + index + "</li>");
                    });
                }

                return isOK;
            }
        });


    </script>
@endif

<style>
    .tooltip-inner {
        white-space: pre-wrap;
    }
</style>
