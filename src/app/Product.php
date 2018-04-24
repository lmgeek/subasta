<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const CAJONES = "boxes";
    const UNIDADES = "unity";

    const CHICO = "small";
    const MEDIANO = "medium";
    const GRANDE = "big";

    protected $table = 'products';

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'unit','image_name'];

    public static function units()
    {
        return [
            self::CAJONES,
            self::UNIDADES
        ];
    }

    public static function caliber()
    {
        return [
            self::CHICO,
            self::MEDIANO,
            self::GRANDE
        ];
    }

    public function canBeDeleted()
    {
        $b = Batch::where('product_id',$this->id)->count();
        return ($b == 0);
    }
}
