



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
                                    <option>Mar del Plata</option>
                                    <option>Ciudad de Buenos Aires</option>
                                    <option>Puerto Madryn</option>
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
                            <button class="button ripple-effect" onclick="#">Buscar</button>
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
                            <strong class="counter white">543</strong>
                            <span>Barcos pesqueros</span>
                        </li>
                        <li>
                            <strong class="counter white">1,832</strong>
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
                        <a href="subastas-list.php" class="headline-link">Ver todas las subastas</a>
                    </div>
                <?php $contadorsubastasdestacadas=0;?>




                    <!-- Auctions Container -->
                    <div class="tasks-list-container margin-top-35">
                        @if(count($auctions)>0)
                            <?php
                            function cmp($a, $b){
                                return strcmp($a["end"], $b["end"]);
                            }
                            usort($auctions,'cmp');
                            ?>
                        @foreach($auctions as $auction)
                            @if($contadorsubastasdestacadas<3)
                                <?php $contadorsubastasdestacadas++;?>
                        <!-- Auction Listing -->
                        <div id="div_<?=$contadorsubastasdestacadas?>" class="task-listing auction" data-idauction="{{$auction->id}}">
                            <?php
                            setlocale(LC_TIME,'es_ES');
                            $fechafin=strftime('%d %b %Y', strtotime($auction->end));
                            switch ($auction->batch->caliber){
                                case 'small':$calibre='chico';break;
                                case 'medium':$calibre='mediano';break;
                                case 'big':$calibre='grande';break;
                            }
                            ?>
                            <!-- Auction Listing Details -->
                            <div class="task-listing-details">
                                <!-- Photo -->
                                <div class="task-listing-photo">
                                    <img src="{{ asset('/img/products/'.$auction->batch->product->image_name) }}" alt="{{$auction->batch->product->name}}">
                                </div>
                                <!-- Details -->
                                <div class="task-listing-description">
                                    <h3 class="task-listing-title">
                                        <a href="subasta.php">{{$auction->batch->product->name}} {{$calibre}}</a>
                                        <div class="star-rating" data-rating="{{$auction->batch->quality}}"></div>
                                        @if($auction->type!='public')
                                            <i class="t16 icon-feather-eye-off" data-tippy-placement="right" title="Subasta Privada" data-tippy-theme="dark"></i></h3>
                                    @endif
                                    </h3>


                                    <ul class="task-icons">
                                        <li><i class="icon-material-outline-access-time primary"></i><strong class="primary">{{$fechafin}}</strong></li>
                                        <li><i class="icon-material-outline-location-on"></i> {{$port[$auction->id]->name}}</li>
                                    </ul>
                                    <p class="task-listing-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <ul class="task-icons margin-top-20">
                                        <?php $userId = $auction->batch->arrive->boat->user->id;?>
                                        <li>
                                            <small>Vendedor</small><br>
                                            <strong>
                                                <i class="icon-feather-user"></i> <?=(isset($auction->batch->arrive->boat->user->nickname)?$auction->batch->arrive->boat->user->nickname:$auction->batch->arrive->boat->user->name)?>
                                            </strong><br>
                                            <div class="medal-rating {{strtolower($usercat[$userId])}}" data-rating="{{$usercat[$userId]}}"><span class="medal {{$usercat[$userId]}}-text"></span></div>
                                            </li>
                                        <li><small>Barco</small><br><strong><i class="icon-line-awesome-ship"></i> Barco V</strong><br>
                                            <div class="star-rating" data-rating="<?=(isset($userRating[$userId]))?round(($userRating[$userId]/20),1,PHP_ROUND_HALF_UP):''?>"></div></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="task-listing-bid">
                                <div class="task-listing-bid-inner">
                                    <div class="task-offers">
                                        <?php
                                        $vendido = 0;
                                        foreach ($auction->bids()->where('status','<>',\App\Bid::NO_CONCRETADA)->get() as $b) {
                                            $vendido+= $b->amount;
                                        }
                                        $total = $auction->amount;
                                        $disponible = ($total-$vendido);
                                        $cantofertas=count($auction->bids);
                                        ?>
                                        <p><small>Disponibilidad:</small> <strong>{{$disponible}} <small>de</small> {{$total}} kg</strong><br>
                                            @if($cantofertas>0)
                                            <small class="green fw700"><i class="icon-material-outline-local-offer green"></i>
                                                {{$cantofertas}} Ofertas Directas
                                            </small>
                                            @endif
                                        </p>
                                        <div class="pricing-plan-label billed-monthly-label red" >
                                            <strong class="red"id="precio_<?=$auction->id?>">${{$price[$auction->id]}}</strong>/ kg<br>
                                            <small class="red fw500" id="CloseTime{{$auction->id}}" style="display: none;">¡Cerca del precio límite!</small>
                                        </div>
                                        <div id="timer<?=$auction->id?>" class="countdown margin-bottom-0 margin-top-20 blink_me timerauction" data-timefin="{{$auction->end}}" data-timestart="{{$auction->start}}" data-id="<?=$auction->id?>"></div>
                                    </div>
                                    <div class="w100">
                                        <a href="#small-dialog-compra-{{$auction->id}}" class="button ripple-effect popup-with-zoom-anim w100">Comprar</a>
                                    </div>
                                    <div class="w100 text-center margin-top-5 t14">o puedes <a href="#small-dialog-oferta" class="sign-in popup-with-zoom-anim">realizar una oferta</a></div>
                                </div>
                            </div>
                        </div>
                                @include('/landing3/partials/pop-up-compra')
                                @include('landing3/partials/pop-up-oferta')
                            @endif
                        @endforeach
                        @endif
                        <!-- Auction Listing -->

                        <!-- Auction Listing -->
                        <div id="div_3" class="task-listing">

                            <!-- Auction Listing Details -->
                            <div class="task-listing-details">
                                <!-- Photo -->
                                <div class="task-listing-photo">
                                    <img src="landing3/images/subastas/subasta03.jpg" alt="Calamares">
                                </div>
                                <!-- Details -->
                                <div class="task-listing-description">
                                    <h3 class="task-listing-title"><a href="subasta.php">Calamar Chico/a</a> <div class="star-rating" data-rating="4.5"></div></h3>
                                    <ul class="task-icons">
                                        <li><i class="icon-material-outline-access-time primary"></i><strong class="primary"> 28 Dic 2018</strong></li>
                                        <li><i class="icon-material-outline-location-on"></i> Mar del Plata</li>
                                    </ul>
                                    <p class="task-listing-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <ul class="task-icons margin-top-20">
                                        <li><small>Vendedor</small><br><strong><i class="icon-feather-user"></i> netlabs</strong><br><div class="medal-rating bronze" data-rating="Bronze"><span class="medal bronze-text"></span></div></li>
                                        <li><small>Barco</small><br><strong><i class="icon-line-awesome-ship"></i> Barco II</strong><br><div class="star-rating" data-rating="3.5"></div></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="task-listing-bid">
                                <div class="task-listing-bid-inner">
                                    <div class="task-offers">
                                        <p><small>Disponibilidad:</small> <strong>2 <small>de</small> 40 kg</strong><br><small class="green fw700"><i class="icon-material-outline-local-offer green"></i> 2 Ofertas Directas</small></p>
                                        <div class="pricing-plan-label billed-monthly-label red"><strong class="red" id="precio_1">$100</strong>/ kg<br><small class="red fw500">&iexcl;Cerca del precio l&iacute;mite!</small></div>
                                        <div id="timer3" class="countdown green margin-bottom-0 margin-top-20"></div>
                                    </div>
                                    <div class="w100">
                                        <a href="#small-dialog-compra" class="button ripple-effect popup-with-zoom-anim w100">Comprar</a>
                                    </div>
                                    <div class="w100 text-center margin-top-5 t14">o puedes <a href="#small-dialog-oferta" class="sign-in popup-with-zoom-anim">realizar una oferta</a></div>
                                </div>
                            </div>
                        </div>

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
                    <div class="tasks-list-container margin-top-35">

                        <!-- Auction Listing -->
                        <div id="div_4" class="task-listing bg-disabled">

                            <!-- Auction Listing Details -->
                            <div class="task-listing-details">
                                <!-- Photo -->
                                <div class="task-listing-photo">
                                    <img src="landing3/images/subastas/subasta01.jpg" alt="Camarones">
                                </div>
                                <!-- Details -->
                                <div class="task-listing-description">
                                    <h3 class="task-listing-title"><a href="subasta.php">Lote de Caballa tierna</a> <div class="star-rating" data-rating="3.5"></div></h3>
                                    <ul class="task-icons">
                                        <li><i class="icon-material-outline-access-time"></i><strong> 25 Ene 2019</strong></li>
                                        <li><i class="icon-material-outline-location-on"></i> Mar del Plata</li>
                                    </ul>
                                    <p class="task-listing-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <ul class="task-icons margin-top-20">
                                        <li><small>Vendedor</small><br><strong><i class="icon-feather-user"></i> jlopez75</strong><br><div class="medal-rating silver" data-rating="Silver"><span class="medal silver-text"></span></div></li>
                                        <li><small>Barco</small><br><strong><i class="icon-line-awesome-ship"></i> Barco IV</strong><br><div class="star-rating" data-rating="4.9"></div></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="task-listing-bid">
                                <div class="task-listing-bid-inner">
                                    <div class="task-offers">
                                        <small>Disponibilidad:</small>
                                        <p><strong>0 <small>de</small> 40 kg</strong></p>
                                        <div class="pricing-plan-label"><strong id="precio_1">$100</strong>/ kg</div>
                                        <small>Precio final.</small>
                                        <div id="timer" class="countdown margin-bottom-0 margin-top-20">&iexcl;Finalizada!</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Auction Listing -->
                        <div id="div_5" class="task-listing bg-disabled">

                            <!-- Auction Listing Details -->
                            <div class="task-listing-details">
                                <!-- Photo -->
                                <div class="task-listing-photo">
                                    <img src="landing3/images/subastas/subasta02.jpg" alt="Cornalito">
                                </div>
                                <!-- Details -->
                                <div class="task-listing-description">
                                    <h3 class="task-listing-title"><a href="subasta.php">Oportunidad: Corvina y Raya</a> <div class="star-rating" data-rating="5.0"></div></h3>
                                    <ul class="task-icons">
                                        <li><i class="icon-material-outline-access-time"></i><strong class="primary"> 11 Ene 2019</strong></li>
                                        <li><i class="icon-material-outline-location-on"></i> Mar del Plata</li>
                                    </ul>
                                    <p class="task-listing-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <ul class="task-icons margin-top-20">
                                        <li><small>Vendedor</small><br><strong><i class="icon-feather-user"></i> gdesancho</strong><br><div class="medal-rating gold" data-rating="Gold"><span class="medal gold-text"></span></div></li>
                                        <li><small>Barco</small><br><strong><i class="icon-line-awesome-ship"></i> Barco I</strong><br><div class="star-rating" data-rating="4.0"></div></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="task-listing-bid">
                                <div class="task-listing-bid-inner">
                                    <div class="task-offers">
                                        <small>Disponibilidad:</small>
                                        <p><strong>0 <small>de</small> 240 kg</strong></p>
                                        <div class="pricing-plan-label billed-monthly-label"><strong id="precio_2">$650</strong>/ kg</div>
                                        <small>Precio final.</small>
                                        <div id="timer2" class="countdown margin-bottom-0 margin-top-20">&iexcl;Finalizada!</div>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/mdq.jpg">
                        <div class="photo-box-content">
                            <h3>Mar del Plata</h3>
                            <span>66 Subastas</span>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/caba.jpg">
                        <div class="photo-box-content">
                            <h3>Buenos Aires</h3>
                            <span>25 Subastas</span>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/madryn.jpg">
                        <div class="photo-box-content">
                            <h3>Puerto Madryn</h3>
                            <span>12 Subastas</span>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6">
                    <!-- Photo Box -->
                    <a href="#" class="photo-box" data-background-image="landing3/images/puertos/lavalle.jpg">
                        <div class="photo-box-content">
                            <h3>General Lavalle</h3>
                            <span>3 Subastas</span>
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
<script>
    var param=Math.floor((Math.random() * 7) + 1);
    function timer($id) {
        window['dateend']= new Date($("#timer"+$id).attr('data-timefin'));
        var now = new Date().getTime();
        var distance = window['dateend']- now,string='';
console.log($("#timer"+$id).attr('data-timefin'))
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        if(days!=0){string+=days+'d ';}
        if(hours!=0 || days!=0){string+=hours+'h ';}
        if(minutes!=0 || hours!=0 || days!=0){string+=minutes+'m ';}
        string+=seconds+'s';
        document.getElementById('timer'+$id).innerHTML = string;
        if (distance < 0) {
            document.getElementById('timer'+$id).innerHTML = "¡Finalizada!";
        }else{
            setTimeout(function(){timer($id);},1000);
        }
    }
    function getPrice($id){

        $.get('/calculateprice?auction_id='+$id+'&i=c',function(result){
            var $result=JSON.parse(result);
            $("#precio_"+$id).html('$'+$result['price']);
            window['dateend']=$result['endTime'];
            $('#timer'+$id).attr('data-timefin',window['dateend']);
            if($result['isClose']==1 && $('#CloseTime'+$id).css('display')=='none'){
                $('#CloseTime'+$id).fadeIn();
            }else{
                $('#CloseTime'+$id).fadeOut();
            }
            console.log($result)
        });
        setTimeout(function(){getPrice($id)},30000);
    }
    function makeBid(auctionId)
    {
        var cDispo =  parseInt($(".s-disponible-" +auctionId).html()),amount=$('#cantidad-{{$auction->id}}').val();

        if ( amount <= cDispo  ){
            $.ajax({
                method: "GET",
                dataType:"json",
                url: "/makeBid?auction_id="+auctionId + "&amount="+amount,
                success: function(data)
                {
                    if (data.active == 0)
                    {
                        $(".modal").modal('hide');
                        var note = '';
                        note+= '<table>';
                        note+= '<tr>';
                        note+= '<td colspan="2"><strong>La subasta ha sido cancelada por el vendedor</strong></td>';
                        note+= '</tr>';
                        note+= '</table>';
                        showBillError(note);
                    }else {
                        if (data.isnotavailability == 0)
                        {
                            $(".modal").modal('hide');
                            var note = '<table>';
                            note+= '<tr>';
                            note+= '<td colspan="2"><strong>Su compra se ha realizado con éxito</strong></td>';
                            note+= '</tr>';
                            note+= '<tr><td colspan="2" style="border-bottom:1px solid"></td>';
                            note+= '</tr>';
                            note+= '<tr>';
                            note+= '<td>Producto</td>';
                            note+= '<td>'+data.product+'</td>';
                            note+= '</tr>';
                            note+= '<tr>';
                            note+= '<td>Precio</td>';
                            note+= '<td>$ '+data.price+'</td>';
                            note+= '</tr>';
                            note+= '<tr>';
                            note+= '<td>Cantidad</td>';
                            note+= '<td>'+data.amount+ ' ' + data.unit  + '</td>';
                            note+= '</tr>';
                            note+= '<tr><td colspan="2" style="border-bottom:1px solid"></td>';
                            note+= '</tr>';
                            note+= '<tr>';
                            note+= '<td><strong>Total</strong></td>';
                            note+= '<td><strong>$ '+ currency( currency(data.price).multiply(data.amount) ).format() +'</strong></td>';
                            note+= '</tr>';
                            note+= '</tr>';
                            note+='<table>';
                            $(".bid-button-act").attr("disabled",true);
                            showBill(note);
                        }else{
                            var note = '';

                            note+= '<div class="alert alert-danger alert-dismissible" role="alert">';
                            note+= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                            note+= 'Sólo quedan disponibles ' + data.availability + ' ' + data.unit + ' de ' + data.product ;
                            note+= '</div>';

                            $(".content-danger-" +auctionId ).html(note);
                            $("#amount-bid-" +auctionId).val(data.availability);
                            $("#amount-bid-" +auctionId).attr('max',data.availability);
                            $(".s-disponible-" +auctionId).html(data.availability);

                            var price = $(".hid-currentPrice-"+auctionId).val();
                            var total = price * data.availability;
                            $(".modal-total-"+auctionId).html('Total $' + currency(total).format() );

                            if (data.availability < 0)
                            {
                                $("#amount-bid-" +auctionId).attr('disabled',true);
                                $(".mak-bid-"+auctionId).hide();
                            }

                        }
                    }
                }
            });
        }else{

            var note = '';
            note+= '<div class="alert alert-danger alert-dismissible" role="alert">';
            note+= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            note+= 'Sólo quedan disponibles ' + cDispo + ' ' + $(".modal-unit-"+auctionId).html() ;
            note+= '</div>';
            $(".content-danger-" +auctionId ).html(note);
            $("#amount-bid-" +auctionId).val(cDispo);
            $("#amount-bid-" +auctionId).attr('max',cDispo);


            var price1 = $(".hid-currentPrice-"+auctionId).val();
            var total1 = currency(price1).multiply(cDispo);
            $(".modal-total-"+auctionId).html('Total $' + currency(total1).format() );


            if (cDispo == 0)
            {
                $("#amount-bid-" +auctionId).attr('disabled',true);
                $(".mak-bid-"+auctionId).hide();
            }


        }
    }

    $(document).ready(function(){
        $('.timerauction').each(function(){
           timer($(this).data('id'))
        });
        $('.auction').each(function(){
            getPrice($(this).data('idauction'));
        })
    });
</script>
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