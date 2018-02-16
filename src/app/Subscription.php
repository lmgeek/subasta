<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    const NO_NOTIFICADO = 0;
    const NOTIFICADO = 1;

    protected $table = 'subscriptions';
    protected $fillable = ['auction_id', 'user_id','status'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function auction()
    {
        return $this->belongsTo('App\Auction');
    }
}
