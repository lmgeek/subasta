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

    protected $fillable = ['name', 'matricula', 'status','user_id','rebound'];

    public function arrive(){
        return $this->hasMany('App\Arrive');
    }
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function getLastArrive()
	{
		$arrive = $this->arrive()->orderBy('date','desc')->get();
		return $arrive;
//		if (null != $hasArrive)
//		{
//			return Carbon::parse($hasArrive->date)->format('H:i:s d/m/Y') ;
//		}else
//		{
//			return trans('boats.boats_no_arrive');
//		}
//
	}

	public static function filterAndPaginate($user = null,$status = null , $name = null)
	{

		$query = Boat::select('boats.*');
		if (!is_null($user) and count($user) > 0) $query->whereIn('user_id',$user);
		if (!is_null($status) and count($status) > 0) $query->whereIn('status',$status);
		if (!is_null($name)) $query->where('name', 'like', "%$name%");

		return $query->orderBy('created_at','desc')->get();

	}
	
}
