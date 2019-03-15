<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ProductDetail;

class Product extends Model
{
    use SoftDeletes;



    protected $table = 'products';

    protected $dates    = ['deleted_at'];
    protected $fillable = ['name', 'image_name', 'fishing_code'];


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
    public static function status()
    {
        return [
            'Activado',
            'Desactivado'
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


    public static function getProductFromId($id){
        return self::select()->where('products.id','=',$id)->get()[0]['name'];
    }
    public static function getProductInfoFromProductDetailId($productdetailid){
        $productdetails= ProductDetail::select()->where('id',Constants::EQUAL,$productdetailid)->get()[0];
        $productinfo=Product::select()->where('id',Constants::EQUAL,$productdetails->product_id)->get()[0];
        return array(
            'idproduct'=>$productinfo->id,
            'caliber'=>$productdetails->caliber,
            'presentation_unit'=>$productdetails->presentation_unit,
            'sale_unit'=>$productdetails->sale_unit,
            'weight'=>$productdetails->weight,
            'name'=>$productinfo->name,
            'fishing_code'=>$productinfo->fishing_code,
            'image'=>$productinfo->image_name
        );
    }

    public function detalle(){
        return $this->HasMany('App\ProductDetail','product_id','id');
    }
}
