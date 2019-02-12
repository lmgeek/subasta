<?php $outsidehome=1;
use App\Product;
use App\Ports;
use App\User;
use App\Constants;
use Illuminate\Pagination\LengthAwarePaginator;
$query=(isset($request->q))?$request->q:null;

?>
@extends('landing3/partials/layout')
@section('title',' | Lista de subastas')
@section('content')
	<div class="section gray padding-top-65 padding-bottom-75 margin-top-30">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">

					<!-- Section Headline -->
					<div class="section-headline margin-top-0 margin-bottom-35">
						<h2>Todas las subastas</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
					</div>
				<?php $contadorsubastasdestacadas=0;$nopic=1;?>

				<!-- Auctions Container -->
					<div id="FilterToggler" class="topnav" onclick="$('#MasterFilter').slideToggle()">
						<div id="title">Filtros</div>
						<em class="icon-feather-sliders" id="icon"></em>
					</div>
					<div id="MasterFilter"class=" margin-top-35">
						@include('/landing3/partials/masterFilter')
					</div><div class="tasks-list-container margin-top-35"  id="Auctions">
                        @include('/landing3/partials/ListaSubastas')
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection