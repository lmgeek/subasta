<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionInvited extends Model
{
    protected $table = 'auctions_invites';
	
	protected $fillable = ['auction_id', 'user_id'];
	
	
	
	
}
