<section  class="container features">

    <div class="row">
        <div class="col-lg-12 wow fadeInDown terminated">
            <img alt="image"  src="{{ asset('/landing/img/terminando.png') }}" />
        </div>
    </div>
             {{--  Resolucion de pantalla
                 La resolución actual de tu pantalla es:
            <script language="JavaScript">
            document.writeln(screen.width + " x " + screen.height)
            </script>

            <br>
            <br>

            Tu pantalla la consideramos:
            <script language="JavaScript">
            if (screen.width<1024)
                document.write ("Pequeña")
            else
                if (screen.width<1280)
                    document.write ("Mediana")
                else
                    document.write ("Grande")
            </script> --}}
@if (count($auctions) == 0)
    <div class="text-center">
        <strong><em>
            {{ trans('auction.no_auction_home') }}
        </em></strong>
    </div>
@else
    <? $items = 0; ?>
    @foreach ($auctions as $a)
        <? if ( $items < 5 ) { ?>

        <div class="row">
            <div class="row text-center wow fadeInDown terminated-items">

                <div class="col-md-2 col-lg-2 col-sm-6 ">
                    <div>
                        <input type="hidden" class="auctionIds" value="{{ $a->id }}" />
                        <img class="img-pez img-responsive" alt="image" src="{{ asset('/img/products/'.$a->batch->product->image_name) }}" alt="">
                    </div>
                    <div data-score="{{ $a->batch->quality }}" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de {{ $a->batch->quality }} Estrellas">
                        <input name="score" type="hidden" value="5" readonly="">
                    </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-6" >

                    <table >
                        <tr>
                            <td style="padding: 10px"></td>
                        </tr>
                        <tr >
                            <td rowspan="4" width="15%">
                                <img alt="image" class="user-faro" src="{{ asset('/landing/img/faro.png') }}"/>
                            </td>
                            <td width="10%">
                                <img alt="image" class="user-data"  src="{{ asset('/landing/img/usuario.png') }}"/>
                            </td>
                            <td>
                                <span class="user-title">USUARIO</span>

                                <?php
                                $cadena = $a->batch->arrive->boat->user->name;
                                $userId = $a->batch->arrive->boat->user->id;

                                if ($userRating[$userId] > 0){ ?>
                                    <span class="usertext" data-toggle="tooltip" data-placement="top" title="{{ $a->batch->arrive->boat->user->name }},  {{ $userRating[$userId]  }}% {{trans('users.reputability.seller')}}" class="fa fa-info-circle" >
                                <? } else {

                                    echo '<span class="usertext" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                                }

                                        $longitudcadena=strlen($cadena);
                                    if ($longitudcadena >= 8) {
                                        echo strtoupper(substr($cadena, 0, 7) ). "...";
                                    } elseif ($longitudcadena <= 7) {
                                        echo strtoupper($cadena);
                                    }
                                ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td">
                                <span class="user-title">PUERTO</span>

                                <?php
                                $cadena = "Mar del Plata";
                                echo '<span class="usertext" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                                $longitudcadena=strlen($cadena);
                                    if ($longitudcadena >= 14) {
                                        echo strtoupper(substr($cadena, 0, 13) ). "...";
                                    } elseif ($longitudcadena <= 13) {
                                        echo strtoupper($cadena);
                                    }
                                ?></span>
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="col-md-2 col-lg-2 col-sm-6 barco-details" >
                    <img alt="image" width="35px"  src="{{ asset('/landing/img/barco.png') }}"  />
                    <br>

                    <?php
                        $cadena = $a->batch->arrive->boat->name ;
                        echo '<span class="barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    $longitudcadena=strlen($cadena);
                            if ($longitudcadena >= 18) {
                                echo strtoupper(substr($cadena, 0, 17) ). "...";
                            } elseif ($longitudcadena <= 17) {
                                echo strtoupper($cadena);
                            }
                        ?>
                    </span>

                    <br>

                    <?php
                        $cadena = "Buque Pesquero y cañero";
                        echo '<span class="tipo-barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    $longitudcadena=strlen($cadena);
                            if ($longitudcadena >= 18) {
                                echo strtoupper(substr($cadena, 0, 17) ). "...";
                            } elseif ($longitudcadena <= 17) {
                                echo strtoupper($cadena);
                            }
                        ?></span>

                    <br>

                    <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
                        <input name="score" type="hidden" value="5" readonly>
                    </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-6 tiempo">

                   @if( ($status == \App\Auction::IN_CURSE or $status == \App\Auction::MY_IN_CURSE) and $a->active == \App\Auction::ACTIVE)
                            <?
                            $fechaActual = date('Y-m-d H:i:s');
                            $fechaInicial = $a->start;
                            $fechaFinal = $a->end;
                            $segundos = strtotime($fechaFinal) - strtotime($fechaActual);
                            $segFinal = strtotime($fechaFinal) - strtotime($fechaInicial);

                            ?>
                            <input type="text"
                                   class="dial dialLeft m-r"
                                   value="{{ $segundos }}"
                                   data-max="{{ $segFinal }}"
                                   data-min="0"
                                   name="amount"
                                   data-fgColor="#00e4a1"
                                   data-width="70"
                                   data-height="70"
                                   auctionId = "{{ $a->id }}"
                                   active = "{{ $a->active }}"
                                   data-angleArc= 250
                                   data-angleOffset=-125
                                   data-readOnly= true
                                   readonly
                                    />
                    @endif


                    <span class="priceText">TIEMPO<br>RESTANTE</span>

                </div>

                {{-- reloj intervalo --}}
                <div class="row" style="display: none;">
                    <div class="col-lg-6 col-xs-12">
                        @if($status != \App\Constants::FINISHED and $status != \App\Constants::MY_FINISHED )
                            <div class="priceText currentPrice-{{ $a->id }}">$ {{ $a->start_price }}</div>
                        @else
                            <div class="priceText currentPrice-{{ $a->id }}">$ {{ $a->end_price }}</div>
                        @endif
                    </div>
                    @if( ($status == \App\Constants::IN_CURSE or $status == \App\Constants::MY_IN_CURSE) and $a->active == \App\Constants::ACTIVE)
                        <div class="col-lg-6 col-xs-12" style="margin-top: 8px" title="{{ trans('auction.next_price_update') }}">
                            <input type="text"
                                   class="dial dialInterval m-r"
                                   value="{{ $a->getTimeToNextInterval() }}"
                                   data-max="{{ $a->interval * 60 }}"
                                   {{--data-rotation="anticlockwise"--}}
                                   data-min="0"
                                   name="amount"
                                   data-fgColor="#1AB394"
                                   data-width="70"
                                   data-height="70"
                                   auctionId = "<?php echo $a->id ?>"
                                   active = "<?php echo $a->active ?>"
                                   data-angleArc= 250
                                   data-angleOffset=-125
                                   data-readOnly= true
                                   readonly
                            />
                        </div>
                    @endif
                </div>
            {{-- reloj intervalo fin --}}

                <div class="col-md-2 col-lg-2 col-sm-6 product-details">
                    <div class="detalle">
                        <?php
                        $cadena = $a->batch->product->name. " " .trans('general.product_caliber.'.$a->batch->caliber);
                        echo '<span style="font-weight: 600" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';

                        $longitudcadena=strlen($cadena);
                            if ($longitudcadena >= 18) {
                                echo strtoupper(substr($cadena, 0, 17) ). "...";
                            } elseif ($longitudcadena <= 17) {
                                echo strtoupper($cadena);
                            }
                        ?>
                    </span><br>
                        <?php
                        $cadena = "Longfin Inshore Squid netlabs";
                        echo '<span style="font-size: 10px; color: #00e4a1" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';

                        $longitudcadena=strlen($cadena);
                            if ($longitudcadena >= 24) {
                                echo strtoupper(substr($cadena, 0, 23) ). "...";
                            } elseif ($longitudcadena <= 23) {
                                echo strtoupper($cadena);
                            }
                        ?>
                        </span><br>
                        <?
//                            dd( $a->batch->caliber);
                        if ( $a->batch->caliber == 'small' ) {
                            $weigth = $a->batch->product->weigth_small;
                        }
                        if ( $a->batch->caliber == 'medium' ) {
                            $weigth = $a->batch->product->weigth_medium;
                        }
                        if ( $a->batch->caliber == 'big' ) {
                            $weigth = $a->batch->product->weigth_big;
                        }
//                        $weigth = $a->batch->product->weigth;
                        $vendido = 0;
                        foreach ($a->bids()->where('status','<>',\App\Bid::NO_CONCRETADA)->get() as $b) {
                            $vendido+= $b->amount;
                        }
                        $subtotal = $a->amount;
                        $disponible = ($subtotal-$vendido)*$weigth;
                        $total = $subtotal * $weigth;
                        ?>
                        <span style="font-size: 11px;">DISPONIBILIDAD</span><br>
                        <? $longitudcadena=strlen($disponible);
                        if ($longitudcadena == 5) {
                            echo '<span style="font-size: 21px;">';
                        } if ($longitudcadena > 5) {
                            echo '<span style="font-size: 17px;">';
                        } else {
                            echo '<span style="font-size: 25px;">';
                        }?>
                        {{ str_replace('.',',',$disponible) }}/{{ str_replace('.',',',$total) }}</span>
                        <span class="peso">Kg.</span>
                        @if($a->active == \App\Auction::ACTIVE)
                            @include('auction.partials.auctionAvailabilityHome')
                        @endif

                    </div>
                </div>

                <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                    @if($status != \App\Auction::FINISHED and $status != \App\Auction::MY_FINISHED )
                        {{-- <span class="symbol">$</span> --}}
                        <div class="font1 currentPrice-{{ $a->id }}">
                            <span class="symbol">$</span>
                            <font class="font">{{ $a->start_price }}</font>
                            <span class="peso">x Kg.</span><br>
                        </div>

                    @else
                        <div class="currentPrice-{{ $a->id }}">
                            <span class="symbol">$</span>
                            <font class="font">{{ $a->end_price }}</font>
                            <span class="peso">x Kg.</span><br>
                    </div>
                    @endif

                    @if($a->active == \App\Auction::ACTIVE)
                        @include('auction.partials.auctionBidHome')
                    @endif
                    <div class="showdetail">
                        <a href="#" >VER DETALLE</a>
                    </div>

                </div>
            </div>
        </div>


        <? $items++;
        } ?>
    @endforeach
@endif




