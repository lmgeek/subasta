<?php

namespace App\Policies;

use App\Boat;

class BoatPolicy
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

    public function seeAllBoatsList($user)
    {
        return ($user->isAdmin());
    }

    public function seeBoatDetail($user)
    {
        return ($user->isAdmin());
    }

    public function evaluateBoat($user)
    {
        return ($user->isAdmin());
    }

    //******************************************************************************************************************
    //                                                  SELLER SECTION
    //******************************************************************************************************************
    public function seeSellerBoats($user)
    {
        return ($user->isSeller());
    }

    public function isMyBoat($user,Boat $boat)
    {
        return ($boat->user->id == $user->id);
    }

    public function isAproved($user,Boat $boat)
    {
        return ($boat->status == Boat::APROBADO);
    }

    public function addBoat($user)
    {
        return ($user->isSeller());
    }

    public function addBoatArrive($user,Boat $boat)
    {
        return ($this->isMyBoat($user,$boat) && $this->isAproved($user,$boat));
    }

    public function createBatch($user,Boat $boat)
    {
        return ($this->isMyBoat($user,$boat) && $this->isAproved($user,$boat));
    }
}
