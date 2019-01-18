<?php
use App\Auction;
//Creamos un objeto de la clase para usar sus funciones
$objtAuction = new Auction();
?>

<!doctype html>
<html lang="es">
<head>

    <title>Subastas del Mar</title>
    @include('landing3/partials/common')

</head>
<body>

<!-- Wrapper -->
<div id="wrapper" class="wrapper-with-transparent-header">

    <!-- Header Container
    ================================================== -->
@include('landing3/partials/header')
<!-- Header Container / End -->
<?php
/*        function cmp($a, $b){
            return strcmp($a["end"], $b["end"]);
        }*/?>
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
                    <div class="intro-banner-search-form margin-top-60">

                        <!-- Search Field -->
                        <div class="intro-search-field">
                            <label for="where-input" class="field-title ripple-effect bg-secondary-light">&iquest;Qu&eacute; puerto prefieres?</label>
                            <div class="input-with-icon">
                                <select class="selectpicker" multiple title="Escoge una opción...">
                                    @foreach($portsall as $port)
                                        <option value="{{$port->id}}">{{$port->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <!-- Search Field -->
                        <div class="intro-search-field">
                            <label for ="intro-keywords" class="field-title ripple-effect bg-secondary-light">&iquest;Qu&eacute; est&aacute;s buscando?</label>
                            <input id="intro-keywords" type="text" placeholder="ej. Subasta, Vendedor o Producto">
                        </div>

                        <!-- Button -->
                        <div class="intro-search-button">
                            <button class="button ripple-effect" {{--onclick="#"--}}>Buscar</button>
                        </div>
                    </div>
                </div>
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
                            <?php
                            function cmp($a, $b){
                                return strcmp($a["end"], $b["end"]);
                            }
                            if(isset($auctions)){
                                usort($auctions,'cmp');
                            }
                            if(isset($auctionsf)){
                                usort($auctionsf,'cmp');
                            }
                            ?>

                        @foreach($auctions as $auction)
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
                        <?php $contadorsubastasdestacadas=0;$finished=1;
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
    <div id="notificationsauction"></div>

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
                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/mdq.jpg">
                        <div class="photo-box-content">
                            <h3>Mar del Plata</h3>
                            <span>{{(isset($ports[1]))?$ports[1]['cant']:0}} Subasta<?=(isset($ports[1]))?(($ports[1]['cant']!=1)?'s':''):'s'?></span>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/caba.jpg">
                        <div class="photo-box-content">
                            <h3>Buenos Aires</h3>
                            <span>{{(isset($ports[2]))?$ports[2]['cant']:0}} Subasta<?=(isset($ports[2]))?(($ports[2]['cant']!=1)?'s':''):'s'?></span>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/madryn.jpg">
                        <div class="photo-box-content">
                            <h3>Puerto Madryn</h3>
                            <span>{{(isset($ports[3]))?$ports[3]['cant']:0}} Subasta<?=(isset($ports[3]))?(($ports[3]['cant']!=1)?'s':''):'s'?></span>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/lavalle.jpg">
                        <div class="photo-box-content">
                            <h3>General Lavalle</h3>
                            <span>{{(isset($ports[4]))?$ports[4]['cant']:0}} Subasta<?=(isset($ports[4]))?(($ports[4]['cant']!=1)?'s':''):'s'?></span>
                        </div>
                    </a>
                </div>

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

    <!-- Footer
    ================================================== -->
    <div id="footer">

        <!-- Footer Top Section -->
    @include('landing3/partials/footer-top')
    <!-- Footer Top Section / End -->

        <!-- Footer Middle Section -->
    @include('landing3/partials/footer-mid')
    <!-- Footer Middle Section / End -->

        <!-- Footer Copyrights -->
    @include('landing3/partials/copyright')
    <!-- Footer Copyrights / End -->

    </div>
    <!-- Footer / End -->

</div>
<!-- Wrapper / End -->


@include('landing3/partials/pop-up-register-login')

<!-- Scripts
================================================== -->
@include('landing3/partials/js')
<script type="text/javascript">
    // window.onload = setTimeout(swapDiv, 9000);
    // window.onload = setTimeout(swapDiv2, 18000);
    // function swapDiv() {
    //     $("#div_1").swap({
    //         target: "div_2", // Mandatory. The ID of the element we want to swap with
    //         opacity: "0.8", // Optional. If set will give the swapping elements a translucent effect while in motion
    //         speed: 1000, // Optional. The time taken in milliseconds for the animation to occur
    //     });
    //     $("#precio_2").text("$10");
    // }
    // function swapDiv2() {
    //     $("#div_3").swap({
    //         target: "div_2", // Mandatory. The ID of the element we want to swap with
    //         opacity: "0.8", // Optional. If set will give the swapping elements a translucent effect while in motion
    //         speed: 1000, // Optional. The time taken in milliseconds for the animation to occur
    //     });
    //     $("#precio_3").text("$190");
    // }

</script>
<script src="js/jquery-ui.min.js"></script>
<script>
    jQuery(document).ready(function(){

        $('.blink_me').animatedBG({
            colorSet: ['#dc3545', '#a01321']
        });

    });
</script>


</body>
</html>