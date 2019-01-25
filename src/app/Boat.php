<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Boat extends Model
{
    const PENDIENTE = "pending";
    const APROBADO = "approved";
    const RECHAZADO = "rejected";

    protected $table = 'boats';

    protected $fillable = ['name', 'matricula', 'status','user_id','rebound', 'nickname'];

    public function arrive(){
        return $this->hasMany('App\Arrive');
    }
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function getLastArrive()
	{
		return $this->arrive()->orderBy('date','desc')->get();
//		if (null != $hasArrive)
//		{
//			return Carbon::parse($hasArrive->date)->format('H:i:s d/m/Y') ;
//		}else000
//		{
//			return trans('boats.boats_no_arrive');
//		}
//
	}

	public static function filterAndPaginate($user = null,$status = null , $name = null)
	{

		$query = Boat::select('boats.*');
		if (!is_null($user) && count($user) > 0) $query->whereIn('user_id',$user);
		if (!is_null($status) && count($status) > 0) $query->whereIn('status',$status);
		if (!is_null($name)) $query->where('name', 'like', "%$name%");

		return $query->orderBy('created_at','desc')->get();

	}

// funcion para traer el sellerNickname
    public static function filterForSellerNickname($id)
    {
        $query = Boat::select('boats.name')
                ->where('user_id','=',$id);
        return $query->get();
    }


    //Funcion para convertir numeros a numeros romanos
    public static function  RomanNumber($integer)
    {
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100,
            'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9,
            'V'=>5, 'IV'=>4, 'I'=>1);
        $return = '';
        while($integer > 0)
        {
            foreach($table as $rom=>$arb)
            {
                if($integer >= $arb)
                {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }
        return $return;
    }





}
