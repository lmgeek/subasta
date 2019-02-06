@extends('mail')


@section('content')
<body>

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
										<div style="text-align:center;background-color:#1ab394;color:#FFF;font-size:18px;font-weight:bold;padding:10px;">{{ trans('users.offer_auction')  }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <h3>{{ $user['name']  }}</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                      Ha realizado una oferta por el total del producto:
                                        <table>
                                            <tr>
                                                <td><strong>Producto:</strong></td>
                                                <td>{{ $product['product'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Calibre:</strong></td>
                                                <td>{{ $product['caliber'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Calidad:</strong></td>
                                                <td>
                                                    <div style="display: inline-block;">
                                                        @for ($i = 1; $i <= $product['quality']; $i++)
                                                            <span style="text-decoration: none; display: inline-block; font-size: 32px; font-size: 2rem; color: #f8ac59;">&#9733;</span>
                                                        @endfor
                                                    </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Presentación:&nbsp;&nbsp;&nbsp;</strong></td>
                                                <td>{{ $product['unit'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Precio:</strong></td>
                                                <td>${{ $product['price'] }}</td>
                                            </tr>
                                        </table>
                                        <br><br><br>
                                        Será notificado si fue el ganador de la subasta.
									  
                                    </td>
                                </tr>
								 <tr>
                                    <td class="content-block">
                                     
									 
									  <a href="{{ url('/auction') }}">
									  <button type="button" class="btn btn-primary" >Ir a subastas</button>
									  </a>
                                    </td>
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