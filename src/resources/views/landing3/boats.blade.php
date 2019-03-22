<?php

use App\Boat;

$objt = new Boat();
$CantidadBarco = count($objt->getInfoBoat(Auth::user()->id));
use Illuminate\Support\Facades\Auth;



?>

@extends('landing3/partials/layout-admin')
@section('title', '| Lista de Barcos')
@section('content')

    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >


            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                @if (Auth::check() && Auth::user()->type == \App\User::INTERNAL)
                    <h3>Lista de Barcos</h3>
                @else
                    <h3>Mis Barcos </h3>
                @endif
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
                    {{--G.B si es distinto a admin no lo muestra--}}
                    @if (Auth::check() && Auth::user()->type != \App\User::INTERNAL)
                        <div class="content text-right">
                            <a href="#small-dialog-barco" class="popup-with-zoom-anim button ripple-effect big margin-bottom-10"><i class="icon-feather-plus"></i> Nuevo Barco</a>
                        </div>
                    @endif
                    <div class="dashboard-box margin-top-0">

                        <!-- Headline-->
                        <div class="headline">
                            @if(Auth::check() && Auth::user()->type == \App\User::INTERNAL)
                                <h3 class="fw500"><i class="icon-line-awesome-ship"></i> {{$countBoat}} Barcos</h3>
                            @else
                                <h3 class="fw500"><i class="icon-line-awesome-ship"></i> {{$countBoat}} Barcos</h3>
                            @endif

                            <div class="sort-by">
                                <select name="filterBoat" id="filterBoat" class="selectpicker hide-tick">
                                    <option value="">Seleccionar..</option>
                                    <option value="all">Todos</option>
                                    <option value="pending">Pendientes</option>
                                    <option value="approved">Aprobados</option>
                                    <option value="rejected">Rechazados</option>
                                </select>
                            </div>
                        </div>

                        <div class="content">

                            <ul class="dashboard-box-list" id="boat-list">

                                @if (Auth::check() && Auth::user()->type == \App\User::INTERNAL)

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
                                                            {{--<span class="dashboard-status-button green">{{$boat['status']}}</span></h3>--}}
                                                            <span class="dashboard-status-button @if($boat->status == 'pending') yellow @elseif($boat->status == 'approved') green @elseif($boat->status == 'rejected') red @endif">
                                                            {{ trans('sellerBoats.status.'.$boat->status) }}
                                                        </span>

                                                            <!-- Job Listing Footer -->
                                                            <div class="job-listing-footer">
                                                                <ul>
                                                                    <li><i class="icon-feather-user"></i> <?=$objt->getNameUserById($boat->user_id);?></li>
                                                                    <li><i class="icon-line-awesome-ship"></i>{{$boat['matricula']}}</li>
                                                                    <li><i class="icon-material-outline-location-on"></i>{{$objt->preferencePort($boat['preference_port'])}}</li>
                                                                </ul>
                                                            </div>

                                                    </div>

                                                    {{--G.B botones de aceptar y rechazar--}}
                                                    @if ($boat->status == \App\Constants::PENDIENTE)
                                                        <div class="buttons-to-right  always-visible single-right-button">
                                                            <a href="{{ route('boats.approve', $boat->id) }}" class=" button verde ripple-effect ico" data-tippy-placement="top" title="{{ trans('boats.accept_boat') }}"><i class="icon-feather-check"></i></a>
                                                            <a href="{{ route('boats.reject', $boat->id) }}" class="button rojo ripple-effect ico" data-tippy-placement="top" title="{{ trans('boats.reject_boat') }}"><i class="icon-feather-x"></i></a>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="buttons-to-right always-visible bd-tp-1">
                                                <a href="#" class="button gray ripple-effect ico" title="Editar" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                                                <a href="#" class="button gray ripple-effect ico" title="Eliminar" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>


                                            </div>
                                        </li>

                                    @endforeach

                                @else

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
                                                            {{--<span class="dashboard-status-button green">{{$boat['status']}}</span></h3>--}}

                                                        <span class="dashboard-status-button @if($boat->status == 'pending') yellow @elseif($boat->status == 'approved') green @elseif($boat->status == 'rejected') red @endif">
                                                             {{ trans('sellerBoats.status.'.$boat->status) }}
                                                        </span>

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

                                @endif

                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        @include('landing3/partials/pop-up-barco')

            <!--G.B paginacion-->
            <div class="pagination-container margin-top-30 margin-bottom-60">
                <ul>
                    <?php
                        //G.B  para preservar los parámetros de búsqueda al cambiar de página
                        echo $boats->appends(request()->input())->render();
                    ?>
                    {{--<li class="pagination-arrow"><a href="#" class="ripple-effect fw300">Mostrar más...</a></li>--}}
                </ul>
            </div>

@endsection