<?php

namespace App\Policies;

use App\Bid;

class BidPolicy
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

    public function isMyBid($user,Bid $bid)
    {
        $auction = $bid->auction;
        return policy($auction)->isMyAuction($user, $auction);
    }

    public function qualifyBid($user,Bid $bid)
    {
        return $this->isMyBid($user,$bid);
    }

    public function seeMyBids($user)
    {
        return $user->isBuyer();
    }

}
