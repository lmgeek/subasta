<?php
use App\Constants;
use App\User;
$title='Agregar';
$tipo=(isset(Auth::user()->type))?Auth::user()->type:null;
if($tipo==Constants::INTERNAL){
?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.$title.' usuario')
@section('content')
<div style="margin:20px">
    <form method="post" action="/usuarios/guardar">
        {{csrf_field()}}
        <div class="row">
            <div class="col">
                Tipo de Usuario<br>
                <select name="type" onchange="users_changeType()" class="selectpicker" id="UserType">
                    <option disabled selected>Seleccione...</option>
                    <option value="<?=Constants::INTERNAL?>">Administrador</option>
                    <option value="<?=Constants::SELLER?>">Vendedor</option>
                    <option value="buyer">Comprador</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col">
                Nombre<br><input type="text" name="name" placeholder="Nombre">
            </div>
            <div class="col">
                Apellido<br><input type="text" name="lastname" placeholder="Apellido">
            </div>
            <div class="col">
                Alias<br><input type="text" name="nickname" placeholder="Alias" maxlength="10">
            </div>
        </div>
        <div class="row UserPanel" id="BuyerPanel" style="display:none">
            <div class='col'>
                DNI<br>
                <input type="number" name="dni" placeholder="DNI" minlength="7" id="DNI">
            </div>
            <div class='col'>
                Limite de compra<br>
                <input type="number" name="limit" placeholder="L&iacute;mite de compra" minlength="10" id="Limit">
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
                Email<br><input type="email" name="email" placeholder="Email" minlength="7">
            </div>
            <div class="col">
                Telefono<br><input type="tel" name="phone" placeholder="Tel&eacute;fono">
            </div>
        </div>
        <div class="row">
            <div class="col">
                Contrase&ntilde;a<br><input type="password" name="password" placeholder="Contrase&ntilde;a" required="">
            </div>
            <div class="col">
                Confirmar contrase&ntilde;a<br><input type="password" name="password_confirmation" placeholder="Confirmar contrase&ntilde;a" required>
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
<?php }?>
