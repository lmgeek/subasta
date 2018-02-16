<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrive extends Model
{
    protected $table = 'arrives';

    protected $fillable = ['boat_id', 'date'];

    public function boat(){
        return $this->belongsTo('App\Boat');
    }
	
	public function batch()
	{
		return $this->hasMany('App\Batch');
	}
}
