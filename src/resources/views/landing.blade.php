<!-- MENU HEADER SECTION -->

@include('header')

<!-- FIN MENU HEADER SECTION -->


<div class="bg-header">
    <img src="{{ asset('/landing/img/header_subastas.jpg') }}" alt="Subastas"/>
</div>

<div class="categories" >
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-12 categories-left">
                <div class="vbar"></div>
                <img alt="image" width="30%" height="30%" src="{{ asset('/landing/img/icono_ofertas_pescdo.png') }}" />
                <div class="vbar2"></div>
            </div>
            <div class="col-md-4 col-xs-12 categories-center">
                <div class="vbar"></div>
                <img alt="image" width="30%" height="30%" src="{{ asset('/landing/img/icono_subastas_barco.png') }}" />
                <div class="vbar2"></div>
            </div>
            <div class="col-md-4 col-xs-12 categories-right">
                <div class="vbar"></div>
                <img alt="image" width="30%" height="30%" src="{{ asset('/landing/img/icono_exportacion_mundo.png') }}" />
                <div class="vbar2"></div>
            </div>
        </div>
    </div>
</div>



<!-- TERMINANDO SECTION-->

    @include('terminate')

<!-- FIN TERMINANDO SECTION-->



<!-- OFERTAS -->

    @include('offers')
<!-- FIN OFERTAS -->

</section>


<!-- FOOTER SECTION -->
@include('footer_home')
<!-- FIN FOOTER SECTION -->



