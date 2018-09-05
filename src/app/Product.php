<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const CAJAS = "cajas";
    const CAJONES = "cajones";
    const PASTILLAS = "pastillas";
    const UNIDADES = "unidad";

    const CHICO = "small";
    const MEDIANO = "medium";
    const GRANDE = "big";

    protected $table = 'products';

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'unit','weigth','image_name'];

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
