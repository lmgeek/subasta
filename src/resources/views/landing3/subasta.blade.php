<?php


use App\Auction;
use App\UserRating;

//Creamos un objeto de ña clase para usar sus funciones
$objtAuction = new Auction();

//Creamos una instacia de user para usar el metodo rating
$userRating = new UserRating();

//Id del usuario
$userId = $auction->batch->arrive->boat->user->id;

?>

<!doctype html>
<html lang="en">
<head>

    <title>Subasta | Subastas del Mar</title>
    @include('landing3/partials/common')

</head>
<body>

<!-- Wrapper -->
<div id="wrapper">

    <!-- Header Container
    ================================================== -->
    @include('landing3/partials/header')
    <!-- Header Container / End -->



    <!-- Titlebar
    ================================================== -->
    <div class="single-page-header bd-bt-1" data-background-image="landing3/images/single-auction.jpg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="single-page-header-inner">
                        <div class="left-side">
                            <div class="header-image"><img src="{{ asset('/img/products/'.$auction->batch->product->image_name) }}" alt="{{$auction->batch->product->name}}"></div>
                            <div class="header-details">
                                <h3 class="margin-bottom-0">{{$auction->batch->product->name}} <?php $objtAuction->caliber($auction->batch->caliber);?> <div class="star-rating" data-rating="5.0"></div>
                                    @if($auction->type!='public')
                                    <i class="t16 icon-feather-eye-off" data-tippy-placement="right" title="Subasta Privada" data-tippy-theme="dark"></i>
                                    @endif
                                </h3>


                                <ul>
                                    <li><i class="icon-material-outline-access-time primary"></i><strong class="primary">{{$objtAuction->formatDate($auction->end)}}</strong></li>
                                    <li><i class="icon-material-outline-location-on"></i> {{\App\Http\Controllers\AuctionController::getPortById($auction->batch->arrive->port_id) }}</li>
                                </ul>
                                <ul class="task-icons margin-top-6">
                                    <li>
                                        <small>Vendedor</small><br>
                                        <strong><i class="icon-feather-user"></i> {{$auction->batch->arrive->boat->user->nickname}}</strong><br>
                                        {{--<div class="medal-rating {{strtolower($usercat[$userId])}}" data-rating="{{$usercat[$userId]}}"><span class="medal {{$usercat[$userId]}}-text"></span></div>--}}
                                        <div class="medal-rating {{strtolower(\App\Auction::catUserByAuctions($userId))}}" data-rating="{{\App\Auction::catUserByAuctions($userId)}}">
                                            <span class="medal {{\App\Auction::catUserByAuctions($userId)}}"></span>
                                        </div>
                                    </li>
                                    <li><small>Barco</small><br>
                                        <strong><i class="icon-line-awesome-ship"></i>{{$auction->batch->arrive->boat->nickname}}</strong><br>
                                      <div class="star-rating" data-rating="<?php $userRating->calculateTheRatingUser($userId)?>"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="right-side">
                            <div class="salary-box">
                                <div class="salary-type"><span>&Uacute;ltimo precio:</span></div>
                                <div class="salary-amount t32"><strong>{{$price}}</strong> x kg<br>
                                    <small class="green fw400">
                                        <i class="icon-material-outline-local-offer green"></i>
                                        2 Ofertas Directas
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Page Content
    ================================================== -->
    <div class="container">
        <div class="row">

            <!-- Content -->
            <div class="col-xl-8 col-lg-8 content-right-offset">

                <!-- Description -->
                <div class="single-page-section">
                    <h3 class="margin-bottom-25">Descripci&oacute;n</h3>
                    <p>{{$auction->description}}</p>

                </div>

                <!-- Skills -->
                {{--<div class="single-page-section">--}}
                    {{--<h3>Categor&iacute;as</h3>--}}
                    {{--<div class="task-tags">--}}
                        {{--<span>Camar&oacute;n</span>--}}
                        {{--<span>Mar del Plata</span>--}}
                        {{--<span>Premium</span>--}}
                        {{--<span>Mariscos</span>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="clearfix"></div>

            </div>


            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4">
                <br class="sidebar-container">
                    <div id="timer<?=$auction->id?>" class="countdown margin-bottom-0 margin-top-20 blink_me timerauction" data-timefin="{{$auction->end}}" data-id="{{$auction->id}}"></div>
                    <br>
                    {{--<div class="countdown primary margin-bottom-25 t24" id="timer_1"></div>--}}

                    <div class="sidebar-widget">
                        <div class="bidding-widget">
                            <div class="bidding-headline bg-primary"><h3 class="white">&iexcl;Realiza tu compra ahora!</h3></div>
                            <div class="bidding-inner">

                                <!-- Headline -->
                                <span class="bidding-detail t18 bd-bt-1 padding-bottom-10">Disponibles <strong>10</strong> de <strong>400</strong> kg</span>

                                <!-- Headline -->
                                <span class="bidding-detail margin-top-10 fw300">Por favor, haz tu pedido:</span>

                                <!-- Fields -->
                                <div class="bidding-fields">
                                    <div class="bidding-field">
                                        <!-- Quantity Buttons -->
                                        <div class="qtyButtons">
                                            <div class="qtyDec" data-id="{{$auction->id}}"></div>
                                            <input type="text" name="qtyInput" value="1" id="cantidad-{{$auction->id}}" max="5">
                                            <div class="qtyInc" data-id="{{$auction->id}}"></div>
                                        </div>
                                    </div>
                                    <div class="bidding-field">
                                        <input type="text" class="with-border" value="Kg" disabled>
                                    </div>
                                </div>
                                <div class="bidding-fields">
                                    <div class="checkbox">
                                        <input type="checkbox" id="chekcbox{{$auction->id}}" onclick="popupCompraDisableText({{$auction->id}})">
                                        {{--<input type="checkbox" id="chekcbox1" onclick="enable_text(this.checked)">--}}
                                        <label for="chekcbox{{$auction->id}}"><span class="checkbox-icon"></span> Adquirir todo el lote</label>
                                    </div>
                                </div>

                                <!-- Button -->
                                <button id="snackbar-place-bid" class="button ripple-effect move-on-hover full-width margin-top-25" onclick="makeBid({{$auction->id}})")><span>Comprar</span></button>

                            </div>
                            <div class="bidding-signup t12">&iquest;Prefieres hacer una oferta? <a href="#small-dialog-oferta" class="sign-in popup-with-zoom-anim">Real&iacute;zala ahora</a></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


    <!-- Spacer -->
    <div class="margin-top-15"></div>
    <!-- Spacer / End-->

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
{{--@include('landing3/partials/popup-oferta.php')--}}
{{--@include('landing3/partials/popup-register-login.php')--}}
{{----}}

@include('landing3/partials/js')
<!-- Scripts
================================================== -->

<script>

       function popupCompraDisableText($id) {
           $var = $('#cantidad-'+$id).attr('max')

           $('#checkbox'+$id).click(
               $('#cantidad-'+$id).val($var)
           );

    }

       function makeBid($id){

        $.get("/makeBid?auction_id="+$id + "&amount="+$('#cantidad-'+$id).val(),function(result){

        }).fail(function(){

        });
    }




     function timer($id) {
         console.log($id)
        window['dateend']= new Date($("#timer"+$id).attr('data-timefin'));
        var countDownDate = new Date($("#"+$id).attr('data-timefin')).getTime();
        var now = new Date().getTime();
        var distance = window['dateend']- now,string='';
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

            $('#Auction_'+$id).addClass('bg-disabled');
            $('#ClosePrice'+$id).html('Precio Final');
            var $html='<div id="Auction_'+$id+'" class="task-listing auction bg-disabled" style="display:none"data-id="'+$id+'">'+$('#Auction_'+$id).html()+'</div>'+$('#FinishedAuctions').html();
            $('#Auction_'+$id).fadeOut();
            setTimeout(function(){$('#Auction_'+$id).remove();},400);
            $('#FinishedAuctions').html($html);
            $('#Auction_'+$id+' .pricing-plan-label .billed-monthly-label').removeClass('red');
            $('#Auction_'+$id+' .icon-material-outline-access-time').removeClass('primary');
            setTimeout(function(){$('#Auction_'+$id).fadeIn();},400);
            if($('#FinishedAuctions > .task-listing').length>3){
                $("#FinishedAuctions").children('.task-listing').last().remove();
            }
        }else{
            setTimeout(function(){timer($id);},1000);
        }
    }

    $(document).ready(function(){
        $('.timerauction').each(function(){
            timer($(this).data('id'))
        });
    });





    // Set the date we're counting down to
    var countDownDate = new Date("Jan 23, 2019 15:37:25").getTime();

    // Update the count down every 1 second
/*    var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("timer_1").innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer_1").innerHTML = "Subasta Finalizada";
            document.getElementById("timer_1").classList.add("fw300");
        }
    }, 1000);*/
</script>

<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
    // Snackbar for "hacer oferta" button
    $('#snackbar-place-bid').click(function() {
        Snackbar.show({
            text: '¡Felicidades! Has comprado este producto.',
        });
    });
</script>
<script>
    function enable_text(status)
    {
        //alert(status);
        document.getElementById("cantidad").disabled = status;
    }
</script>

</body>
</html>