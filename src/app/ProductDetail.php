<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    use SoftDeletes;
    protected $table = 'product_detail';

    protected $dates    = ['deleted_at'];
    protected $fillable = ['product_id', 'caliber', 'presentation_unit','weight', 'sale_unit','status'];


    public function product(){
        return $this->HasMany('App\Product','id','product_id')->withTrashed();
    }
    public function batch(){
        return $this->hasMany('App\Batch');
    }

}
