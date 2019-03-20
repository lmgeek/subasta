<?php
use App\Constants;
use App\User;
if(isset($user->id)){
    $title='Editar usuario';
    if($user->id==Auth::user()->id){
        $title='Mi Cuenta';
    }
}else{
    $title='Agregar usuario';
}

?>
@extends('landing3/partials/layout-admin')
@section('title',' | '.$title)
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-user<?=($title=='Agregar usuario')?'-plus':''?>"></i> <?=$title?></h3></div>
    <div style="margin:20px">
        @if (count($errors) > 0)
        <div class="row padding-bottom-20">
            <div class="col-xl-12">
                <div class="dashboard-box margin-top-0 ">
                    <div class="headline"><h4><i class="icon-feather-alert-triangle red"></i> Error<?=(count($errors)>1)?'es':''?></h4></div>
                    <ul>
                        @foreach ($errors->all() as $error)
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
                <div class="col">
                    <div class="headline"><h3><i class="icon-feather-user"></i> Tipo de Usuario</h3></div>
                    <select name="type" onchange="users_changeType()" class="selectpicker" id="UserType" <?=(isset($user) && count($user->offers)+count($user->bids)>0)?'disabled':''?>>
                        <option disabled selected>Seleccione...</option>
                        <option value="<?=Constants::INTERNAL?>" <?=(isset($user) && $user->type== Constants::INTERNAL)?'selected':''?>>Administrador</option>
                        <option value="<?=Constants::SELLER?>" <?=(isset($user) && $user->type== Constants::VENDEDOR)?'selected':''?>>Vendedor</option>
                        <option value="buyer" <?=(isset($user) && $user->type=='buyer')?'selected':''?>>Comprador</option>
                    </select>
                </div>
            </div>
            @endif
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline"><h3><i class="icon-feather-users"></i> Datos B&aacute;sicos</h3></div>
                    <div class="row">
                        <div class="col">
                            Nombre<br><input type="text" name="name" placeholder="Nombre" value='<?=(isset($user))?$user->name:old('name')?>' required>
                        </div>
                        <div class="col">
                            Apellido<br><input type="text" name="lastname" placeholder="Apellido" value='<?=(isset($user))?$user->lastname:old('lastname')?>' required>
                        </div>
                        <div class="col">
                            Alias<br><input type="text" name="nickname" placeholder="Alias" maxlength="10" value='<?=(isset($user))?$user->nickname:old('nickname')?>' required>
                        </div>
                    </div>
                    <div class="row UserPanel" id="BuyerPanel" <?=(empty($user) || (isset($user) && $user->type!=User::COMPRADOR))?'style="display:none"':''?>>
                        <div class='col'>
                            DNI<br>
                            <input type="number" name="dni" placeholder="DNI" minlength="7" id="DNI" value="<?=(isset($user) && $user->type==User::COMPRADOR)?$user->comprador->dni:old('dni')?>">
                        </div>
                        @if(Auth::user()->type=='internal')
                        <div class='col'>
                            Limite de compra<br>
                            <input type="number" name="limit" placeholder="L&iacute;mite de compra" maxlength="10" id="Limit" value="<?=(isset($user) && $user->type==User::COMPRADOR)?$user->comprador->bid_limit:old('limit')?>">
                        </div>
                        @endif
                    </div>
                    <div class="row UserPanel" id="SellerPanel" <?=(empty($user) || (isset($user) && $user->type!=User::VENDEDOR))?'style="display:none"':''?>>
                        <div class='col'>
                            CUIT<br>
                            <input type="text" name="cuit" placeholder="CUIT" minlength="13" id="CUIT" value="<?=(isset($user) && $user->type==User::VENDEDOR)?$user->vendedor->cuit:old('cuit')?>">
                        </div>
                    </div>
                </div>
                    
            </div>
            
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline"><h3><i class="icon-feather-message-square"></i> Contacto</h3></div>
                    <div class="row">
                        <div class="col">
                            Email<br><input type="email" name="email" placeholder="Email" minlength="7" value='<?=(isset($user))?$user->email:old('email')?>'required>
                        </div>
                        <div class="col">
                            Telefono<br><input type="tel" name="phone" placeholder="Tel&eacute;fono" value='<?=(isset($user))?$user->phone:old('phone')?>' required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline"><h3><i class="icon-line-awesome-key"></i> Contrase&ntilde;a</h3></div>
                    <div class="row">
                        <div class="col">
                            Contrase&ntilde;a<br><input type="password" name="password" placeholder="Contrase&ntilde;a"<?=($title=='agregar')?'required':''?>>
                        </div>
                        <div class="col">
                            Confirmar contrase&ntilde;a<br><input type="password" name="password_confirmation" placeholder="Confirmar contrase&ntilde;a"<?=($title=='agregar')?'required':''?>>
                        </div>
                    </div>
                </div>       
            </div>
            @if(Auth::user()->type=='internal')
            <div class="row dashboard-box" style="padding-bottom: 20px">
                <div class="col">
                    <div class="headline"><h3><i class="icon-feather-user-<?=(empty($user) || (isset($user->status) && $user->status==User::APROBADO))?'check':'x'?>" id="UserApprobationIcon"></i> Aprobaci&oacute;n</h3></div>
                    <select name="status" class="selectpicker" id="UserApprobation" onchange="users_userApprobation()">
                        <option disabled selected>Seleccione...</option>
                        <option value="<?=User::APROBADO?>" <?=(old('status')== User::APROBADO || (isset($user) && $user->status== User::APROBADO))?'selected':''?>>Aprobado</option>
                        <option value="<?= User::RECHAZADO?>" <?=(old('status')== User::RECHAZADO || (isset($user) && $user->status== User::RECHAZADO))?'selected':''?>>Rechazado</option>
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

