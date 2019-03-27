<?php
$products = array($auction->batch->productDetail->product);
//            dd($products);
foreach ($products as $p) {
    $name = $p[0]->name;
}
?>

<div id="small-dialog-ver-ventas-{{$auction->id}}" class="small-dialog zoom-anim-dialog mfp-hide dialog-with-tabs" style="    padding: 0!important;">

    <!--Tabs -->
    <div class="sign-in-form">

        <ul class="popup-tabs-nav">
            <li><a href="#tab">Subastas del Mar</a></li>
        </ul>

        <div class="popup-tabs-container">

            <!-- Tab -->
            <div class="popup-tab-content" id="tab">

                <!-- Welcome Text -->
                <div class="welcome-text">
                    <h2 class="fw700 text-left t32 lsp-1">Ventas Realizadas</h2>
                </div>


                <!-- Bidding -->
                <div class="bidding-widget">
                    <ul class="dashboard-box-list">
                        @if( count($auction->bids) > 0 )

                            @foreach($auction->bids as $b)
                                <li>
                                    <div class="boxed-list-item">
                                        <!-- Content -->
                                        <div class="item-content">
                                            <h4 class="primary">{{ $b->user->nickname }}</h4>
                                            <span class="dashboard-status-button @if($b->status == 'pending') yellow @elseif($b->status == 'concretized') green @elseif($b->status == 'noConcretized') red @endif">
                                                                {{ trans('general.bid_status.'.$b->status) }}
                                                            </span>
                                            <div class="item-details margin-top-10">
                                                <div class="detail-item"><i
                                                            class="icon-material-outline-date-range"></i> {{ \App\Constants::formatDateOffer($b->created_at) }}
                                                </div>
                                                <div class="detail-item"><i class="icon-line-awesome-money"></i>
                                                    ${{ number_format($b->price,2,',','.') }}</div>
                                                <div class="detail-item"><i
                                                            class="icon-line-awesome-balance-scale"></i> {{ $b->amount }}{{ $auction->batch->productDetail->sale_unit }}
                                                </div>
                                                <div class="detail-item"><i class="icon-material-outline-gavel"></i>
                                                    @if ($b->offer_id != 0)
                                                        {{ trans('auction.'.\App\Constants::OFFER_ORIGIN) }}
                                                    @else
                                                        {{ trans('auction.'.\App\Constants::AUCTION_ORIGIN) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <div class="text-center">No hay ventas asociadas.</div>
                        @endif


                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>