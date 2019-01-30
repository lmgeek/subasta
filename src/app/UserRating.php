<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $table = 'users_ratings';

    protected $fillable = ['user_id', Constants::CALIFICACION_POSITIVA,Constants::CALIFICACION_NEGATIVA,Constants::CALIFICACION_NEUTRAL];

    public function user(){
        return $this->belongsTo('App\User');
    }


    //Calculamos el rating del usuario
    public function calculateTheRatingUser($user_id)
    {

        $userRating = UserRating::Select(Constants::CALIFICACION_POSITIVA,Constants::CALIFICACION_NEGATIVA,Constants::CALIFICACION_NEUTRAL)->where('user_id','=',$user_id)->get();

        //convertirmos el resultado de la consulta en array
        $userRating = $userRating->toArray();
        $suma=0;$porcentaje=0;
        foreach ($userRating as $value){
            $suma=  array_sum($value);

        }
        if ($suma>0){
            $porcentaje = round((($userRating[0][Constants::CALIFICACION_POSITIVA]*100)/$suma)/20 , 1,PHP_ROUND_HALF_UP);
        }
        echo $porcentaje;
    }



}
