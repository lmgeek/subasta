<?php

namespace App\Widgets;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AuctionProducts
{

    public static function run()
    {
        $products = self::getQuerySubastasPorProducto();
        return view('widgets.AuctionProducts',compact('products'));
    }

    private static function getQuerySubastasPorProducto()
    {
        $now =date("Y-m-d H:i:s");
        return DB::select("select b.product_id,p.name, count(*) cantidad from auctions a
                    inner join batches b on a.batch_id = b.id
                    inner join products p on b.product_id = p.id
                    where a.start <= '".$now."'
                    and a.end >= '".$now."'
                    group by b.product_id");
    }
}
