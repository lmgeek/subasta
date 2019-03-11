<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;



    protected $table = 'products';

    protected $dates    = ['deleted_at'];
    protected $fillable = ['name', 'unit', 'sale_unit', 'weigth_small', 'weigth_medium', 'weigth_big', 'image_name', 'fishing_code'];

    public static function units()
    {
        return [
            Constants::CAJAS,
            Constants::CAJONES,
            Constants::PASTILLAS,
            Constants::UNIDADES
        ];
    }
    public static function sale()
    {
        return [
            Constants::KG,
            Constants::CAJONES,
            Constants::UNIDADES
        ];
    }

    public static function caliber()
    {
        return [
            Constants::CHICO,
            Constants::MEDIANO,
            Constants::GRANDE,
            
        ];
    }

    public function canBeDeleted()
    {
        $b = Batch::where('product_id',$this->id)->count();
        return ($b == 0);
    }

    public function canBeDeactivate()
    {

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

        return ($total == $total_amount);
    }
    /* INI Rodolfo*/
    public static function getProductFromId($id){
        return self::select()->where('products.id','=',$id)->get()[0]['name'];
    }
    /* FIN Rodolfo*/
}
