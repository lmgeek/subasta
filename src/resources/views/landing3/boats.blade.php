<?php
/*
 * In @section('content') goes all that's inside
 * .dashboard-content-container
 * (Lines 30->166 dash-list-barcos)
 * In the foreach goes each ship container with all the actions and data
 * 
 */

/*
    NOTA:

    Se instancia la clase barco pora usar la funcion referencePort()
    esta funcion permite bucar el puerto de referencia asociado a un
    barco, todo lo que se debe hacer  es llamar la funcion y pasar como
    parametro "$barco->preference_port" ejemplo:

    {{$objt->referencePort($barco->preference_port)}}

    La variable "$CantidadBarco" tiene la cantidad de barco que un usurio
    pose, solo debes colocarla en el lugar que va, ejemplo:

    <td>{{$CantidadBarco}}</td>


    La funcion "render()" permite paginar

*/

use App\Boat;

$objt = new Boat();
$CantidadBarco = count($objt->filterForSellerNickname(Auth::user()->id));
?>

{{--@extends('landing3/partials/layout-admin')--}}
@section('title',' | Mis Barcos')
@section('content')
    <table class="table table-bordered table-hover dataTables-example">
        <thead>
        <tr role="row">
            <th>
                Barcos
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($boats as $boat)

            <tr>
                <td><?php echo 'Boat <br>';var_dump($boat['name'],$boat['matricula'],$boat['status']);echo '<br>'?></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    //Paginacion
    <?php echo $boats->render(); ?>


    <table class="table table-bordered table-hover dataTables-example">
        <thead>
        <tr role="row">
            <th>
                {{ trans('boats.port.title') }}
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($boats as $barco)
            <tr>
                <td>{{$objt->preferencePort($barco->preference_port)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h1>cantidad de barcos: {{$CantidadBarco}}</h1><br>

@endsection