<?php

namespace App\Widgets;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class BuyResumen
{

    public static function run()
    {
        $resumen = self::getQueryResumen();
        return view('widgets.buyResumen',compact('resumen'));
    }

    private static function getQueryResumen()
    {
        return DB::select("
            SELECT
              p.name,
              aux.amount,
              p.unit,
              aux.total
            FROM bids b
              INNER JOIN auctions a ON b.auction_id = a.id
              INNER JOIN batches ba ON a.batch_id = ba.id
              INNER JOIN products p ON ba.product_id = p.id
              INNER JOIN
              (SELECT
                 id,
                 auction_id,
                 amount,
                 (amount * price) total
               FROM bids
               WHERE user_id = ".Auth::user()->id."
              ) aux ON aux.id = b.id
              order by b.bid_date desc
        ");
    }
}
