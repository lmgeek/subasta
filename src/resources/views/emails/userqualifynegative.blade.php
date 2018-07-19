@extends('mail')


@section('content')
<?php use Carbon\Carbon; ?>
<body>
<style>
.table { width:100% }
.table th ,.table td   { font-size:11px; }

</style>
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table  cellpadding="0" width="100%" cellspacing="0">
                                <tr>
                                    <td>
										<div style="text-align:center;background-color:#ec4758;color:#FFF;font-size:18px;font-weight:bold;padding:10px;">{{ trans('auction.qualification_negative')  }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block"><br>
										@if (\App\User::VENDEDOR == $type)
											El comprador {{ $user['name']  }}, Calificó negativo al vendedor  {{ $seller['name']  }}
										@else
											El Vendedor {{ $seller['name']  }}, Calificó negativo al comprador  {{ $user['name']  }}
										@endif
										
										 
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
									 <strong>Motivo :</strong> <br>
                                     {{ $comentariosCalificacion  }}
                                    </td>
                                </tr>
								
								 <tr>
                                    <td class="content-block">
										    <div class="ibox-content">
                        <table border="1"  class="table table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
									
                                    <th >{{ trans('auction.buyer') }}</th>
									<th>{{ trans('general.users_type.seller') }}</th>
                                    <th>{{ trans('auction.amount') }}</th>
                                    <th>{{ trans('auction.unity_price') }}</th>
                                    <th>{{ trans('auction.price') }}</th>
                                    <th>{{ trans('auction.date') }}</th>
                                    <th>{{ trans('auction.status') }}</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
								<?php $b = $bid;   ?>
                                
                                    <tr>
										
                                        <td>
                                           
                                              {{ $b['user']['name'] }}
                                           
                                        </td>
										 <td>
                                            {{ $b['auction']['batch']['arrive']['boat']['user']['name']  }}
                                        </td>
                                        <td>{{ $b['amount'] }} {{ trans('general.product_units.'.$b['auction']['batch']['product']['unit']) }}</td>
                                        <td style="text-align: right">$ {{ number_format($b['price'],2) }}</td>
                                        <td style="text-align: right">$ {{ number_format($b['price'] * $b['amount'],2) }}</td>
                                        <td style="text-align: right">{{ Carbon::parse($b['bid_date'])->format('H:i:s d/m/Y') }}</td>
                                        <td>
                                            @if ($b['status'] == \App\Bid::PENDIENTE)
                                                {{ trans('general.bid_status.'.$b['status']) }}
                                            @else
                                               
                                                {{ trans('general.bid_status.'.$b['status']) }}
                                               
                                            @endif
                                        </td>
                                       
                                    </tr>
                                
                            </tbody>
                        </table>
                    </div>
									<td>
                                </tr>
								
                               
                                <tr>
                                    <td class="content-block aligncenter">
                                        
                                    </td>
                                </tr>
                              </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                   
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
@endsection