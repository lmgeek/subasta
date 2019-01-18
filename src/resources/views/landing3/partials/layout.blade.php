<!doctype html>
<html lang="es">
<head>

    <title>Subastas del Mar @yield('title','')</title>
    @include('landing3/partials/common')

</head>
<body>

<!-- Wrapper -->
<div id="wrapper" class="wrapper-with-transparent-header">

<!-- Header Container
    ================================================== -->
@include('landing3/partials/header')
    @yield('content')
    <div id="notificationsauction"></div>
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