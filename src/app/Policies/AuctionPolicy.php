<?php

namespace App\Policies;

use App\Auction;
use App\Bid;

class AuctionPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function seeSellerAuction($user)
    {
        return ($user->isSeller());
    }

    public function isMyAuction($user,Auction $auction)
    {
        $batch = $auction->batch;
        return policy($batch)->isMyBatch($user, $batch);
    }

    public function viewOperations($user,Auction $auction)
    {
        return $this->isMyAuction($user,$auction);
    }

    public function seeAuctions($user)
    {
        return ($user->isBuyer());
    }

    public function makeBid($user)
    {
        return ($user->isBuyer() );
    }
	
	public function canBid($user)
	{
		$bid = new Bid();
		$numPendingBids = $bid->getTotalPendienteByUser($user); 

        if($user->isBuyer()){
            return ( $numPendingBids < $user->buyer->bid_limit   );
        }else{
            return true;
        }
	}

    public function editAuction($user,$auction)
    {
        return $this->isMyAuction($user,$auction);
    }
	
	public function isInvited($user, $auction)
	{
			$invites = $auction->auctionInvited;
			$rtn = false;
			foreach ($invites as $i)
			{
				if ( $i->user_id == $user->id )
				{
					$rtn = true;	
					break;
				}
			}
	
			return $rtn;
	}

    public function exportAuction($user,$auction)
    {
        return $this->isMyAuction($user,$auction);
    }


}
