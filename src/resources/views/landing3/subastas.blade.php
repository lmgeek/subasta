<?php $outsidehome=1;
use App\Product;
use App\Ports;
use App\User;
use App\Constants;
use Illuminate\Pagination\LengthAwarePaginator;
$query=(isset($request->q))?$request->q:null;
$listasubastas=1;

?>
@extends('landing3/partials/layout'.((isset(Auth::user()->type))?'-admin':''))
@section('title',' | Lista de subastas')
@section('content')
@if(empty(Auth::user()->type))
	<div class="section gray padding-top-65 padding-bottom-75 margin-top-30">
        <div class="container">
@endif
		
            
			<div class="row">
				<div class="col-xl-12">

					<!-- Section Headline -->
					<div class="section-headline margin-top-0 margin-bottom-35">
                        @if(isset(Auth::user()->id) && Auth::user()->type==Constants::VENDEDOR && isset($request->type) && $request->type=='mine')
						<h2>Mis Subastas</h2>
						<p><?=$auctioncounter.(($auctioncounter!=1)?' subastas':' subasta')?></p>
                        @else
                        <h2>Todas las subastas</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        @endif
					</div>
				<?php $contadorsubastasdestacadas=0;$nopic=1;?>

				<!-- Auctions Container -->
					<div id="FilterToggler" class="topnav" onclick="$('#MasterFilter').slideToggle()">
						<div id="title">Filtros</div>
						<em class="icon-feather-sliders" id="icon"></em>
					</div>
					<div id="MasterFilter"class=" margin-top-35">
						@include('/landing3/partials/masterFilter')
                    </div><div class="tasks-list-container margin-top-35" id="AuctionsContainer">
                        <div class="headline">
                            <div class="sort-by">
                                <select class="selectpicker" onchange="filterByStatus()" id="selectStatus" name="timeline">
                                    <option value="all" <?=($timeline=='all' || old('timeline'))?Constants::SELECTED:''?>>Todas</option>
                                    <option value="future" <?=($timeline=='future' || old('timeline'))?Constants::SELECTED:''?>>Pendientes</option>
                                    <option value="incourse" <?=($timeline=='incourse' || old('timeline'))?Constants::SELECTED:''?>>En Curso</option>

                                    <option value="finished"<?=($timeline=='finished' || old('timeline'))?Constants::SELECTED:''?>>Finalizadas</option>
                                </select>
                            </div>
                        </div><div id="Auctions">
                            @include('/landing3/partials/listasubasta')
                        </div>
					</div>
				</div>
			</div>
@if(empty(Auth::user()->type))
		</div>
	</div>
@endif
@endsection