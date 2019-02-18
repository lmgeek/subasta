<?php
/*
 * action for the form goes to =
 * The select provided will be replaced from the hardcoded one
 * the content from popup-barco goes exactly as the provided 
 * the only change is the select
 */
$ports= \App\Ports::select()->get();
?>
<select class="selectpicker with-border" title="Selecciona...">
    @foreach($ports as $port)
    <option value='{{$port->id}}'>{{$port->name}}</option>
    @endforeach
</select>



