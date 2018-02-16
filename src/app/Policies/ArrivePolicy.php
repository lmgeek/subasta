<?php

namespace App\Policies;

use App\Arrive;

class ArrivePolicy
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

    public function isMyArrive($user,Arrive $arrive)
    {
        $boat = $arrive->boat;
        return policy($boat)->isMyBoat($user, $boat);
    }

    public function editArrive($user, Arrive $arrive)
    {
        return $this->isMyArrive($user,$arrive);
    }


}
