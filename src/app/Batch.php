<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Batch extends Model
{
    protected $table = 'batches';

    protected $fillable = ['arrive_id', 'product_detail_id','quality','amount'];

    public function status(){
        return $this->hasOne('App\BatchStatus');
    }

 
	public function arrive(){
        return $this->belongsTo('App\Arrive');
    }
	
	public function detail(){
        return $this->belongsTo('App\ProductDetail')->withTrashed();
    }

    public function auction(){
        return $this->hasMany('App\Auction');
    }

    public function privateSale(){
        return $this->hasMany('App\PrivateSale');
    }

    public function assignForAuction($amount)
    {
        $this->status->assigned_auction += $amount;
        $this->status->remainder -= $amount;
        $this->status->save();
    }

    public function makePrivateSale($amount , $price, $buyer,$weight = "")
    {
        $this->privateSale = new PrivateSale();
        $this->privateSale->user_id = Auth::user()->id;
        $this->privateSale->batch_id = $this->id;
        $this->privateSale->amount = $amount;
        $this->privateSale->price = $price;
        $this->privateSale->weight = $weight;
        $this->privateSale->buyer_name = $buyer;
        $this->privateSale->date = date(Constants::DATE_FORMAT);
        $this->privateSale->save();

        $this->status->remainder -= $amount;
        $this->status->private_sold += $amount;
        $this->status->save();
    }
}
