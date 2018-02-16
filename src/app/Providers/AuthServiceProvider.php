<?php

namespace App\Providers;

use App\Arrive;
use App\Auction;
use App\Batch;
use App\Bid;
use App\Boat;
use App\Policies\ArrivePolicy;
use App\Policies\AuctionPolicy;
use App\Policies\BatchPolicy;
use App\Policies\BidPolicy;
use App\Policies\BoatPolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Boat::class => BoatPolicy::class,
        Arrive::class => ArrivePolicy::class,
        Auction::class => AuctionPolicy::class,
        Batch::class => BatchPolicy::class,
        Bid::class => BidPolicy::class
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $gate->define('seeSellerBoats','App\Policies\BoatPolicy@seeSellerBoats');
        $gate->define('seeAllBoatsList','App\Policies\BoatPolicy@seeAllBoatsList');
        $gate->define('viewSellerAuction','App\Policies\AuctionPolicy@viewSellerAuction');
        parent::registerPolicies($gate);
    }
}
