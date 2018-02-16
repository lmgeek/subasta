<?php

namespace App\Policies;

use App\Batch;
use App\Boat;

class BatchPolicy
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

    public function seeBatch($user)
    {
        return ($user->isSeller());
    }

    public function isMyBatch($user,Batch $batch)
    {
        $arrive = $batch->arrive;
        return policy($arrive)->isMyArrive($user, $arrive);
    }

    public function createAuction($user,Batch $batch)
    {
        return $this->isMyBatch($user,$batch);
    }

    public function deleteBatch($user,Batch $batch)
    {
        $hasActivity = ($batch->status->remainder != $batch->amount);

        return ($this->isMyBatch($user,$batch) and !$hasActivity);
    }

    public function makeDirectBid($user,Batch $batch)
    {
        return ($user->isSeller() and $this->isMyBatch($user,$batch));
    }
}
