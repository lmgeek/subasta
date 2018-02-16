<?php

namespace App\Policies;

use App\User;

class UserPolicy
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

    public function canLogin(User $user)
    {
        return ($user->isAdmin() or !$user->isRejected());
    }

    public function seeUsersList(User $user)
    {
        return ($user->isAdmin());
    }

    public function seeUserDetail(User $user)
    {
        return ($user->isAdmin());
    }

    public function evaluateUser(User $user)
    {
        return ($user->isAdmin());
    }



}
