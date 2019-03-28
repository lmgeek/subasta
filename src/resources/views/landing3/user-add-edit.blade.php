<?php
use App\Constants;
use App\User;
if(isset($user->id)){
    $title='Editar usuario';
    if($user->id==Auth::user()->id){
        $title='Mi Cuenta';
    }
    $check= \App\Http\Controllers\UserController::checkIfUserCanChangeTypeApproval($user);
}else{
    $title='Agregar usuario';
}
if(!is_array($errors)){
    $errores=$errors->all();
}else{
    $errores=$errors;
}
$constants=new Constants();
$paises=$constants->paises;
unset($constants);
?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.$title)
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-user<?=($title=='Agregar usuario')?'-plus':''?>"></i> <?=$title?></h3></div>
    <div style="margin:20px;">
        @if (count($errors) > 0)
        <div class="row padding-bottom-20">
            <div class="col-xl-12">
                <div class="dashboard-box margin-top-0 ">
                    <div class="headline"><h4><i class="icon-feather-alert-triangle red"></i> Error<?=(count($errors)>1)?'es':''?></h4></div>
                    <ul>
                        @foreach ($errores as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        <form method="post" action="/usuarios/guardar">
            @if(isset($user->id))
            <input type="hidden" name="id" value='<?=$user->id?>'>
            @endif
            {{csrf_field()}}
            @if(Auth::user()->type=='internal')
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="headline"><h3><i class="icon-feather-user"></i> Tipo de Usuario</h3></div>
                <div class="content with-padding padding-bottom-0">
                    <div class="row">
                        <div class="col">
                            <select title="Seleccione..." name="type" data-translation="tipo de usuario" onchange="users_changeType()" class="selectpicker with-border" id="UserType"  <?=(isset($user) && $check['success']==0)?'disabled':''?> required>
                                <option disabled selected>Seleccione...</option>
                                <option value="<?=Constants::INTERNAL?>" <?=(isset($user) && $user->type== Constants::INTERNAL)?'selected':''?>>Administrador</option>
                                <option value="<?=Constants::SELLER?>" <?=(isset($user) && $user->type== Constants::VENDEDOR)?'selected':''?>>Vendedor</option>
                                <option value="buyer" <?=(isset($user) && $user->type=='buyer')?'selected':''?>>Comprador</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="headline"><h3><i class="icon-material-outline-account-circle"></i> Datos B&aacute;sicos</h3></div>
                <div class="content with-padding padding-bottom-0">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-sm">
                                    <h5>Nombre <i class="help-icon" data-tippy-placement="right" data-tippy="" data-original-title="Tranquilo. Nadie verá este dato"></i></h5>
                                    <input type="text" name="name" data-translation="nombre" placeholder="Nombre" value='<?=(isset($user))?$user->name:old('name')?>' required>
                                </div>
                                <div class="col-sm">
                                    <h5>Apellido <i class="help-icon" data-tippy-placement="right" data-tippy="" data-original-title="Tranquilo. Nadie verá este dato"></i></h5>
                                    <input type="text" name="lastname" data-translation="apellido" placeholder="Apellido" value='<?=(isset($user))?$user->lastname:old('lastname')?>' required>
                                </div>
                                <div class="col-sm">
                                    <h5>Alias <i class="help-icon" data-tippy-placement="right" data-tippy="" data-original-title="Nombre con el que usarás el sitio"></i></h5>
                                    <input type="text" name="nickname" data-translation="alias" placeholder="Alias" maxlength="10" value='<?=(isset($user))?$user->nickname:old('nickname')?>' required>
                                </div>
                            </div>
                            <div class="row UserPanel" id="BuyerPanel" <?=(empty($user) || (isset($user) && $user->type!=User::COMPRADOR))?'style="display:none"':''?>>
                                <div class='col-sm'>
                                    <h5>DNI</h5>
                                    <input type="number" name="dni" data-translation="DNI" placeholder="DNI" <?=(isset($user) && $user->type==User::COMPRADOR)?'minlength="7" required':''?> id="DNI" value="<?=(isset($user) && $user->type==User::COMPRADOR)?$user->comprador->dni:old('dni')?>">
                                </div>
                                @if(Auth::user()->type=='internal')
                                <div class='col-sm'>
                                    <h5>L&iacute;mite de compra</h5>
                                    <input type="number" name="limit" data-translation="l&iacute;mite" placeholder="L&iacute;mite de compra" <?=(isset($user) && $user->type==User::COMPRADOR)?'maxlength="10" required':''?>  id="Limit" value="<?=(isset($user) && $user->type==User::COMPRADOR)?$user->comprador->bid_limit:old('limit')?>">
                                </div>
                                @endif
                            </div>
                            <div class="row UserPanel" id="SellerPanel" <?=(empty($user) || (isset($user) && $user->type!=User::VENDEDOR))?'style="display:none"':''?>>
                                <div class='col'>
                                    <h5>CUIT</h5>
                                    <input type="text" name="cuit" placeholder="CUIT (Ej: 20-12345678-9)" <?=(isset($user) && $user->type==User::VENDEDOR)?'minlength="13" pattern="(20|23|24|27|30|33|34)(-)[0-9]{8}(-)[0-9]" required':''?>  id="CUIT" value="<?=(isset($user) && $user->type==User::VENDEDOR)?$user->vendedor->cuit:old('cuit')?>" onkeyup="users_formatCuit()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="headline"><h3><i class="icon-feather-message-square"></i> Contacto</h3></div>
                <div class="content with-padding padding-bottom-0">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="submit-field">
                                        <h5>Email</h5>
                                        <input type="email" name="email" data-translation="correo" placeholder="Email" minlength="7" value='<?=(isset($user))?$user->email:old('email')?>' required>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="submit-field">
                                        <h5>Tel&eacute;fono </h5>
                                        <input type="tel" name="phone" data-translation="tel&eacute;fono" placeholder="Tel&eacute;fono" value='<?=(isset($user))?$user->phone:old('phone')?>' required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row dashboard-box">
                <div class="headline"><h3><i class="icon-feather-home"></i> Locación</h3></div>
                <div class="content with-padding padding-bottom-0">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="submit-field">
                                        <h5>Domicilio</h5>
                                        <input type="text" data-translation="direcci&oacute;n" class="with-border" name="address" placeholder="Ingrese la direcci&oacute;n">
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="submit-field">
                                        <h5>Código Postal</h5>
                                        <input type="text" data-translation="c&oacute;digo postal" class="with-border" name="zipcode" placeholder="Ingrese el c&oacute;digo postal">
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="submit-field">
                                        <h5>Localidad</h5>
                                        <input type="text" class="with-border" name="localidad" placeholder="Ingrese la localidad">
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="submit-field">
                                        <h5>Provincia</h5>
                                        <input type="text" class="with-border" name="provincia" placeholder="Ingrese la provincia">
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="submit-field">
                                        <h5>País</h5>
                                        <select name="pais" id="Pais"  class="selectpicker with-border" data-live-search="true" title="Seleccione...">
                                            <option disabled selected>Seleccione...</option>
                                            @foreach($paises as $pais)
                                            <option value="<?=$pais['code2']?>"><?=$pais['name']?></option>
                                            @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="headline"><h3><i class="icon-material-outline-lock"></i> Contrase&ntilde;a &amp; Seguridad</h3></div>
                <div class="content with-padding padding-bottom-0">
                    <div class="row">
                        <div class="col">
                            <div class="row" id="PasswordContainer">
                                @if($title!='agregar' && Auth::user()->type!=User::INTERNAL)
                                <div class="col-sm">
                                    <h5>Contrase&ntilde;a actual</h5>
                                    <input type="password" data-translation="contrase&ntilde;a actual" pattern="(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)"  name="passwordcurrent" placeholder="Contrase&ntilde;a actual">
                                </div>  
                                @endif
                                <div class="col-sm">
                                    <h5>Contrase&ntilde;a Nueva</h5>
                                    <input type="password" data-translation="contrase&ntilde;a"  pattern="(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)" name="password" placeholder="Contrase&ntilde;a"<?=($title=='Agregar usuario')?'required':''?>>
                                    
                                </div>
                                <div class="col-sm">
                                    <h5>Repite la contrase&ntilde;a</h5>
                                    <input type="password" data-translation="confirmaci&oacute;n de contrase&ntilde;a" name="password_confirmation" placeholder="Confirmar contrase&ntilde;a"<?=($title=='Agregar usuario')?'required':''?>>
                                </div>  
                                <div class="w-100">
                                    <h5>&nbsp;</h5>
                                    <div class="button  ripple-effect big dark text-center" style="cursor:pointer;color:#fff" id="PassSwitcher" onclick="users_passSwitch()"><div class="fa fa-eye"></div> Mostrar</div>
                                </div> 
                                
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="checkbox">
                                        <input type="checkbox" id="two-step" name="2FA">
                                        <label for="two-step"><span class="checkbox-icon"></span> Activar <i>Two-Step Verification</i> via Email</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(Auth::user()->type=='internal')
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="headline"><h3><i class="icon-feather-user-<?=(empty($user) || (isset($user->status) && $user->status==User::APROBADO))?'check':'x'?>" id="UserApprobationIcon"></i> Aprobaci&oacute;n</h3></div>
                <div class="content with-padding padding-bottom-0">
                    <div class="row">
                        <div class="col">
                            <select name="status" title="Seleccione..." data-translation="aprobaci&oacute;n" class="selectpicker with-border" id="UserApprobation" onchange="users_userApprobation()" <?=(isset($user) && $check['success']==0)?'disabled':''?> >
                                <option disabled selected>Seleccione...</option>
                                <option value="<?=User::APROBADO?>" <?=(old('status')== User::APROBADO || (isset($user) && $user->status== User::APROBADO))?'selected':''?>>Aprobado</option>
                                <option value="<?= User::RECHAZADO?>" <?=(old('status')== User::RECHAZADO || (isset($user) && $user->status== User::RECHAZADO))?'selected':''?>>Rechazado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-xl-12 text-right">
                    <button type="submit"class="button dark ripple-effect big margin-top-30">Guardar</button>
                    <a href="<?= Illuminate\Support\Facades\URL::previous()?>"><button class="button dark ripple-effect big margin-top-30" type="button">Cancelar</button></a>
                </div>
            </div>
            @if(isset($user) && (count($user->offers)>0 || count($user->bids))>0 && Auth::user()->type=='internal')
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline">
                        <div class="row">
                            @if(count($user->offers)>0)
                            <div class="col text-center SwitchButton" onclick="users_switchOffersBids('Offers')" id="OffersButton">
                                <div class="button primary ripple-effect big" style="cursor:pointer;color:#fff"><i class="icon-feather-tag"></i> &Uacute;ltimas Ofertas Realizadas</div>
                            </div>
                            @endif
                            <div class="col text-center SwitchButton" onclick="users_switchOffersBids('Bids')" id="BidsButton">
                                <div class="button dark ripple-effect big" style="cursor:pointer;color:#fff"><i class="icon-feather-dollar-sign"></i> &Uacute;ltimas Compras Realizadas</div>
                            </div>
                        </div>
                    </div>
                    <div class="panel"id="Offers">
                    @foreach($user->offers as $auction)
                    <div class="row"<?=(count($user->offers)>0)?' style="margin:10px"':' style="display:none"'?>>
                        <div class="col">
                            <div class="headline">
                                <div class="col">
                                    <h3>
                                        <i class="icon-material-outline-gavel"></i> Subasta: <?= App\Http\Controllers\AuctionFrontController::getAuctionCode($auction->correlative, $auction->StartDateAuction)?>
                                        <span class="dashboard-status-button <?=Constants::colorByStatus($auction->status)?>"><?=trans('general.status.'.$auction->status)?></span>
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    Producto<br>
                                    <?=$auction->name.' '.trans('general.product_caliber.'.$auction->caliber)?>
                                </div>
                                <div class="col">
                                    Precio/ <?=$auction->sale_unit?><br>
                                    <?= number_format($auction->price, 2, ',', '.')?> ARS
                                </div>
                                <div class="col">
                                    Fecha de oferta<br>
                                    <?= date_create_from_format('Y-m-d H:i:s', $auction->created_at)->format('d/m/Y - H:i:s')?>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(count($user->offers)>0)
                    <a href="/usuarios/ofertas/<?=$user->id?>"><div class="button dark ripple-effect big text-center" style="cursor:pointer;color:#fff">Ver todas</div></a>
                    @endif
                    </div>
                    <div  class="panel"id="Bids" <?=(count($user->offers)>0)?'style="display:none"':''?>>
                    @foreach($user->bids as $auction)
                    <div class="row" style="margin:10px">
                        <div class="col">
                            <div class="headline">
                                <div class="col">
                                    <h3>
                                        <i class="icon-material-outline-gavel"></i> Subasta: <?= App\Http\Controllers\AuctionFrontController::getAuctionCode($auction->correlative, $auction->StartDateAuction)?>
                                        <span class="dashboard-status-button <?=Constants::colorByStatus($auction->status)?>"><?=trans('general.bid_status.'.$auction->status)?></span>
                                        
                                    </h3>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col">
                                    Producto<br>
                                    <?=$auction->name.' '.trans('general.product_caliber.'.$auction->caliber)?>
                                </div>
                                <div class="col">
                                    Precio/ <?=$auction->sale_unit?><br>
                                    <?= number_format($auction->price, 2, ',', '.')?> ARS
                                </div>
                                <div class="col">
                                    Cantidad<br>
                                    <?=$auction->amount?>
                                </div>
                                <div class="col">
                                    Total<br>
                                    <?= number_format($auction->price*$auction->amount, 2, ',', '.')?> ARS
                                </div>
                                <div class="col">
                                    Fecha de compra<br>
                                    <?= date_create_from_format('Y-m-d H:i:s', $auction->bid_date)->format('d/m/Y - H:i:s')?>
                                </div>
                            </div>
                            @if($auction->status==Constants::CONCRETADA || $auction->status==Constants::NO_CONCRETADA)
                            <div class="row" style="margin-top:10px;border-top: 1px solid #e0e0e0;">
                                @if($auction->status==Constants::NO_CONCRETADA)
                                <div class="col">
                                    Razon<br>
                                    <?=$auction->reason?>
                                </div>
                                @endif
                                <div class="col">
                                    Calificacion Comprador<br>
                                    <?=trans('general.buyer_qualification.'.$auction->user_calification)?>
                                </div>
                                <div class="col">
                                    Comentarios Comprador<br>
                                    <?=$auction->user_calification_comments?>
                                </div>
                                <div class="col">
                                    Calificacion Vendedor<br>
                                    <?=trans('general.buyer_qualification.'.$auction->seller_calification)?>
                                </div>
                                <div class="col">
                                    Comentarios Vendedor<br>
                                    <?=$auction->seller_calification_comments?>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @if(count($user->bids)>0)
                    <a href="/usuarios/compras/<?=$user->id?>"><div class="button dark ripple-effect big text-center" style="cursor:pointer;color:#fff">Ver todas</div></a>
                    @endif
                    </div>
                </div>
            </div>
            @endif
            
        </form>
    </div>
</div>
@endsection

