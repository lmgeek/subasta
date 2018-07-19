<?php $bids = $bids->get(); ?>
@if ($bids->count() > 0) 
 <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('auction.summary_operations') }}</h5>
                    </div>
                    <div class="ibox-content table-responsive">
                        <table class="table table-bordered table-hover dataTables-example dataTable">
                            <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Importe total</th>
                                <th>Fecha</th>
                                <th>Barco</th>
                                <th>Estado</th>
                                <th>Puntaje Vendedor</th>
								<th>Puntaje Comprador</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bids as $sale)
                                <tr>
                                    <td>{{ $sale->auction->batch->arrive->boat->user->name }}</td>
                                    <td>{{ $sale->amount }} {{ trans('general.product_units.'.$sale->auction->batch->product->unit ) }}</td>
                                    <td>{{ $sale->auction->batch->product->name }}</td>
                                    <td class="text-right">$ {{ number_format($sale->price * $sale->amount , 2) }}</td>
                                    <td class="text-right">{{ date('d/m/Y H:i:s',strtotime($sale->bid_date)) }}</td>
                                    <td>{{ $sale->auction->batch->arrive->boat->name }}</td>
                                    <?php
                                        switch ($sale->status){
                                            case \App\Bid::PENDIENTE:
                                                $class = "warning";
                                                break;
                                            case \App\Bid::NO_CONCRETADA:
                                                $class = "danger";
                                                break;
                                            default:
                                                $class = "success";
                                        }
                                    ?>
                                    <td class="{{ $class }}">{{ trans('general.bid_status.'.$sale->status) }}</td>
									<td><center>
											@if ($sale->user_calification == \App\Bid::CALIFICACION_POSITIVA)
												<span class="text-navy" data-toggle="tooltip" data-placement="top" title="{{ $sale->user_calification_comments }}"  style="font-size:18px;"><i class="fa fa-plus-circle"></i> </span>
											@endif
											@if ($sale->user_calification == \App\Bid::CALIFICACION_NEGATIVA)
												<span class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ $sale->user_calification_comments }}"  style="font-size:18px;"><i class="fa fa-minus-circle"></i> </span>
											@endif
											@if ($sale->user_calification == \App\Bid::CALIFICACION_NEUTRAL)
												<span data-toggle="tooltip" data-placement="top" title="{{ $sale->user_calification_comments }}"  style="color:#BABABA;font-size:18px;"><i class="fa fa-dot-circle-o"></i> </span>
											@endif
											</center>
                                    </td>
									<td>
										<center>
											@if ($sale->seller_calification == \App\Bid::CALIFICACION_POSITIVA)
												<span class="text-navy" data-toggle="tooltip" data-placement="top" title="{{ $sale->seller_calification_comments }}"  style="font-size:18px;"><i class="fa fa-plus-circle"></i> </span>
											@endif
											@if ($sale->seller_calification == \App\Bid::CALIFICACION_NEGATIVA)
												<span class="text-danger" data-toggle="tooltip" data-placement="top" title="{{ $sale->seller_calification_comments }}"  style="font-size:18px;"><i class="fa fa-minus-circle"></i> </span>
											@endif
											@if ($sale->seller_calification == \App\Bid::CALIFICACION_NEUTRAL)
												<span data-toggle="tooltip" data-placement="top" title="{{ $sale->seller_calification_comments }}"  style="color:#BABABA;font-size:18px;"><i class="fa fa-dot-circle-o"></i> </span>
											@endif
										</center>
									</td>
									 
									
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
				
@endif