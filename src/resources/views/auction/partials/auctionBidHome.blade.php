<?php
$vendido = 0;
foreach ($a->bids()->where('status', '<>', \App\Bid::NO_CONCRETADA)->get() as $b) {
    $vendido += $b->amount;
}

$total = $a->amount;
$disponible = $total - $vendido;

?>

<div class="row" style="margin-top: 22px;margin-left:0px;">
    @can('canBid', \App\Auction::class)
        <div class="">
            @if ($disponible > 0 and $status != \App\Auction::FUTURE)
                <a href="#"
                   @if(Auth::user()->status != "approved")
                   data-target="#bid-Modal-danger-pending"
                   @else
                   data-target="<? if (Auth::user()->type == \App\User::VENDEDOR){ ?> #bid-Modal-danger <? } else {?> #bid-Modal-{{ $a->id }} <? } ?>"
                   @endif()
                   data-toggle="modal">
                    <img alt="image" width="150px" src="{{ asset('/landing/img/subastar.png') }}"/>
                </a>
            @endif
        </div>
    @else
        <? if (Auth::user()){ ?>
        <a href="#"
           @if(Auth::user()->status != "approved")
           data-target="#bid-Modal-danger-pending"
           @else
           data-target="<? if (Auth::user()->type == \App\User::VENDEDOR){ ?> #bid-Modal-danger <? } else {?> #bid-Modal-warning <? } ?>"
           @endif()
           data-toggle="modal">
            <img alt="image" width="150px" src="{{ asset('/landing/img/subastar.png') }}"/>
        </a>
        <? } else { ?>
        <a href="{{ url('/auction') }}">
            <img alt="image" width="150px" src="{{ asset('/landing/img/subastar.png') }}"/>
        </a>
        <? } ?>
    @endcan
    <div class="modal inmodal fade modal-bid" id="bid-Modal-{{ $a->id }}" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3>Comprar Producto</h3>
                </div>
                <div class="modal-body text-center">
                    <div>
                        @include('auction.partials.auctionInfoModal')


                        <strong>Disponible: <span
                                    class="s-disponible-{{ $a->id }} target-total" target="{{ $disponible }}">{{ $disponible }}  {{ trans('general.product_units.'.$a->batch->product->unit) }}</span>
                            <br> Precio Unitario: <span class="currentPrice-{{ $a->id }}"></span></strong>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" method="post" style="display: inline-block;">
                                    {{ csrf_field() }}
                                    <div class="row"><br>
                                        <div class="col-md-12" style="height: 20px; padding: 0px 0px 0px 83px;">
                                            <div class="col-md-6">
                                                <input type="number" style="width:110px"
                                                       min="<?= ($disponible > 0) ? '1' : '0' ?>" max="{{$disponible}}"
                                                       placeholder="Cantidad" auctionId="{{ $a->id }}"
                                                       class="form-control bfh-number amount-bid amount-bid-modal"
                                                       id="amount-bid-{{ $a->id }}" min="1" pattern="^[0-9]+"/>
                                            </div>
                                            <div class="col-md-4" style="margin-top: 7px;">
                                                <strong><span
                                                            class="modal-unit-{{ $a->id }}">{{ trans('general.product_units.'.$a->batch->product->unit) }}</span>
                                                </strong>
                                            </div>
                                            <input type="hidden" value="" class="hid-currentPrice-{{ $a->id }}"/>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="priceText2 modal-total-{{ $a->id }} modal-price "></div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="content-danger content-danger-{{ $a->id }}">

                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" data-loading-text="Comprando..." auctionId="{{ $a->id }}"
                            class=" noDblClick mak-bid-{{ $a->id }} make-bid btn btn-primary">{{ trans('auction.action_bid') }}</button>
                    <button type="button" class="btn btn-danger close-btn-bid"
                            data-dismiss="modal">{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Warning -->
    <div class="modal inmodal fade modal-bid" id="bid-Modal-warning" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3>Alerta</h3>
                </div>
                <div class="alert alert-warning">
                    <strong> {{ trans('auction.bid_limit')  }}  </strong>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close-btn-bid"
                            data-dismiss="modal">{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal danger -->
    <div class="modal inmodal fade modal-bid" id="bid-Modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3>Alerta</h3>
                </div>
                <div class="alert alert-danger">
                    <strong> {{ trans('auction.unauthorized')  }}  </strong>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close-btn-bid"
                            data-dismiss="modal">{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal no Authorize danger -->
    <div class="modal inmodal fade modal-bid" id="bid-Modal-danger-pending" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3>Alerta</h3>
                </div>
                <div class="alert alert-danger">
                    <strong> {{ trans('auction.pending')  }}  </strong>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close-btn-bid"
                            data-dismiss="modal">{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>


</div>