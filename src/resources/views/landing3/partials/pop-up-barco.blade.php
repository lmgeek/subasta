
<?php

$ports= \App\Ports::select()->orderBy('name', 'asc')->get();
use Illuminate\Routing\Controllers;
?>


<!-- Barco Popup
================================================== -->
<div id="small-dialog-barco" class="zoom-anim-dialog small-dialog mfp-hide dialog-with-tabs">

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
                    <h2 class="fw700 text-left t32 lsp-1">Nuevo Barco</h2>
                </div>

                <form id="newBoat" action="{{route('sellerboat.store')}}" method="POST">
                {{ csrf_field() }}
                    <!-- Bidding -->
                    <div class="bidding-widget">
                        <!-- Headline -->
                        <span class="bidding-detail"><strong>Nombre del barco</strong> </span>

                        <!-- Fields -->
                        <div class="bidding-fields w100">
                            <input name="name" class="with-border" placeholder="Nombre Barco" value="{{ old('name') }}">
                        </div>

                        <!-- Headline -->
                        <span class="bidding-detail margin-top-30"><strong>&iquest;Cu&aacute;l es la matr&iacute;cula del barco?</strong></span>

                        <!-- Fields -->
                        <div class="bidding-fields w100">
                            <input class="with-border" name="matricula" placeholder="Ingresa la matrícula" value="{{ old('matricula') }}" >
                        </div>

                        <!-- Headline -->
                        <span class="bidding-detail margin-top-30"><strong>&iquest;Cu&aacute;l es tu puerto de preferencia?</strong></span>

                        <!-- Fields -->
                        <div class="bidding-fields w100">
                            <select name="port" class="selectpicker with-border form-control over">
                                <option value="">Seleccione...</option>
                                @foreach($ports as $port)
                                    <option value='{{$port->id}}'>{{$port->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="bidding-widget margin-top-0">
                        <div class="bidding-fields bd-tp-1">
                            <div class="bidding-field margin-bottom-0">
                                <!-- Quantity Buttons -->
                                <button class="button form-control big" type="submit">Guardar</button>
                            </div>
                            <div class="bidding-field">
                                <a type="button" class="button dark ripple-effect big" href="{{url('/boatslist')}}">Cancelar</a>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<!-- Barco Popup / End -->
