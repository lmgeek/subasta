@extends('landing3/partials/layout-admin')
@section('title',' | Ofertas Recibidas')
@section('content')
    <?
    use App\UserRating;
    //Creamos una instacia de user para usar el metodo rating
    $userRating = new UserRating();
    ?>

    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Ofertas Recibidas</h3>
            </div>

            <!-- Row -->
            <div class="row">

                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    @if(count($auctions) > 0)
                        <div class="notification error closeable">
                            <p><i class="icon-feather-alert-circle"></i> Tienes <strong>{{ $revision }}
                                    Subastas</strong> pendientes de revisión.</p>
                            <a class="close" href="#"></a>
                        </div>
                    @else
                        <div class="notification error closeable">
                            <p><i class="icon-feather-alert-circle"></i> No tienes ninguna oferta pendiente.</p>
                        </div>
                    @endif
                    <div class="dashboard-box margin-top-0">
                        <div class="content">
                            <div class="accordion js-accordion offer">

                            @foreach($auctions as $auction)
                                <!-- Accordion Item -->
                                    <div class="accordion__item js-accordion-item">
                                        <div class="accordion-header bg-accordion-hd js-accordion-header d-flex">
                                            <div class="job-listing-details w50">

                                                <!-- Details -->
                                                <div class="job-listing-description">
                                                    <h3 class="fw500">
                                                        <i class="icon-feather-dollar-sign"></i> {{ $auction->offers }} Ofertas
                                                    </h3><br>
                                                    <h3 class="job-listing-title">Camar&oacute;n Grande
                                                        <div class="star-rating"
                                                             data-rating="{{ $auction->batch->quality }}"></div>
                                                    </h3>

                                                    <!-- Job Listing Footer -->
                                                    <div class="job-listing-footer">
                                                        <ul>
                                                            <li>
                                                                <i class="icon-material-outline-gavel"></i> {{ $auction->code }}
                                                            </li>
                                                            <li><i class="icon-material-outline-date-range"></i>
                                                                Finaliza el {{ \App\Constants::formatDateOffer($auction->end) }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="task-listing-info-dash">
                                                <ul class="dashboard-task-info">
                                                    <li><strong>{{ $available[$auction->id]['available'] }}
                                                            kg</strong><span>Disponibles</span></li>
                                                    <li>
                                                        <strong>${{ number_format(str_replace(",","",$auction->end_price),2,',','') }}</strong><span>Precio Límite</span>
                                                    </li>
                                                    @if($auction->offers > 0)
                                                        <li>
                                                        <strong class="red">${{ number_format(str_replace(",","",$max[$auction->id]['price']),2,',','') }}</strong><span
                                                                class="red">Mejor Oferta</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Accordtion Body -->
                                        <div class="accordion-body js-accordion-body">

                                            <!-- Accordion Content -->
                                            <div class="accordion-body__contents">
                                                <ul class="dashboard-box-list">
                                                    @foreach($offers[$auction->id] as $offer)
                                                        <li>
                                                            <!-- Job Listing -->
                                                            <div class="job-listing">

                                                                <!-- Job Listing Details -->
                                                                <div class="job-listing-details oferta">

                                                                    <!-- Details -->
                                                                    <div class="job-listing-description">
                                                                        <h3 class="job-listing-title t18">
                                                                            <i class="icon-feather-user"></i> {{ $offer->user->nickname }}
                                                                            <div class="star-rating"
                                                                                 data-rating="{{ $userRating->calculateTheRatingUser($offer->user->id) }}"></div>
                                                                            <span class="dashboard-status-button @if($offer->status == 'pending') yellow @elseif($offer->status == 'accepted') green @elseif($offer->status == 'rejected') red @endif">
                                                            {{ trans('auction.'.$offer->status) }}
                                                        </span>
                                                                        </h3>
                                                                        <div class="job-listing-footer">
                                                                            <ul>
                                                                                <li>
                                                                                    <i class="icon-material-outline-date-range"></i>
                                                                                    Realizada el {{ \App\Constants::formatDateOffer($offer->created_at) }}
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="task-listing-info-dash">
                                                                    <!-- Offer Details -->
                                                                    <ul class="dashboard-task-info precio">
                                                                        <li><strong>${{ number_format(str_replace(",","",$offer->price),2,',','') }}</strong><span>Precio Ofrecido</span>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                            </div>

                                                            <!-- Buttons -->
                                                            @if($offer->status == 'pending')
                                                                <div class="buttons-to-right center-buttons-mobile always-visible">
                                                                    <a href="#" onclick="javascript:window.location='oferta/reschazar/{{ $auction->id }}/{{ $offer->id }}'"
                                                                       class="button verde ripple-effect ico"
                                                                       title="Aceptar" data-tippy-placement="top"><i
                                                                                class="icon-feather-check"></i></a>
                                                                    <a href="#" onclick="javascript:window.location='oferta/reschazar/{{ $offer->id }}'"
                                                                       class="button rojo ripple-effect ico"
                                                                       title="Rechazar" data-tippy-placement="top"><i
                                                                                class="icon-feather-x"></i></a>
                                                                </div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                        </div>
                                        <!-- Accordion Body / End -->
                                    </div>
                                    <!-- Accordion Item / End -->
                                @endforeach


                            </div>
                        </div>
                    </div>
                    </div>

                        <div class="pagination-container margin-top-30 margin-bottom-60">
                            <ul>
                                    <!-- Pagination -->
                                    {!! $auctions->render() !!}
                            </ul>
                        </div>
                </div>




@endsection














