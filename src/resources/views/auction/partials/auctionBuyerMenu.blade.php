@if ( $type != \App\Constants::AUCTION_PRIVATE )
<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <form>
						<input type="hidden" name="status" value="{{ $request->get('status') }}"  />
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-noraml">Producto</label>
                                        <select data-placeholder="Seleccione una o varias opciones" name="product[]" class="chosen-select" multiple tabindex="4">
                                           @foreach($products as $product)
												<option @if (!is_null($request->get('product')) and in_array($product->id,$request->get('product'))) selected @endif value="{{ $product->id }}">{{ $product->name }}</option>
										   @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-noraml">Vendedor</label>
                                         <select data-placeholder="Seleccione una o varias opciones" name="seller[]" class="chosen-select" multiple tabindex="4">
                                           @foreach($sellers as $seller)
												<option @if (!is_null($request->get('seller')) and in_array($seller->id,$request->get('seller'))) selected @endif value="{{ $seller->id }}">{{ $seller->name }}</option>
										   @endforeach
                                        </select>
                                    </div>
                                </div>
                               
                            </div>
							 <div class="row">
								<div class="col-md-6">
									 <div class="form-group">
                                        <label class="font-noraml">Barco</label>
                                        <select data-placeholder="Seleccione una o varias opciones" name="boat[]" class="chosen-select" multiple tabindex="4">
                                           @foreach($boats as $boat)
												<option @if (!is_null($request->get('boat')) and in_array($boat->id,$request->get('boat'))) selected @endif value="{{ $boat->id }}">{{ $boat->name }}</option>
										   @endforeach
                                        </select>
                                    </div>
								</div>
							 </div>
							 <div class="row">
								 <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                </div>
							 </div>
                        </div>
                    </form>
                </div>
            </div>
@endif

<div class="col-lg-12 text-center">
	
	
	@if ($type == \App\Constants::AUCTION_PRIVATE)
		<a href="auction?status='{{\App\Constants::IN_COURSE  . '&type=' . \App\Constants::AUCTION_PRIVATE}}" class="btn @if($status == \App\Constants::IN_COURSE) btn-success @endif">{{ trans('auction.auction_in_curse') }}</a>
		<a href="auction?status='{{\App\Constants::FUTURE . '&type=' . \App\Constants::AUCTION_PRIVATE}}" class="btn @if($status == \App\Constants::FUTURE) btn-success @endif">{{ trans('auction.auction_future') }}</a>
	@else
		<a href="auction?status='{{\App\Constants::IN_COURSE}}" class="btn @if($status == \App\Constants::IN_COURSE) btn-success @endif">{{ trans('auction.auction_in_curse') }}</a>
		<a href="auction?status='{{\App\Constants::FUTURE}}" class="btn @if($status == \App\Constants::FUTURE) btn-success @endif">{{ trans('auction.auction_future') }}</a>
	@endif
	
	
</div>