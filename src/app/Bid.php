<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\priceTrait;

class Bid extends Model
{
    use priceTrait;
	protected $table = 'bids';
	
	protected $fillable = ['user_id', 'auction_id','amount','price','bid_date','status','reason','user_calification','user_calification_comments','weight','buyer_name'];

	const NO_CONCRETADA = 'noConcretized';
	const CONCRETADA = 'concretized';
	const PENDIENTE = 'pending';

	const CALIFICACION_POSITIVA = 'positive';
	const CALIFICACION_NEUTRAL = 'neutral';
	const CALIFICACION_NEGATIVA = 'negative';

	public function auction(){
        return $this->belongsTo('App\Auction');
    }

	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function getTotalByUser($user)
	{
		return Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->where('status',Bid::CONCRETADA)->sum('price');
	}
	
	public function getTotalPendienteByUser($user)
	{
		return Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->where('status',Bid::PENDIENTE)->count('id');
	}
	
}
