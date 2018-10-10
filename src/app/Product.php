<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const CAJAS     = "Cajas";
    const CAJONES   = "Cajones";
    const PASTILLAS = "Pastillas";
    const UNIDADES  = "Unidades";

    const CHICO     = "small";
    const MEDIANO   = "medium";
    const GRANDE    = "big";

    protected $table = 'products';

    protected $dates    = ['deleted_at'];
    protected $fillable = ['name', 'unit', 'weigth_small', 'weigth_medium', 'weigth_big', 'image_name'];

    public static function units()
    {
        return [
            self::CAJAS,
            self::CAJONES,
            self::PASTILLAS,
            self::UNIDADES
        ];
    }

    public static function caliber()
    {
        return [
            self::CHICO,
            self::GRANDE,
            self::MEDIANO
        ];
    }

    public function canBeDeleted()
    {
        $b = Batch::where('product_id',$this->id)->count();
        return ($b == 0);
    }

    public function canBeDeactivate()
    {
        $now = date('Y-m-d H:i:s');

        $a = Auction::where('start_price','<=',$now)->where('end_price','>=',$now)->orwhere('start','>=',$now)->count();

        $consult = DB::table('batch_statuses')
            ->join('batches','batch_statuses.batch_id','=','batches.id')
            ->join('products', 'batches.product_id','=','products.id')
            ->where('batches.product_id','=',$this->id)
            ->select('products.id AS producto','batches.id AS batches','batches.amount','batch_statuses.assigned_auction','batch_statuses.auction_sold','batch_statuses.private_sold')
            ->get();
        $total = null;
        $total_amount = null;

        foreach ($consult as $c){
            $total = $c->auction_sold + $c->private_sold + $total;
            $total_amount = $total_amount + $c->amount;
        }

        return (($total == $total_amount));
    }
}
