<?php

namespace App\Providers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('auction_price_greater_than', function($attribute, $value, $parameters) {
            $request = Request::capture();
            $parameterValue = $request->input($parameters[0]);
            return intval($value) > intval($parameterValue);
        });
        Validator::extend('batch_is_mine', function($attribute, $value, $parameters) {
            $authorize = DB::table('batches')
                ->join('arrives','batches.arrive_id','=','arrives.id')
                ->join('boats','arrives.boat_id','=','boats.id')
                ->where('batches.id',$value)
                ->where('boats.user_id',Auth::user()->id)->count();

            return $authorize;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
