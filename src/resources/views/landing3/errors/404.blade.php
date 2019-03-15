<?php $outsidehome=1;?>
@extends('landing3/partials/layout')
@section('title', '| 404')
@section('content')
<div class="margin-top-30">
    <div class="row">
		<div class="col-xl-12">

			<section id="not-found" class="center margin-top-50 margin-bottom-25">
				<h2>404</h2>
				<p>Lo sentimos, pero la p&aacute;gina que buscabas no existe.</p>
			</section>

			<div class="row">
				<div class="col-xl-8 offset-xl-2">
						<div class="intro-banner-search-form not-found-search margin-bottom-50">
                            <form method="get" action="/subastas">
                                <input type="hidden" name="time" value="all">
							<!-- Search Field -->
							<div class="intro-search-field ">
                                <input id="intro-keywords" type="text" placeholder="¿Qué estás buscando?" name="q">
							</div>

							<!-- Button -->
							<div class="intro-search-button">
                                <button class="button ripple-effect" type="submit">Buscar</button>
							</div>
                            </form>
						</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection