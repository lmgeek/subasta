<?php
/*
 * action for the form goes to =
 * The select provided will be replaced from the hardcoded one
 * the content from popup-barco goes exactly as the provided 
 * the only change is the select
 */

$ports= \App\Ports::select()->orderBy('name', 'asc')->get();
use Illuminate\Routing\Controllers;
?>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Error</strong><br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form action="/addbarco" method="POST">
    {{ csrf_field() }}
<input type="text" name="name" placeholder="nombre">
<input type="text" name="matricula" placeholder="Matricula">

<select name="port" class="selectpicker with-border">
    <option value="">Seleccione...</option>
    @foreach($ports as $port)
    <option value='{{$port->id}}'>{{$port->name}}</option>
    @endforeach
</select>
    <button type="submit" class="btn btn-primary noDblClick" data-loading-text="Guardando...">Guardar</button>
</form>
