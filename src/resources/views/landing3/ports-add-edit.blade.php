<?php

?>
@extends('landing3/partials/layout-admin')
@section('title',' | Puertos')
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-anchor"></i> <?=(isset($port))?'Editar':'Agregar'?> Puerto</h3></div>
    <div style="margin:20px">
        @if (count($errors) > 0)
        <div class="row padding-bottom-20 errors">
            <div class="col-xl-12">
                <div class="dashboard-box margin-top-0 ">
                    <div class="headline"><h4><i class="icon-feather-alert-triangle red"></i> Error<?=(count($errors)>1)?'es':''?></h4></div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        <form method="post" action="/puertos/guardar">
            @if(isset($port->id))
            <input type="hidden" name="id" value='<?=$port->id?>'>
            @endif
            {{csrf_field()}}
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline"><h3><i class="icon-feather-anchor"></i> Nombre del puerto</h3></div>
                    <input type="text" name="name" placeholder="Nombre" maxlength="39" value="<?=(isset($port))?$port->name:old('name')?>"required>
                </div>
            </div>
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline"><h3><i class="icon-feather-anchor"></i> Imagen</h3></div>
                    <input type="text" name="image" placeholder="Imagen" maxlength="255" value="<?=(isset($port))?$port->image:old('image')?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 text-right">
                    <button type="submit"class="button dark ripple-effect big margin-top-30">Guardar</button>
                    <a href="/puertos"><button class="button dark ripple-effect big margin-top-30" type="button">Cancelar</button></a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
