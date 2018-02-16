<div class="col-md-6">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Resumen ultimas compras</h5>
        </div>
        <div class="ibox-content">
            @if(count($resumen)>0)
                <table class="table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($resumen as $r)
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->amount }} {{ trans('general.product_units.'.$r->unit) }}</td>
                            <td>$ {{ $r->total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <ul class="list-group">
                    <li class="list-group-item text-center" >
                        No hay proximos arrivos de tus compras
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>