<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\priceTrait;
use App\Constants;

class Bid extends Model
{
    use priceTrait;
	protected $table = 'bids';
	
	protected $fillable = ['user_id', 'auction_id','amount','price','bid_date','status','reason','user_calification','user_calification_comments','weight','buyer_name','bid_origin'];



	public function auction(){
        return $this->belongsTo('App\Auction');
    }

	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function getTotalByUser($user)
	{
		return Bid::where(Constants::BIDS_USER_ID, $user->id )->orderBy(Constants::BIDS_DATE, Constants::DESC)->where(Constants::STATUS,Constants::CONCRETADA)->sum('price');
	}
	
	public function getTotalPendienteByUser($user)
	{
		return Bid::where(Constants::BIDS_USER_ID, $user->id )->orderBy(Constants::BIDS_DATE, Constants::DESC)->where(Constants::STATUS,Constants::PENDIENTE)->count('id');
	}
	
}
