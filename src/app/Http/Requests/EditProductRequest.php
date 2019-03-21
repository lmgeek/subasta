<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Product;
use App\User;
use Auth;
use App\Constants;
use Illuminate\Support\Facades\Input;

class EditProductRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::user()->type === User::INTERNAL);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cero = "0,00";
        return [
                Constants::CODIGO        => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\-\s]+$)|max:10',
                Constants::NOMBRE        => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)',
                'unidadp'       => Constants::REQUIRED,
                'unidadm'       => Constants::REQUIRED,
                'unidadg'       => Constants::REQUIRED,
                Constants::WEIGHT_SMALL  => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                Constants::WEIGHT_MEDIUM => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                Constants::WEIGHT_BIG    => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                'salep'        => Constants::REQUIRED,
                'salem'        => Constants::REQUIRED,
                'saleg'        => Constants::REQUIRED,
                Constants::IMAGEN        => Constants::IMAGE,
            ];
    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                "weigth_small"  => "peso por calibre chico",
                "weigth_medium" => "peso por calibre mediano",
                "weigth_bis"    => "peso por calibre grande",
            ];
        }
        return [];
    }
    public function messages()
    {
        return [
            'codigo.regex'                         => 'El código pesquero es alfanumerico y "-" maximo 10 caracteres',
            'codigo.unique'                         => 'El código pesquero ya existe',
            'nombre.unique_name_unit'                 => 'La relación nombre unidad ya se encuentra registrada',
            'nombre.required'                         => 'El nombre es obligatorio',
            'nombre.regex'                            => 'El nombre sólo permite caracteres alfabéticos',
            'salep.required'                         => 'La unidad de venta chica es obligatoria',
            'salem.required'                         => 'La unidad de venta mediana es obligatoria',
            'saleg.required'                         => 'La unidad de venta grande es obligatoria',
            'unidadp.required'                         => 'La unidad de presentación chica es obligatoria',
            'unidadm.required'                         => 'La unidad de presentación mediana es obligatoria',
            'unidadg.required'                         => 'La unidad de presentación grande es obligatoria',
            'weigth_small.required'                   => 'El peso de calibre chico es obligatorio y mayor a 0,00',
            'weigth_big.required'                     => 'El peso de calibre grande es obligatorio y mayor a 0,00',
            'weigth_medium.required'                  => 'El peso de calibre mediano es obligatorio y mayor a 0,00',
            'weigth_small.regex'                      => 'El peso de calibre chico sólo permite caracteres numéricos',
            'weigth_big.regex'                        => 'El peso de calibre grande sólo permite caracteres numéricos',
            'weigth_medium.regex'                     => 'El peso de calibre mediano sólo permite caracteres numéricos',
            'imagen.image'                            => 'La imagen no es un formato válido',
            'weigth_small.greater_weight_than'        => 'El peso de calibre chico debe ser mayor a 0,00',
            'weigth_medium.greater_weight_than'       => 'El peso de calibre mediano debe ser mayor a 0,00',
            'weigth_big.greater_weight_than'          => 'El peso de calibre grande debe ser mayor a 0,00',
        ];
    }
}
