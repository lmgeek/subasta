<?php

namespace App\Widgets;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class AveragePrices
{

    public static function run()
    {
        $prices = self::getQueryAveracePrices();
        return view('widgets.averagePrices',compact('prices'));
    }

    private static function getQueryAveracePrices()
    {
        return DB::select("
            select p.name, sum(b.price)/count(b.amount) average from bids b
            inner join auctions a on b.auction_id = a.id
            inner join batches ba on a.batch_id = ba.id
            inner join products p on ba.product_id = p.id
            group by b.auction_id
        ");
    }
}
