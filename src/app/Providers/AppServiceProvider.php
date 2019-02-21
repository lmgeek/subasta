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
        Validator::extend('batch_is_mine', function($value) {
            return DB::table('batches')
                ->join('arrives','batches.arrive_id','=','arrives.id')
                ->join('boats','arrives.boat_id','=','boats.id')
                ->where('batches.id',$value)
                ->where('boats.user_id',Auth::user()->id)->count();
        });
        Validator::extend('unique_name_unit', function ($value, $parameters) {
            $count = DB::table('products')
                ->where('name', $value)
                ->where('unit', $parameters[0])
                ->count();
            return $count === 0;
        });
        Validator::extend('greater_weight_than', function($value) {
            return $value > "0,00";
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
