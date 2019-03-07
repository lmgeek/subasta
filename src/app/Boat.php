<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Constants;
use Illuminate\Support\Facades\Auth;

class Boat extends Model
{


    protected $table = 'boats';

    protected $fillable = ['name', 'matricula', 'status',Constants::USER_ID,'rebound', 'preference_port'];

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

	}

	public static function filterAndPaginate($user = null,$status = null , $name = null)
	{
		$query = Boat::select('boats.*');
		if (!is_null($user) && count($user) > 0){$query->whereIn(Constants::USER_ID,$user);}
		if (!is_null($status) && count($status) > 0){$query->whereIn('status',$status);}
		if (!is_null($name)){$query->where('name', 'like', "%$name%");}
		return $query->orderBy('created_at','desc')->get();
	}

// funcion para traer la informacion del barco
    public static function getInfoBoat($id){
        return Boat::select()->where(Constants::USER_ID,'=',$id)->get();
    }
    //Funcion para convertir numeros a numeros romanos
    public static function  RomanNumber($integer){
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100,
            'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9,
            'V'=>5, 'IV'=>4, 'I'=>1);
        $return = '';
        while($integer > 0) {
            foreach($table as $rom=>$arb) {
                if($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }
        return $return;
    }

    //G.B creamo una funcion para traer los puertos de preferencias asociados a los barcos
    public function preferencePort($idBarcoReference)
    {
        $port = Ports::select('name')->where('id', '=', $idBarcoReference)->get();

        foreach ($port as $key => $value) {
           echo  $value['name'];
        }
    }
}
