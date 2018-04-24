<div class="col-md-4">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Cantidad de subastas por producto</h5>
        </div>
        <div class="ibox-content">
            <ul class="list-group">
                @if(count($products)>0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->cantidad }}</td>
                                <td>
                                    <a href="{{ url('auction?&product%5B%5D='.$p->product_id) }}" class="btn btn-action">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <li class="list-group-item text-center" >
                        No hay subastas activas en este momento
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>