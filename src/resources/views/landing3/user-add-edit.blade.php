<?php
use App\Constants;
use App\User;
$title=(isset($user->id))?'editar':'Agregar';
?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.$title.' usuario')
@section('content')
<div style="margin:20px">
    <form method="post" action="/usuarios/guardar">
        @if($title=='editar')
        <input type="hidden" name="id" value='<?=$user->id?>'>
        @endif
        {{csrf_field()}}
        <div class="row">
            <div class="col">
                Tipo de Usuario<br>
                <select name="type" onchange="users_changeType()" class="selectpicker" id="UserType">
                    <option disabled selected>Seleccione...</option>
                    <option value="<?=Constants::INTERNAL?>" <?=(isset($user) && $user->type== Constants::INTERNAL)?'selected':''?>>Administrador</option>
                    <option value="<?=Constants::SELLER?>" <?=(isset($user) && $user->type== Constants::VENDEDOR)?'selected':''?>>Vendedor</option>
                    <option value="buyer" <?=(isset($user) && $user->type=='buyer')?'selected':''?>>Comprador</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Nombre<br><input type="text" name="name" placeholder="Nombre" value='<?=(isset($user))?$user->name:''?>'>
            </div>
            <div class="col">
                Apellido<br><input type="text" name="lastname" placeholder="Apellido" value='<?=(isset($user))?$user->lastname:''?>'>
            </div>
            <div class="col">
                Alias<br><input type="text" name="nickname" placeholder="Alias" maxlength="10" value='<?=(isset($user))?$user->nickname:''?>'>
            </div>
        </div>
        <div class="row UserPanel" id="BuyerPanel" style="display:none">
            <div class='col'>
                DNI<br>
                <input type="number" name="dni" placeholder="DNI" minlength="7" id="DNI">
            </div>
            <div class='col'>
                Limite de compra<br>
                <input type="number" name="limit" placeholder="L&iacute;mite de compra" maxlength="10" id="Limit">
            </div>
        </div>
        <div class="row UserPanel" id="SellerPanel" style="display:none">
            <div class='col'>
                CUIT<br>
                <input type="number" name="cuit" placeholder="CUIT" minlength="13" id="CUIT">
            </div>
        </div>
        <div class="row">
            <div class="col">
                Email<br><input type="email" name="email" placeholder="Email" minlength="7" value='<?=(isset($user))?$user->email:''?>'>
            </div>
            <div class="col">
                Telefono<br><input type="tel" name="phone" placeholder="Tel&eacute;fono" value='<?=(isset($user))?$user->phone:''?>'>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Contrase&ntilde;a<br><input type="password" name="password" placeholder="Contrase&ntilde;a"<?=($title=='agregar')?'required':''?>>
            </div>
            <div class="col">
                Confirmar contrase&ntilde;a<br><input type="password" name="password_confirmation" placeholder="Confirmar contrase&ntilde;a"<?=($title=='agregar')?'required':''?>>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 text-right">
                <button type="submit"class="button dark ripple-effect big margin-top-30">Guardar</button>
                <a href="/usuarios"><button class="button dark ripple-effect big margin-top-30" type="button">Cancelar</button></a>
            </div>
        </div>
    </form>
</div>
<script>
function users_changeType(){
    let $type=$('#UserType').val();
    $('.UserPanel').fadeOut();
    $('#DNI').removeAttr('required');
    $('#Limit').removeAttr('required');
    $('#CUIT').removeAttr('required');
    if($type=='buyer'){
        $('#BuyerPanel').fadeIn();
        $('#DNI').attr('required','true');
        $('#Limit').attr('required','true');
    }else if($type=='seller'){
        $('#SellerPanel').fadeIn();
        $('#CUIT').attr('required','true');
    }
}
</script>
@endsection

