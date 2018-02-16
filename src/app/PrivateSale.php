<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateSale extends Model
{
    protected $fillable = ['user_id', 'batch_id','amount','price','weight','date','buyer_name'];

    public function batch()
    {
        return $this->belongsTo('App\Batch');
    }
	
	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	
	
}
