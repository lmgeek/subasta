<?php

namespace App;

use App\Constants;
class ViewHelper
{
    static function userStatusClass($status)
    {
        switch ($status){
            case Constants::PENDIENTE:
                $rtrn = 'warning';
                break;
            case Constants::APROBADO:
                $rtrn = 'primary';
                break;
            case Constants::RECHAZADO:
                $rtrn = 'danger';
                break;
            default:
                throw new \Exception('Status no encontrado.');
        }

        return $rtrn;
    }

    static function UserAdditionalInfo($user)
    {
        switch ($user->type){
            case User::COMPRADOR:
                $info = Comprador::where('user_id',$user->id)->first();
                break;
            case User::VENDEDOR:
                $info = Vendedor::where('user_id',$user->id)->first();
                break;
            default:
                throw new \Exception('Tipo de usuario no encontrado.');
        }

        return $info;
    }

    static function includeUserInfo($type){
        switch ($type){
            case User::COMPRADOR:
                $viewName = 'user.partials.buyerInfo';
                break;
            case User::VENDEDOR:
                $viewName = 'user.partials.sellerInfo';
                break;
            default:
                throw new \Exception('Tipo de usuario no encontrado.');
        }

        return $viewName;
    }
	
	static function boatStatusClass($status)
    {
        switch ($status){
            case Constants::PENDIENTE:
                $rtrn = 'warning';
                break;
            case Constants::APROBADO:
                $rtrn = 'primary';
                break;
            case Constants::RECHAZADO:
                $rtrn = 'danger';
                break;
            default:
                throw new \Exception('Status no encontrado.');
        }

        return $rtrn;
    }

}