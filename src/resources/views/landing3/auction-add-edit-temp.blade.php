<?php
/*
 * In @section('content') goes all that's inside
 * .dashboard-content-container
 * (Lines 31->242 dash-nueva-subasta.php)
 * The content of the previously mentioned div in some point should transform to 
 * a form which action would be = /auctionStore
 * the rest of the content goes exactly as in the provided desing only changing
 * the selects of boats, ports and products
 * $code would be for the auction id field, it cant be shown because we need to 
 * avoid conflicts at saving in case of multiple sessions
 * In order to the form to work it needs the csrf_field
 * the names of the fields that are explitly declared should stay the same
 * Other name for inputs/selects
 *      Fecha Tentativa de entrega: date
 *      Calibre:    caliber
 *      Calidad:    quality
 *      Cantidad:   amount
 *      idbatch:    batch
 *      idarrive:   id
 *      datestart:  fechaInicio
 *      activehours:fechaFin
 *      startprice: startPrice
 *      endprice:   endPrice  
 *      description:descri
 *      privacy:    tipoSubasta
 *      guests:     invitados[]
 */
$code='SU-'.date('ym').'XXX';
?>
@extends('landing3/partials/layout-admin')
@section('title',$title)
@section('content')

{{csrf_field()}}










<div class="dashboard-content-container" data-simplebar>
		<div class="dashboard-content-inner" >
			
			<!-- Dashboard Headline -->
			<div class="dashboard-headline">
				<h3>Nueva Subasta</h3>
			</div>
	
			<!-- Row -->
			<div class="row">

				<!-- Dashboard Box -->
				<div class="col-xl-12">
					<div class="dashboard-box margin-top-0">
						
						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-feather-package"></i> Informaci&oacute;n del Lote</h3>
						</div>
						
						<div class="content with-padding padding-bottom-10">
							<div class="row">
								
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Barco</h5>
										<select name="barco" title="Selecciona..." onchange="cambiaValores()" >
@foreach($boats as $boat)
    <option value="{{$boat->id}}">{{$boat->name}}</option>
@endforeach    
</select>
										<small>&iquest;No encuentras tu barco? <a href="#small-dialog-barco" class="popup-with-zoom-anim">Crear nuevo barco.</a></small>
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Puerto</h5>
										<div class="input-with-icon">
											<select id="puerto" name="puerto" title="Selecciona..."> 
                                                <option>asd</option>
                                            @foreach($ports as $port)
                                                <option value ="{{$port->id}}">{{$port->name}}</option>
                                            @endforeach
                                            </select>
										</div>
									</div>
								</div>
								
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Fecha Tentativa de Entrega</h5>
											<input type="text" data-field="datetime" class="with-border" placeholder="Ingresa la fecha..." readonly>
											<div id="dtBox"></div>
									</div>
								</div>
																
								<div class="col-xl-3">
									<div class="submit-field">
										<h5>Producto <i class="help-icon" data-tippy-placement="right" title="Especie a subastar"></i></h5>
										<select  name="product" data-live-search="true" title="Selecciona...">
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                        </select>
									</div>
								</div>
								
								<div class="col-xl-3">
									<div class="submit-field">
										<h5>Calibre <i class="help-icon" data-tippy-placement="right" title="Tamaño del producto"></i></h5>
										<select  title="Selecciona...">
											<option>Chico/a</option>
											<option>Mediano/a</option>
											<option>Grande</option>
										</select>
									</div>
								</div>
								
								<div class="col-xl-3">
									<div class="submit-field">
										<h5>Unidad de venta</h5>
										<select  title="Selecciona...">
											<option>Caj&oacute;n</option>
											<option>Kilogramo</option>
											<option>Unidad</option>
										</select>
									</div>
								</div>
								<div class="col-xl-3">
									<div class="submit-field disabled">
										<h5>Calidad</h5>
										<div class="leave-rating margin-top-10">
											<input type="radio" name="rating" id="rating-1" value="1" />
											<label for="rating-1" class="icon-material-outline-star"></label>
											<input type="radio" name="rating" id="rating-2" value="2"/>
											<label for="rating-2" class="icon-material-outline-star"></label>
											<input type="radio" name="rating" id="rating-3" value="3"/>
											<label for="rating-3" class="icon-material-outline-star"></label>
											<input type="radio" name="rating" id="rating-4" value="4"/>
											<label for="rating-4" class="icon-material-outline-star"></label>
										<input type="radio" name="rating" id="rating-5" value="5"/>
										<label for="rating-5" class="icon-material-outline-star"></label>
										</div>
										</div>
								</div>
								
							</div>
						</div>
						
						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-material-outline-gavel"></i> Informaci&oacute;n de la Subasta</h3>
						</div>
						
						<div class="content with-padding padding-bottom-10">
							<div class="row">
								
								<div class="col-xl-4">								
									<div class="submit-field">
										<h5>ID Subasta <i class="help-icon" data-tippy-placement="right" title="Identificador Unico"></i></h5>
										<input type="text" class="with-border" value="SU-18000001" disabled>
									</div>
								</div>
								
								<div class="col-xl-4">								
									<div class="submit-field">
										<h5>Fecha de Inicio</h5>
										<input type="text" data-field="datetime" class="with-border" placeholder="Ingresa la fecha..." readonly>
										<div id="dtBox"></div>
									</div>
								</div>
								
								<div class="col-xl-4">								
									<div class="submit-field">
										<h5>Horas Activa <i class="help-icon" data-tippy-placement="right" title="Duración de la subasta"></i></h5>
										<div class="qtyButtons">
											<div class="qtyDec"></div>
											<input type="text" name="qtyInput" value="4" id="cantidad">
											<div class="qtyInc"></div>
										</div>
									</div>
								</div>
								
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Cantidad de Cajones <i class="help-icon" data-tippy-placement="right" title="Cantidad disponible para esta subasta"></i></h5>
										<input class="with-border" id="cant_disp" type="text" placeholder="Ingresa la cantidad" >
									</div>
								</div>
								
								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Precio Inicial <i class="help-icon" data-tippy-placement="right" title="El costo inicial de la unidad de venta"></i></h5>
										<div class="input-with-icon">
											<input class="with-border" type="text" placeholder="Ingresa el monto">
											<i class="currency">AR$</i>
										</div>
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Precio de Retiro <i class="help-icon" data-tippy-placement="right" title="Costo mínimo de la unidad de venta"></i></h5>
										<div class="input-with-icon">
											<input class="with-border" type="text" placeholder="Ingresa el monto">
											<i class="currency">AR$</i>
										</div>
									</div>
								</div>

								<div class="col-xl-12">
									<div class="submit-field">
										<h5>Descripci&oacute;n de la Subasta</h5>
										<textarea cols="30" rows="5" class="with-border"></textarea>
										<div class="uploadButton margin-top-30">
											<input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload" multiple/>
											<label class="uploadButton-button ripple-effect" for="upload">Adjuntar</label>
											<span class="uploadButton-file-name">Im&aacute;genes reales del producto pueden ser &uacute;tiles para destacar tu subasta.</span>
										</div>
									</div>
								</div>

							</div>
						</div>
						
						
					</div>
				</div>

				<div class="col-xl-12 text-right">
					<a href="#" class="button ripple-effect big margin-top-30">Subastar</a>
					<a href="#" class="button dark ripple-effect big margin-top-30">Cancelar</a>
				</div>

			</div>
		</div>
	</div>
@include('/landing3/partials/pop-up-barco')

@endsection


