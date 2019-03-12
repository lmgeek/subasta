<?php

use App\Boat;

$objt = new Boat();
$CantidadBarco = count($objt->getInfoBoat(Auth::user()->id));

?>

@extends('landing3/partials/layout-admin')
@section('title', '| Lista de Barcos')
@section('content')

    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >


            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Mis Barcos</h3>
            </div>

                @if (count($errors) > 0)
                <div class="notification error closeable">
                    <div class="alert alert-danger">
                        <strong>Error</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

            <!-- Row -->
            <div class="row">

                <!-- Dashboard Box -->
                <div class="col-xl-12">

                    <div class="content text-right">
                        <a href="#small-dialog-barco" class="popup-with-zoom-anim button ripple-effect big margin-bottom-10"><i class="icon-feather-plus"></i> Nuevo Barco</a>
                    </div>

                    <div class="dashboard-box margin-top-0">

                        <!-- Headline -->
                        <div class="headline">
                            <h3 class="fw500"><i class="icon-line-awesome-ship"></i> {{$CantidadBarco}} Barcos</h3>
                            <div class="sort-by">
                                <select class="selectpicker hide-tick">
                                    <option>Todos</option>
                                    <option>Pendientes</option>
                                    <option>Aprobados</option>
                                    <option>Rechazados</option>
                                </select>
                            </div>
                        </div>

                        <div class="content">

                            <ul class="dashboard-box-list">

                                @foreach($boats as $boat)
                                <li>
                                    <!-- Job Listing -->
                                    <div class="job-listing">
                                        <!-- Job Listing Details -->
                                        <div class="job-listing-details">
                                            <!-- Details -->
                                            <div class="job-listing-description">
                                                <h3 class="job-listing-title">
                                                    <a href="#"> {{$boat['name']}}</a>
                                                    <span class="dashboard-status-button green">{{$boat['status']}}</span></h3>

                                                <!-- Job Listing Footer -->
                                                <div class="job-listing-footer">
                                                    <ul>
                                                        <li><i class="icon-line-awesome-ship"></i>{{$boat['matricula']}}</li>
                                                        <li><i class="icon-material-outline-location-on"></i>{{$objt->preferencePort($boat['preference_port'])}}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="buttons-to-right always-visible bd-tp-1">
                                        <a href="#" class="button gray ripple-effect ico" title="Editar" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                                        <a href="#" class="button gray ripple-effect ico" title="Eliminar" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <!--G.B paginacion-->
            <div class="pagination-container margin-top-30 margin-bottom-60">
                <ul>
                    <?php echo $boats->render(); ?>
                    {{--<li class="pagination-arrow"><a href="#" class="ripple-effect fw300">Mostrar más...</a></li>--}}
                </ul>
            </div>

@endsection