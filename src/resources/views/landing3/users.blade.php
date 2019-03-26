<?php
use App\Constants;
?>
@extends('landing3/partials/layout-admin')
@section('title',' | Usuarios')
@section('content')
<div class="dashboard-content-inner" >
    <div class="dashboard-headline"><h3><i class="icon-feather-users"></i> Usuarios</h3></div>
    <div class="text-right">
        <a href="/usuarios/agregar" class="button ripple-effect big margin-bottom-10"><i class="icon-feather-plus"></i> Nuevo usuario</a>
    </div>
    <div style="margin:20px">
        @foreach($users as $user)
        <div class="row dashboard-box" style="padding-bottom: 20px">
            <div class="col">
                <div class="headline margin-bottom-20"><h4>
                        <i class="icon-feather-user"></i> <?= ucfirst($user->name).' '. ucfirst($user->lastname).' ('.$user->nickname.')'?>
                        <span class="dashboard-status-button <?=Constants::colorByStatus($user->status)?>" id="StatusMedal<?=$user->id?>"><?=trans('general.status.'.$user->status)?></span></h4></div>
                <div class="row">
                    <div class="col-sm text-center">
                        <em class="icon-feather-user-check"></em><br><?=trans('general.users_type.'.$user->type)?>
                    </div>
                    <div class="col-sm text-center">
                        <em class="icon-feather-mail"></em><br><a href="mailto:<?=$user->email?>"><?=$user->email?></a>
                    </div>
                    <div class="col-sm text-center">
                        <em class="icon-feather-phone"></em><br><a href='tel:<?=$user->phone?>'><?=$user->phone?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm text-center margin-top-20">
                        <a href="/usuarios/editar/<?=$user->id?>" class="button ripple-effect ico" title="Editar" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                        @if($user->type==Constants::COMPRADOR)
                        <a href="/usuarios/compras/<?=$user->id?>" class="button ripple-effect ico" title="Compras" data-tippy-placement="top"><i class="icon-feather-dollar-sign"></i></a>
                        <a href="/usuarios/ofertas/<?=$user->id?>" class="button ripple-effect ico" title="Ofertas" data-tippy-placement="top"><i class="icon-feather-tag"></i></a>
                        @endif
                        <a href="#UserApprove<?=$user->id?>" id="UserApprove<?=$user->id?>" onclick="users_changeApproval(<?=$user->id?>)" class="button ripple-effect ico" title="Ofertas" data-tippy-placement="top"<?=($user->status=='approved')?'style="display:none"':''?>><i class="icon-feather-check"></i></a>
                        <a href="#UserReject<?=$user->id?>" id="UserReject<?=$user->id?>" onclick="users_changeApproval(<?=$user->id?>)" class="button ripple-effect ico" title="Ofertas" data-tippy-placement="top"<?=($user->status!='approved')?'style="display:none"':''?>><i class="icon-feather-x"></i></a>
                    </div>
                </div>
                
            </div>
        </div>
        @endforeach
    </div>
    <?=$users->render()?>
</div>
@endsection
