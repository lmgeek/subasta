<?php
use App\Auction;
//Creamos un objeto de la clase para usar sus funciones
$objtAuction = new Auction();
$portsall= App\Ports::select()->get();
?>
@extends('landing3/partials/layout')
@section('title',' | Home')
@section('content')
    <!-- Intro Banner
    ================================================== -->
    <!-- add class "disable-gradient" to enable consistent background overlay -->
    <div class="intro-banner dark-overlay big-padding bd-bt-2">

        <!-- Transparent Header Spacer -->
        <div class="transparent-header-spacer hidden-lg"></div>

        <div class="container">

            <!-- Intro Headline -->
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-headline-alt">
                        <h3 class="txt-shdw">Reg&iacute;strate. Oferta. Disfruta.</h3>
                        <span>Adquiere productos de alta calidad en solo 3 pasos.</span>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="row">
                <div class="col-md-12">
                    <form method="get" action="/subastas" class="intro-banner-search-form margin-top-60">
                        <input type="hidden" name="e" value="FilterHome">
                        <input type="hidden" name="t" value="Filter">
                        <input type="hidden" name="ex" value="" id="ExtraParamsAnalytics">
                        <!-- Search Field -->
                        <div class="intro-search-field">
                            <label for="where-input" class="field-title ripple-effect bg-secondary-light">&iquest;Qu&eacute; puerto prefieres?</label>
                            <div class="input-with-icon">
                                <select class="selectpicker" id="port" name="port_id" multiple title="Escoge una opción..." onchange="homeFilterBuilder()">
                                    @foreach($ports as $key=>$value)
                                        <option value="{{$key}}">{{\App\Http\Controllers\AuctionController::getPortById($key)}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <!-- Search Field -->
                        <div class="intro-search-field">
                            <label for ="query" class="field-title ripple-effect bg-secondary-light">&iquest;Qu&eacute; est&aacute;s buscando?</label>
                            <input id="query" name="q" type="text" placeholder="ej. Subasta, Vendedor o Producto" onkeyup="homeFilterBuilder()">
                        </div>

                        <!-- Button -->
                        <div class="intro-search-button">
                            <button type="submit" class="button ripple-effect">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Stats -->
            <div class="row">
                <div class="col-md-12">
                    <ul class="intro-stats margin-top-45 hide-under-992px">
                        <li>
                            <strong class="counter white">586</strong>
                            <span>Toneladas diarias</span>
                        </li>
                        <li>
                            <strong class="counter white">{{count($boats)}}</strong>
                            <span>Barcos pesqueros</span>
                        </li>
                        <li>
                            <strong class="counter white">{{count($buyers)}}</strong>
                            <span>Clientes satisfechos</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>


    <!-- Features auctions -->
    <div class="section gray padding-top-65 padding-bottom-75">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Section Headline -->
                    <div class="section-headline margin-top-0 margin-bottom-35">
                        <h2>Subastas destacadas</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="/subastas" class="headline-link">Ver todas las subastas</a>
                    </div>
                <?php $contadorsubastasdestacadas=0;?>

                <!-- Auctions Container -->
                    <div class="tasks-list-container margin-top-35"  id="FeaturedAuctions">
                        @if(count($auctions)>0)
                            @foreach($auctions as $auction)
                                @if($contadorsubastasdestacadas<3)
                                    <?php $contadorsubastasdestacadas++;?>
                                    @include('/landing3/partials/auctionNoDetail')
                                @endif
                            @endforeach
                        @else
                            <h1 class="text-center">No hay Subastas para mostrar</h1>
                        @endif

                    </div>

                    <!-- Auctions Container / End -->

                </div>
            </div>

            <!-- Finalizadas -->
            <div class="row margin-top-35">
                <div class="col-xl-12">

                    <!-- Section Headline -->
                    <div class="section-headline margin-top-0 margin-bottom-35">
                        <h2>Subastas finalizadas</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>

                    <!-- Auctions Container -->
                    <div class="tasks-list-container margin-top-35 bg-disabled" id="FinishedAuctions">

                        @if(count($auctionsf)>0)
                            <?php $contadorsubastasdestacadas=0;$finished='&iexcl;Finalizada!';
                            ?>
                            @foreach($auctionsf as $auction)
                                @if($contadorsubastasdestacadas<3)
                                    <?php $contadorsubastasdestacadas++;?>
                                    @include('/landing3/partials/auctionNoDetail')
                                @endif
                            @endforeach
                        @endif

                    </div>

                    <!-- Auctions Container / End -->

                </div>
            </div>

        </div>
    </div>
    <!-- Featured Auctions / End -->

    <!-- Features Ports -->
    <div class="section padding-top-65 padding-bottom-65 bd-bt-1">
        <div class="container">
            <div class="row">

                <!-- Section Headline -->
                <div class="col-xl-12">
                    <div class="section-headline centered margin-top-0 margin-bottom-45">
                        <h3>Principales puertos</h3>
                        <p>Busca las subastas m&aacute;s cercanas a tu lugar de residencia.</p>
                    </div>
                </div>
<?php $portsimg=array('mdq.jpg','caba.jpg','madryn.jpg','lavalle.jpg');
for($z=0;$z<count($portsall);$z++){
    $cantsubastas=(isset($ports[$portsall[$z]['id']]))?$ports[$portsall[$z]['id']]:0;
    ?>
                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="<?=($cantsubastas>0)?'/subastas?port_id='.$portsall[$z]['id']:'#'?>" class="photo-box" data-background-image="landing3/images/puertos/<?=$portsimg[$z]?>">
                        <div class="photo-box-content">
                            <h3><?=$portsall[$z]['name']?></h3>
                            <span>{{$cantsubastas}} Subasta<?=(isset($ports[$portsall[$z]['id']]))?(($ports[$portsall[$z]['id']]!=1)?'s':''):'s'?></span>
                        </div>
                    </a>
                </div>
<?php }?>
            </div>
        </div>
    </div>
    <!-- Featured Ports / End -->

    <!-- Content
    ================================================== -->
    <!-- Como funciona Boxes -->
    <div class="section gray padding-top-65 padding-bottom-65">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">

                    <div class="section-headline centered margin-bottom-20">
                        <h3>&iquest;C&oacute;mo funciona?</h3>
                        <p>Es muy f&aacute;cil: s&oacute;lo necesitas seguir 4 sencillos pasos.</p>
                    </div>

                    <!-- Category Boxes Container -->
                    <div class="categories-container">

                        <!-- Category Box -->
                        <a href="#sign-in-dialog" class="category-box register-tab sign-in popup-with-zoom-anim">
                            <div class="category-box-icon">
                                <i class="icon-line-awesome-user-plus"></i>
                            </div>
                            <div class="category-box-content">
                                <h3>Registro de usuario</h3>
                                <p>Crea tu usuario e ingresa.</p>
                            </div>
                        </a>

                        <!-- Category Box -->
                        <a href="subastas-list.php" class="category-box">
                            <div class="category-box-icon">
                                <i class="icon-line-awesome-search"></i>
                            </div>
                            <div class="category-box-content">
                                <h3>Navega el sitio</h3>
                                <p>Encuentra lo que buscas.</p>
                            </div>
                        </a>

                        <!-- Category Box -->
                        <a href="#" class="category-box">
                            <div class="category-box-icon">
                                <i class="icon-line-awesome-legal"></i>
                            </div>
                            <div class="category-box-content">
                                <h3>Realiza tu compra</h3>
                                <p>Consigue el mejor precio.</p>
                            </div>
                        </a>

                        <!-- Category Box -->
                        <a href="#" class="category-box">
                            <div class="category-box-icon">
                                <i class="icon-line-awesome-smile-o"></i>
                            </div>
                            <div class="category-box-content">
                                <h3>Disfruta tu compra</h3>
                                <p>Coordina c&oacute;mo abonarla.</p>
                            </div>
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Como funciona Boxes / End -->
@endsection