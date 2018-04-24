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




{{-- <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('auction.auction_list') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            

            
            <div class="col-lg-12" style="margin-top: 10px">
                <div class="ibox float-e-margins">
                
                    <div class="ibox-title">
                        <h5>{{ trans('auction.auctions') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($auctions) == 0)
                            <div class="text-center">
                                {{ trans('auction.no_auction') }}
                            </div>
                        @else
                            @foreach ($auctions as $a)
                                <div class="auction row">
                                    <div class="col-md-2">
                                        @include('auction.partials.auctionInfo')
                                    </div>
                                    <div class="col-md-3 text-center">
                                        @include('auction.partials.auctionPrice')
                                    </div>
                                    <div class="col-md-2">
                                        @if($a->active == \App\Auction::ACTIVE)
                                            @include('auction.partials.auctionAvailability')
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                            @include('auction.partials.auctionTime')
                                    </div>
                                     <div class="col-md-2">
                                        @if(isset($sellerAuction) and $sellerAuction==true)
                                            @if($status == \App\Auction::MY_IN_CURSE or $status == \App\Auction::MY_FINISHED)
                                                @include('auction.partials.auctionDetail')
                                            @else
                                              <br>
                                            @endif
                                            
                                            @if( $status != \App\Auction::MY_FINISHED )
                                                <div class="col-md-2">
                                                    @if($a->active == \App\Auction::ACTIVE)
                                                        <a class="btn btn-action cancelAuction" href="{{ route('auction.deactivate',$a) }}">{{trans('auction.btnauction_cancel')}}</a>
                                                        @if($status == \App\Auction::MY_FUTURE )
                                                            <a class="btn btn-action" href="{{ route('auction.edit',$a) }}">Editar</a>
                                                        @endif
                                                    @else
                                                        <div style="margin-left:115px;margin-bottom:22px">
                                                            <span class="label label-danger pull-right">{{trans('auction.auction_cancel')}}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            @if($a->active == \App\Auction::ACTIVE)
                                                @include('auction.partials.auctionBid')
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div> --}}





















    <div class="row">
        <div class="row text-center wow fadeInDown terminated-items">

            <div class="col-md-2 col-lg-2 col-sm-6 ">
                <div>
                    <img class="img-pez" alt="image" src="{{ asset('/img/products/1.png') }}" alt="">
                </div>
                <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
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
                            $cadena = "Mariano Ramirez";
                            echo '<span class="usertext" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                            
                                strlen($cadena);
                                if (strlen($cadena) >= 8) {
                                    echo strtoupper(substr($cadena, 0, 7) ). "...";
                                } elseif (strlen($cadena) <= 7) {
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
                                
                                strlen($cadena);
                                if (strlen($cadena) >= 14) {
                                    echo strtoupper(substr($cadena, 0, 13) ). "...";
                                } elseif (strlen($cadena) <= 13) {
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
                    $cadena = "Mis dulces amores queridos";
                    echo '<span class="barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                </span>
                
                <br>
                
                <?php
                    $cadena = "Buque Pesquero y cañero";
                    echo '<span class="tipo-barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?></span>
                
                <br>
                
                <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
                    <input name="score" type="hidden" value="5" readonly="">
                </div>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 tiempo">
                <input type="text"
                   class        = "dial dialLeft m-r"
                   value        = "400"
                   data-max     = "720"
                   data-min     = "0"
                   name         = "amount"
                   data-fgColor = "#00e4a1"
                   data-width   = "70"
                   data-height  = "70"
                   data-angleArc= 250
                   data-angleOffset=-125
                   data-readOnly= true
                />
                <span class="priceText">TIEMPO<br>RESTANTE</span>

            </div>

            <div class="col-md-2 col-lg-2 col-sm-6 product-details">
                <div class="detalle">
                    <?php
                    $cadena = "Calamarete del norte y sur pacifico";
                    echo '<span style="font-weight: 600" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                </span><br> 
                    <?php
                    $cadena = "Longfin Inshore Squid netlabs";
                    echo '<span style="font-size: 10px; color: #00e4a1" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 24) {
                            echo strtoupper(substr($cadena, 0, 23) ). "...";
                        } elseif (strlen($cadena) <= 23) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                    </span><br>
                    <span style="font-size: 11px;">DISPONIBILIDAD</span><br>
                    <span style="font-size: 25px;">300/1500</span> 
                    <span class="peso">Kg.</span>
                    <div class="progress">
                        <div style="width: 43%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="43" role="progressbar" class="progress-bar">
                        </div>
                    </div>
                    <span style="color: #7D7D7D;">120/150 CAJONES</span>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                 <span class="symbol">$</span> 
                 <font class="font">70</font> 
                 <span class="peso">x Kg.</span><br>
            {{-- <button class="btn btn-success"><i class="fa fa-gavel"></i> IR SUBASTA</button> --}}
            <a href="#">
                <img alt="image" width="150px"  src="{{ asset('/landing/img/subastar.png') }}" />
            </a><br>
                <div class="showdetail">
                    <a href="#" >VER DETALLE</a>
                </div>
            
            </div>
        </div>


        <div class="row text-center wow fadeInDown terminated-items">

            <div class="col-md-2 col-lg-2 col-sm-6 ">
                <div>
                    <img class="img-pez" alt="image" src="{{ asset('/img/products/1.png') }}" alt="">
                </div>
                <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
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
                            $cadena = "Mariano Ramirez";
                            echo '<span class="usertext" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                            
                                strlen($cadena);
                                if (strlen($cadena) >= 8) {
                                    echo strtoupper(substr($cadena, 0, 7) ). "...";
                                } elseif (strlen($cadena) <= 7) {
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
                                
                                strlen($cadena);
                                if (strlen($cadena) >= 14) {
                                    echo strtoupper(substr($cadena, 0, 13) ). "...";
                                } elseif (strlen($cadena) <= 13) {
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
                    $cadena = "Mis dulces amores queridos";
                    echo '<span class="barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                </span>
                
                <br>
                
                <?php
                    $cadena = "Buque Pesquero y cañero";
                    echo '<span class="tipo-barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?></span>
                
                <br>
                
                <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
                    <input name="score" type="hidden" value="5" readonly="">
                </div>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 tiempo">
                <input type="text"
                   class        = "dial dialLeft m-r"
                   value        = "400"
                   data-max     = "720"
                   data-min     = "0"
                   name         = "amount"
                   data-fgColor = "#00e4a1"
                   data-width   = "70"
                   data-height  = "70"
                   data-angleArc= 250
                   data-angleOffset=-125
                   data-readOnly= true
                />
                <span class="priceText">TIEMPO<br>RESTANTE</span>

            </div>

            <div class="col-md-2 col-lg-2 col-sm-6 product-details">
                <div class="detalle">
                    <?php
                    $cadena = "Calamarete del norte y sur pacifico";
                    echo '<span style="font-weight: 600" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                </span><br> 
                    <?php
                    $cadena = "Longfin Inshore Squid netlabs";
                    echo '<span style="font-size: 10px; color: #00e4a1" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 24) {
                            echo strtoupper(substr($cadena, 0, 23) ). "...";
                        } elseif (strlen($cadena) <= 23) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                    </span><br>
                    <span style="font-size: 11px;">DISPONIBILIDAD</span><br>
                    <span style="font-size: 25px;">300/1500</span> 
                    <span class="peso">Kg.</span>
                    <div class="progress">
                        <div style="width: 43%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="43" role="progressbar" class="progress-bar">
                        </div>
                    </div>
                    <span style="color: #7D7D7D;">120/150 CAJONES</span>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                 <span class="symbol">$</span> 
                 <font class="font">70</font> 
                 <span class="peso">x Kg.</span><br>
            {{-- <button class="btn btn-success"><i class="fa fa-gavel"></i> IR SUBASTA</button> --}}
            <a href="#">
                <img alt="image" width="150px"  src="{{ asset('/landing/img/subastar.png') }}" />
            </a><br>
                <div class="showdetail">
                    <a href="#" >VER DETALLE</a>
                </div>
            
            </div>
        </div>


        <div class="row text-center wow fadeInDown terminated-items">

            <div class="col-md-2 col-lg-2 col-sm-6 ">
                <div>
                    <img class="img-pez" alt="image" src="{{ asset('/img/products/1.png') }}" alt="">
                </div>
                <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
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
                            $cadena = "Mariano Ramirez";
                            echo '<span class="usertext" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                            
                                strlen($cadena);
                                if (strlen($cadena) >= 8) {
                                    echo strtoupper(substr($cadena, 0, 7) ). "...";
                                } elseif (strlen($cadena) <= 7) {
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
                                
                                strlen($cadena);
                                if (strlen($cadena) >= 14) {
                                    echo strtoupper(substr($cadena, 0, 13) ). "...";
                                } elseif (strlen($cadena) <= 13) {
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
                    $cadena = "Mis dulces amores queridos";
                    echo '<span class="barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                </span>
                
                <br>
                
                <?php
                    $cadena = "Buque Pesquero y cañero";
                    echo '<span class="tipo-barco" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?></span>
                
                <br>
                
                <div data-score="5" class="quality text-stars" data-toggle="tooltip" data-placement="bottom" data-original-title="Calidad de 5 Estrellas">
                    <input name="score" type="hidden" value="5" readonly="">
                </div>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 tiempo">
                <input type="text"
                   class        = "dial dialLeft m-r"
                   value        = "400"
                   data-max     = "720"
                   data-min     = "0"
                   name         = "amount"
                   data-fgColor = "#00e4a1"
                   data-width   = "70"
                   data-height  = "70"
                   data-angleArc= 250
                   data-angleOffset=-125
                   data-readOnly= true
                />
                <span class="priceText">TIEMPO<br>RESTANTE</span>

            </div>

            <div class="col-md-2 col-lg-2 col-sm-6 product-details">
                <div class="detalle">
                    <?php
                    $cadena = "Calamarete del norte y sur pacifico";
                    echo '<span style="font-weight: 600" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 18) {
                            echo strtoupper(substr($cadena, 0, 17) ). "...";
                        } elseif (strlen($cadena) <= 17) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                </span><br> 
                    <?php
                    $cadena = "Longfin Inshore Squid netlabs";
                    echo '<span style="font-size: 10px; color: #00e4a1" data-toggle="tooltip" data-placement="top" data-original-title="'.$cadena.'">';
                    
                        strlen($cadena);
                        if (strlen($cadena) >= 24) {
                            echo strtoupper(substr($cadena, 0, 23) ). "...";
                        } elseif (strlen($cadena) <= 23) {
                            echo strtoupper($cadena);
                        }   
                    ?>
                    </span><br>
                    <span style="font-size: 11px;">DISPONIBILIDAD</span><br>
                    <span style="font-size: 25px;">300/1500</span> 
                    <span class="peso">Kg.</span>
                    <div class="progress">
                        <div style="width: 43%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="43" role="progressbar" class="progress-bar">
                        </div>
                    </div>
                    <span style="color: #7D7D7D;">120/150 CAJONES</span>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                 <span class="symbol">$</span> 
                 <font class="font">70</font> 
                 <span class="peso">x Kg.</span><br>
            {{-- <button class="btn btn-success"><i class="fa fa-gavel"></i> IR SUBASTA</button> --}}
            <a href="#">
                <img alt="image" width="150px"  src="{{ asset('/landing/img/subastar.png') }}" />
            </a><br>
                <div class="showdetail">
                    <a href="#" >VER DETALLE</a>
                </div>
            
            </div>
        </div>




        
    </div>