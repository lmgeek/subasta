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
    protected $fillable = ['name', 'image_name', 'fishing_code','status'];


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
