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
}
