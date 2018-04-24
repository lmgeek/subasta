<div class="col-md-4">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Precio promedio de venta de productos</h5>
        </div>
        <div class="ibox-content" style="height: 200px; overflow-x: auto">

                @if(count($prices)>0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($prices as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>$ {{ $p->average }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <ul class="list-group">
                        <li class="list-group-item text-center" >
                            No hay productos que listar
                        </li>
                    </ul>
                @endif

        </div>
    </div>
</div>