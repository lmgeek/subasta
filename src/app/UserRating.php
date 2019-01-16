<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $table = 'users_ratings';

    protected $fillable = ['user_id', 'positive','negative','neutral'];

    public function user(){
        return $this->belongsTo('App\User');
    }


    //Calculamos el rating del usuario
    public function calculateTheRatingUser($user_id)
    {

        $userRating = UserRating::Select('positive','negative','neutral')->where('user_id','=',$user_id)->get();

        //convertirmos el resultado de la consulta en array
        $userRating = $userRating->toArray();

        foreach ($userRating as $key => $value){
            $suma=  array_sum($value);

        }
        if ($suma>0){
            $porcentaje = round((($userRating[0]['positive']*100)/$suma)/20 , 1,PHP_ROUND_HALF_UP);
        }
        echo $porcentaje;
    }



}
