<?php

?>
@extends('landing3/partials/layout-admin')
@section('title',' | Agregar Puerto')
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-anchor"></i> Puertos</h3></div>
    <div style="margin:20px">
        @foreach($ports as $port)
        <div class="row dashboard-box" style="padding-bottom: 20px">
            <div class="col">
                <div class="headline margin-bottom-20"><h3><?=$port->name?></h3></div>
                <a href="/puertos/editar/<?=$port->id?>" class="button ripple-effect ico" title="Editar" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
            </div>
        </div>
        @endforeach
    </div>
    <?=$ports->render()?>
</div>
@endsection
