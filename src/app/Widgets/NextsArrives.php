<?php

namespace App\Widgets;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class NextsArrives
{

    public static function run()
    {
        $arrives = self::getQueryProximosArribos();
        return view('widgets.nextArrives',compact('arrives'));
    }

    private static function getQueryProximosArribos()
    {
        $now = date("Y-m-d H:i:s");
        return DB::select("
            select p.name product,sum(bids.amount) cantidad,p.unit ,bo.name boat ,ar.date from bids
            inner join auctions a on a.id = bids.auction_id
            inner join batches b on b.arrive_id = a.batch_id
            inner join arrives ar on ar.id = b.arrive_id
            inner join boats bo on bo.id = ar.boat_id
            inner join products p on p.id = b.product_id
            WHERE bids.user_id = 2
            and arrive_id in
            (SELECT id
             FROM arrives
             WHERE id IN
                   (SELECT DISTINCT (arrive_id)
                    FROM batches
                    WHERE id IN
                          (SELECT DISTINCT (batch_id)
                           FROM auctions
                           WHERE id IN
                                 (SELECT DISTINCT (auction_id)
                                  FROM bids
                                  WHERE user_id = ". Auth::user()->id. ")
                          )
                   )
              and arrives.date < '".$now."'
            )
            GROUP BY bids.auction_id
        ");
    }
}
