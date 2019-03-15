<?php
use App\Constants;
use App\User;
$title=(isset($user->id))?'editar':'Agregar';

?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.$title.' usuario')
@section('content')
<div style="margin:20px">
    @if (count($errors) > 0)
    <div class="alert alert-danger"><strong>Error<?=(count($errors)>1)?'es':''?></strong><br><br><ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
    </ul></div>
    @endif
    <form method="post" action="/usuarios/guardar">
        @if($title=='editar')
        <input type="hidden" name="id" value='<?=$user->id?>'>
        @endif
        {{csrf_field()}}
        @if(Auth::user()->type=='internal')
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
        @endif
        <div class="row">
            <div class="col">
                Nombre<br><input type="text" name="name" placeholder="Nombre" value='<?=(isset($user))?$user->name:old('name')?>'>
            </div>
            <div class="col">
                Apellido<br><input type="text" name="lastname" placeholder="Apellido" value='<?=(isset($user))?$user->lastname:old('lastname')?>'>
            </div>
            <div class="col">
                Alias<br><input type="text" name="nickname" placeholder="Alias" maxlength="10" value='<?=(isset($user))?$user->nickname:old('nickname')?>'>
            </div>
        </div>
        <div class="row UserPanel" id="BuyerPanel" <?=(empty($user) || (isset($user) && $user->type!=User::COMPRADOR))?'style="display:none"':''?>>
            <div class='col'>
                DNI<br>
                <input type="number" name="dni" placeholder="DNI" minlength="7" id="DNI" value="<?=(isset($user) && $user->type==User::COMPRADOR)?$user->comprador->dni:old('dni')?>">
            </div>
            <div class='col'>
                Limite de compra<br>
                <input type="number" name="limit" placeholder="L&iacute;mite de compra" maxlength="10" id="Limit" value="<?=(isset($user) && $user->type==User::COMPRADOR)?$user->comprador->bid_limit:old('limit')?>">
            </div>
        </div>
        <div class="row UserPanel" id="SellerPanel" <?=(empty($user) || (isset($user) && $user->type!=User::VENDEDOR))?'style="display:none"':''?>>
            <div class='col'>
                CUIT<br>
                <input type="text" name="cuit" placeholder="CUIT" minlength="13" id="CUIT" value="<?=(isset($user) && $user->type==User::VENDEDOR)?$user->vendedor->cuit:old('cuit')?>">
            </div>
        </div>
        <div class="row">
            <div class="col">
                Email<br><input type="email" name="email" placeholder="Email" minlength="7" value='<?=(isset($user))?$user->email:old('email')?>'>
            </div>
            <div class="col">
                Telefono<br><input type="tel" name="phone" placeholder="Tel&eacute;fono" value='<?=(isset($user))?$user->phone:old('phone')?>'>
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
        @if(Auth::user()->type=='internal')
        <div class="row">
            <div class="col">
                Aprobaci&oacute;n<br>
                <select name="status" class="selectpicker" id="UserApprobation">
                    <option disabled selected>Seleccione...</option>
                    <option value="<?=User::APROBADO?>" <?=(isset($user) && $user->status== User::APROBADO)?'selected':''?>>Aprobado</option>
                    <option value="<?= User::RECHAZADO?>" <?=(isset($user) && $user->status== User::RECHAZADO)?'selected':''?>>Rechazado</option>
                </select>
            </div>
        </div>
        @endif
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

