<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\priceTrait;
use Carbon\Carbon;
use Auth;

class Offers extends Model
{
    use priceTrait;
	protected $table = 'auctions_offers';
	
	protected $fillable = ['id', 'auction_id','user_id','amount','price','status'];

	const NO_ACEPTADA = 'rejected';
	const ACEPTADA = 'accepted';
	const PENDIENTE = 'pending';


	public function auction(){
        return $this->belongsTo('App\Auction');
    }

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
