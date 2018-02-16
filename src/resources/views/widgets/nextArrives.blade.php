<?php use Carbon\Carbon; ?>
<div class="col-md-6">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Proximos arribos de tus compras</h5>
        </div>
        <div class="ibox-content" style="height: 200px; overflow-x: auto">

                @if(count($arrives)>0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Barco</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($arrives as $a)
                            <tr>
                                <td>{{ $a->product }}</td>
                                <td>{{ $a->cantidad }} {{ trans('general.product_units.'.$a->unit) }}</td>
                                <td>{{ $a->boat }}</td>
                                <td>{{ Carbon::parse($a->date)->format('H:i:s d/m/Y') }}</td>
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