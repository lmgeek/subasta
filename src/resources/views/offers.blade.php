<div class="row">
        <div class="col-lg-12 wow fadeInDown offers">
            <img alt="image"  src="{{ asset('/landing/img/ofertas_tituloseccion.png') }}" />
        </div>
    </div>
<!--
    <div class="row">
        <div class="row text-center wow fadeInDown offers-items">
           <div class="col-md-2 col-lg-2 col-sm-6 ">
                <div>
                    <img class="img-pez" alt="image" src="{{ asset('/img/products/2.png') }}" alt="">
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
            <div class="col-md-6 col-lg-6 desc-product">
                   <p class="prod-name">FILET DE MERLUZA X KILO</p>
                   <p class="prod-desc">FRESCO SIN ESPINAS</p>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                 <div>
                     <span class="symbol">$</span> 
                    <font class="font">70</font> 
                 </div>
            {{-- <button class="btn btn-success"><i class="fa fa-gavel"></i> IR SUBASTA</button> --}}
            <a href="#">
                <img alt="image" width="124px"  src="{{ asset('/landing/img/ofertar.png') }}" />
            </a><br>
                <div class="showdetail">
                    <a href="#" >VER DETALLE</a>
                </div>
            
            </div>
        </div>
        

        <div class="row text-center wow fadeInDown offers-items">
           <div class="col-md-2 col-lg-2 col-sm-6 ">
                <div>
                    <img class="img-pez" alt="image" src="{{ asset('/img/products/2.png') }}" alt="">
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
            <div class="col-md-6 col-lg-6 desc-product">
                   <p class="prod-name">FILET DE MERLUZA X KILO</p>
                   <p class="prod-desc">FRESCO SIN ESPINAS</p>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                 <div>
                     <span class="symbol">$</span> 
                    <font class="font">70</font> 
                 </div>
            {{-- <button class="btn btn-success"><i class="fa fa-gavel"></i> IR SUBASTA</button> --}}
            <a href="#">
                <img alt="image" width="124px"  src="{{ asset('/landing/img/ofertar.png') }}" />
            </a><br>
                <div class="showdetail">
                    <a href="#" >VER DETALLE</a>
                </div>
            
            </div>
        </div>

        <div class="row text-center wow fadeInDown offers-items">
            <div class="col-md-2 col-lg-2 col-sm-6 ">
                <div>
                    <img class="img-pez" alt="image" src="{{ asset('/img/products/2.png') }}" alt="">
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
            <div class="col-md-6 col-lg-6 desc-product">
                   <p class="prod-name">FILET DE MERLUZA X KILO</p>
                   <p class="prod-desc">FRESCO SIN ESPINAS</p>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 subastar">
                 <div>
                     <span class="symbol">$</span> 
                    <font class="font">70</font> 
                 </div>
            {{-- <button class="btn btn-success"><i class="fa fa-gavel"></i> IR SUBASTA</button> --}}
            <a href="#">
                <img alt="image" width="124px"  src="{{ asset('/landing/img/ofertar.png') }}" />
            </a><br>
                <div class="showdetail">
                    <a href="#" >VER DETALLE</a>
                </div>
            
            </div>
        </div>
    </div>-->