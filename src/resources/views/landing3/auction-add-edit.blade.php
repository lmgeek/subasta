<?php
use App\Constants;
use App\Product;
$code='SU-'.date('ym').'XXX';
$description='';
$portid='';$boatid='';$productid='';$caliber='';$quality='';$activehours=1;
$privacy=Constants::AUCTION_PUBLIC;
$startdate='';
$tentativedate='';
$calibers= Product::caliber();
$saleunits=Product::sale();
$presunits=Product::units();
$startprice='';$endprice='';$quantity='';
$presunit='unidades';$saleunit='unidad';$type='add';$title='Agregar';$auctioncode='SU-'.date('ym').'XXX';
if(isset($auction)){
    $type=(isset($replicate))?'replication':'edit';
    $title=(isset($replicate))?'Replicar':'Editar';
    $boatid=$auction->batch->arrive->boat->id;
    $portid=$auction->batch->arrive->port_id;
    $productid=$auction->product['idproduct'];
    $caliber=$auction->product['caliber'];
    $tentativedate=date('d-m-Y H:i', strtotime($auction->tentative_date));
    $description=$auction->description;
    $quality=$auction->batch->quality;
    $quantity=$auction->amount;
    $startdate=$auction->start;
    $activehours=(int)((strtotime($auction->end)-strtotime($startdate))/3600);
    $startdate=date('d-m-Y H:i', strtotime($auction->start));
    $startprice=$auction->start_price;
    $endprice=$auction->end_price;
    $privacy=$auction->type;
    $presunit=$auction->product['presentation_unit'];
    $saleunit=$auction->product['sale_unit'];
    $auctioncode=(isset($replicate))?'SU-XXXXXXX': $auction->code;
    if($privacy=='private'){
        $guests= App\AuctionInvited::select('user_id')->where('auction_id',Constants::EQUAL,$auction->id)->get();
    }
}?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.$title.' Subasta')
@section('content')
<?php
if(Auth::user()->type==Constants::VENDEDOR && ((isset($auction->timeline) && $auction->timeline==Constants::FUTURE)) || empty($auction->timeline) || $replicate==1){
?>

	
        <form method="post" action="/subastas/guardar">
		<div class="dashboard-content-inner" >
			<div class="dashboard-headline"><h3>Nueva Subasta</h3></div>
			<div class="row">
				<div class="col-xl-12">
					<div class="dashboard-box margin-top-0">
						@if (count($errors) > 0)
                        <div class="alert alert-danger"><strong>Error<?=(count($errors)>1)?'es':''?></strong><br><br><ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                        </ul></div>
                        @endif
						<div class="headline"><h3><i class="icon-feather-package"></i> Informaci&oacute;n del Lote</h3></div>
						<div class="content with-padding padding-bottom-10">
                            {{csrf_field()}}
                            <input type="hidden" name="type" value="<?=$type?>">
                            @if(isset($auction))
                            <input type="hidden" name="auctionid" value="{{$auction->id}}"><input type="hidden" name="batchid" value="{{$auction->batch_id}}"><input type="hidden" name="arriveid" value="{{$auction->batch->arrive_id}}">
                                @if($arriveedit==0)
                            <input type="hidden" name="barco" value="<?=$boatid?>"><input type="hidden" name="puerto" value="<?=$portid?>">
                                @endif
                                @if($batchedit==0)
                            <input type="hidden" name="product" value="<?=$productid?>"><input type="hidden" name="caliber" value="<?=$caliber?>"><input type="hidden" name="unidad" value="<?=$presunit?>"><input type="hidden" name="quality" value="<?=$quality?>">
                                @endif
                            @endif
                            
							<div class="row">	
								<div class="col"><div class="submit-field"><h5>Barco</h5>
										<select name="barco" id="Boat" onchange="getPreferredPort()" <?=($arriveedit==0)?Constants::DISABLED:''?>  class="selectpicker with-border" data-live-search="true" title="Seleccione...">
                                            <option disabled value='0'>Seleccione...</option>
                                        @foreach($boats as $boat)
                                            <option value="{{$boat->id}}" <?=($boatid==$boat->id || old('barco')==$boat->id)?Constants::SELECTED:''?>>{{$boat->name}}</option>
                                        @endforeach    
                                        </select>
                                </div></div>
								<div class="col">
									<div class="submit-field"><h5>Puerto</h5><div class="input-with-icon">
											<select id="puerto" name="puerto" <?=($arriveedit==0)?Constants::DISABLED:''?> class="selectpicker with-border" title="Seleccione...">
                                                <option disabled value='0'>Seleccione...</option>
                                           @foreach($ports as $port)
                                               <option value ="{{$port->id}}" <?=($portid==$port->id || old('puerto')==$port->id)?Constants::SELECTED:''?>>{{$port->name}}</option>
                                           @endforeach
                                            </select>
										</div>
									</div>
								</div>
                            </div><div class="row">	
								
								<div class="col">
									<div class="submit-field">
										<h5>Producto <i class="help-icon" data-tippy-placement="right" title="Especie a subastar"></i></h5>
                                        <select name="product" <?=($batchedit==0)?Constants::DISABLED:''?>  class="selectpicker with-border" data-live-search="true" title="Seleccione..." onchange="auctions_loadCalibers()" id="ProductSelect">
                                            <option disabled value='0'>Seleccione...</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" <?=($productid==$product->id)?Constants::SELECTED:''?>>{{$product->name}}</option>
                                        @endforeach
                                        </select>
									</div></div>
								
								<div class="col">
									<div class="submit-field">
										<h5>Calibre <i class="help-icon" data-tippy-placement="right" title="Tamaño del producto"></i></h5>
                                        <select name="caliber" <?=($batchedit==0)?Constants::DISABLED:''?>  class="selectpicker with-border"  title="Seleccione un producto..." onchange="auctions_loadUnits()" id="CalibersSelect">
                                            <option disabled value='0'>Seleccione...</option>
                                            <?php for($z=0;$z<count($calibers);$z++){?>
                                            <option value='<?=$calibers[$z]?>'<?=(isset($caliber) && $caliber==$calibers[$z])?'selected':''?>><?=trans('general.product_caliber.'.$calibers[$z])?></option>    
                                            <?php }?>
                                        </select>
									</div>
								</div>
								
								
                                <div class="col">
									<div class="submit-field">
                                        <h5>Unidad de Presentaci&oacute;n</h5>
                                        <input disabled type="text" id="PresentationUnit" value='<?=$presunit?>' placeholder="Escoja un producto y un calibre">
									</div>
								</div>
                                <div class="col">
									<div class="submit-field">
										<h5>Unidad de precio de venta</h5>
                                        <input disabled type="text" id="SaleUnit" value='<?=$saleunit?>' placeholder="Escoja un producto y un calibre">
									</div>
								</div>
								<div class="col">
									<div class="submit-field disabled">
										<h5>Calidad</h5>
										<div class="leave-rating margin-top-10">
                                            @for($z=5;$z>=1;$z--)
											<input type="radio" name="quality" id="rating-<?=$z?>" value="<?=$z?>" <?=($quality==$z || old('quality')==$z)?'checked':''?>  <?=($batchedit==0)?Constants::DISABLED:''?>/>
											<label for="rating-<?=$z?>" class="icon-material-outline-star"></label>
                                            @endfor
										</div>
										</div>
								</div>
								
							</div>
						</div>
                        <input type="hidden" name="product_detail" id='ProductDetailID' value='<?=(isset($auction->batch->product_detail_id))?$auction->batch->product_detail_id:''?>'>
						<!-- Headline -->
						<div class="headline">
							<h3><i class="icon-material-outline-gavel"></i> Informaci&oacute;n de la Subasta</h3>
						</div>
						
						<div class="content with-padding padding-bottom-10">
							<div class="row">
								
								<div class="col">								
									<div class="submit-field">
										<h5>ID Subasta <i class="help-icon" data-tippy-placement="right" title="Identificador Unico"></i></h5>
										<input type="text" class="with-border" value="<?=$auctioncode?>" disabled>
									</div>
								</div>
                            </div><div class="row">
								<div class="col-xl-4">								
									<div class="submit-field">
										<h5>Fecha de Inicio</h5>
                                        <input type="text" data-field="datetime" class="with-border" placeholder="Ingresa la fecha..." readonly name="fechaInicio" value="<?=$startdate?>"<?=($auctionedit==0)?Constants::DISABLED:''?>>
										<div class="dtBox"id='fechaInicioDTP'></div>
									</div>
								</div>
								
								<div class="col-xl-4">								
									<div class="submit-field">
										<h5>Horas Activa <i class="help-icon" data-tippy-placement="right" title="Duración de la subasta"></i></h5>
										<div class="qtyButtons">
                                            <div class="qtyDec" onclick="modifyNumber('cantidad',-1)"></div>
                                            <input type="number" name="ActiveHours" value="<?=$activehours?>" id="cantidad" min="1"<?=($auctionedit==0)?Constants::DISABLED:''?>>
											<div class="qtyInc" onclick="modifyNumber('cantidad',1)"></div>
										</div>
									</div>
								</div>
								<div class="col-xl-4"><div class="submit-field"><h5>Fecha Tentativa de Entrega</h5><input type="text" data-field="datetime" class="with-border" placeholder="Ingresa la fecha..." readonly  value="<?=$tentativedate?>" name="fechaTentativa"<?=($auctionedit==0)?Constants::DISABLED:''?>><div class="dtBox" id='fechaTentativaDTP'></div></div></div>			
								<div class="col-xl-4">
									<div class="submit-field">
                                        <h5>Cantidad de <div id="UnidadDePresentacion" style='display:inline-block'><?=$presunit?></div> <i class="help-icon" data-tippy-placement="right" title="Cantidad disponible para esta subasta"></i></h5>
                                        <input class="with-border" id="cant_disp" type="number" placeholder="Ingresa la cantidad" name="amount"value="<?=$quantity?>"<?=($auctionedit==0)?Constants::DISABLED:''?>>
									</div>
								</div>
								
								<div class="col-xl-4">
									<div class="submit-field">
                                        <h5>Precio Inicial (por <div class='SaleUnits' style='display:inline-block'><?=$saleunit?></div>) <i class="help-icon" data-tippy-placement="right" title="El costo inicial de la unidad de venta"></i></h5>
										<div class="input-with-icon">
                                            <input class="with-border" type="number" step="0.01" placeholder="Ingresa el monto" min="10" name="startPrice" value="<?=$startprice?>"<?=($auctionedit==0)?Constants::DISABLED:''?>>
											<i class="currency">AR$</i>
										</div>
									</div>
								</div>

								<div class="col-xl-4">
									<div class="submit-field">
										<h5>Precio de Retiro  (por <div class='SaleUnits' style='display:inline-block'><?=$saleunit?></div>) <i class="help-icon" data-tippy-placement="right" title="Costo mínimo de la unidad de venta"></i></h5>
										<div class="input-with-icon">
                                            <input class="with-border" type="number" step="0.01" placeholder="Ingresa el monto" min="1" name="endPrice"value="<?=$endprice?>"<?=($auctionedit==0)?Constants::DISABLED:''?>>
											<i class="currency">AR$</i>
										</div>
									</div>
								</div>

								<div class="col-xl-12">
									<div class="submit-field">
										<h5>Descripci&oacute;n de la Subasta</h5>
                                        <textarea cols="30" rows="5" class="with-border" minlength="120" maxlength="1000" name="descri"<?=($auctionedit==0)?Constants::DISABLED:''?>><?=$description?></textarea>
									</div>
								</div>

							</div>
						</div>
						
						
					</div>
				</div>

				<div class="col-xl-12 text-right">
                    <button type="submit"value="Subastar" class="button dark ripple-effect big margin-top-30"<?=($auctionedit==0)?Constants::DISABLED:''?>>Subastar</button>
                    <a href="/subastas"><button class="button dark ripple-effect big margin-top-30" type="button">Cancelar</button></a>
				</div>
            </div>
        </div>
        </form>
<?php }else{
    echo '<h1>Solo pueden crear subastas los usuarios de tipo vendedor</h1>';
}?>
@endsection