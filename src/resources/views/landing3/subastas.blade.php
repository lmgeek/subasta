<?php $outsidehome=1;
use App\Product;
use App\Ports;
use App\User;
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
						<div class="sidebar-widget">
							<h3>Puertos</h3>
							<div class="checkbox">
								@foreach($ports as $key=>$valor)
									<input type="checkbox" id="Port{{$key}}" onclick="auctionListFilter()" class="AuctionListFilter" data-field="port" data-value="{{$key}}"<?=($port_id==$key)?'checked':''?>>
									<label for="Port{{$key}}"><span class="checkbox-icon"></span> {{Ports::getPortById($key)}} ({{$valor}})</label>
								@endforeach
							</div>
						</div>

						<!-- Productos -->
						<div class="sidebar-widget">
							<h3>Productos</h3>
							<div class="checkbox">
								@foreach($products as $key=> $valor)
								<input type="checkbox" id="Product{{$key}}"  onclick="auctionListFilter()" class="AuctionListFilter" data-field="product" data-value="{{$key}}">
								<label for="Product{{$key}}"><span class="checkbox-icon"></span> {{Product::getProductFromId($key)}} ({{$valor}})</label>
								@endforeach
							</div>
						</div>

						<!-- Calibre -->
						<div class="sidebar-widget">
							<h3>Calibre</h3>
							<div class="checkbox">
								@foreach($caliber as $key=>$valor)
								<input type="checkbox" id="Caliber{{$key}}" onclick="auctionListFilter()" class="AuctionListFilter" data-field="caliber" data-value="{{$key}}">
								<label for="Caliber{{$key}}"><span class="checkbox-icon"></span> {{ucfirst(\App\Constants::caliber($key))}} ({{$valor}})</label>
								@endforeach
							</div>
						</div>

						<!-- Calidad -->
						<div class="sidebar-widget">
							<h3>Calidad</h3>
							<div class="checkbox">
								<input type="checkbox" id="chekcbox9"  onclick="auctionListFilter()" class="AuctionListFilter" data-field="quality" data-value="1" >
								<label for="chekcbox9"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="1"></div></label>
								<input type="checkbox" id="chekcbox10" onclick="auctionListFilter()" class="AuctionListFilter" data-field="quality" data-value="2">
								<label for="chekcbox10"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="2"></div></label>
								<input type="checkbox" id="chekcbox11" onclick="auctionListFilter()" class="AuctionListFilter" data-field="quality" data-value="3">
								<label for="chekcbox11"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="3"></div></label>
								<input type="checkbox" id="chekcbox12" onclick="auctionListFilter()" class="AuctionListFilter" data-field="quality" data-value="4">
								<label for="chekcbox12"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="4"></div></label>
								<input type="checkbox" id="chekcbox13" onclick="auctionListFilter()" class="AuctionListFilter" data-field="quality" data-value="5">
								<label for="chekcbox13"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="5"></div></label>
							</div>
						</div>

						<!-- Budget -->
						<div class="sidebar-widget">
							<h3>Precio</h3>
							<div class="margin-top-55"></div>

							<!-- Range Slider -->
							<input class="range-slider"  type="text" value="" data-slider-currency="$" data-slider-min="10" data-slider-max="2500" data-slider-step="25" data-slider-value="[50,2500]" id="PriceFilter"/>
							<div class="checkbox margin-top-15">
								<input type="checkbox" id="CloseLimitPrice" onclick="auctionListFilter()" class="AuctionListFilter" data-field="close" data-value="1">
								<label for="CloseLimitPrice" class="red"><span class="checkbox-icon"></span><em class="icon-line-awesome-exclamation-circle red"></em> Cerca de precio l&iacute;mite</label>
							</div>
						</div>

						<!-- Barco -->
						<div class="sidebar-widget">
							<h3>Barco</h3>
							<div class="checkbox">
								<input type="checkbox" id="UserRating1" onclick="auctionListFilter()" class="AuctionListFilter" data-field="userrating" data-value="1">
								<label for="UserRating1"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="1"></div></label>
								<input type="checkbox" id="UserRating2" onclick="auctionListFilter()" class="AuctionListFilter" data-field="userrating" data-value="2">
								<label for="UserRating2"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="2"></div></label>
								<input type="checkbox" id="UserRating3" onclick="auctionListFilter()" class="AuctionListFilter" data-field="userrating" data-value="3">
								<label for="UserRating3"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="3"></div></label>
								<input type="checkbox" id="UserRating4" onclick="auctionListFilter()" class="AuctionListFilter" data-field="userrating" data-value="4">
								<label for="UserRating4"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="4"></div></label>
								<input type="checkbox" id="UserRating5" onclick="auctionListFilter()" class="AuctionListFilter" data-field="userrating" data-value="5">
								<label for="UserRating5"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="5"></div></label>
							</div>
						</div>

						<!-- Usuarios -->
						<div class="sidebar-widget">
							<h3>Vendedores</h3>
							<div class="checkbox">
								@foreach($users as $key=>$val)
								<input type="checkbox" id="Usuario{{$key}}" onclick="auctionListFilter()" class="AuctionListFilter" data-field="user" data-value="{{$val['nickname']}}">
								<label for="Usuario{{$key}}"><span class="checkbox-icon"></span> {{User::getUserById($key)}} ({{$val}})</label>
								@endforeach
							</div>
						</div>
					</div><div class="tasks-list-container margin-top-35"  id="Auctions">
                        
					@if(count($auctions)>0)

						@foreach($auctions as $auction)
							<?php $contadorsubastasdestacadas++;?>

							<!-- Auction Listing -->

							@include('/landing3/partials/auctionNoDetail')
						@endforeach
					@endif
					<!-- Auction Listing -->

						<!-- Auction Listing -->

					</div>

					<!-- Auctions Container / End -->

				</div>
			</div>


		</div>
	</div>
@endsection