<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ports extends Model
{

    protected $table = 'port';

    protected $fillable = ['name'];

    public function arrive(){
        return $this->hasMany('App\Arrive');
    }
	
	public function user()
	{
		return $this->belongsTo('App\User'); 
	}
	

	public static function filterAndPaginate()
	{

		$query = Ports::select('port.*');
		
		return $query->orderBy('id','desc')->get();

	}
	
}
