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
        $prod = Product::withTrashed()
            ->where('name', Request::get(Constants::NOMBRE))
            ->Where('unit', Request::get(Constants::UNIDAD))
            ->first();
        $product_id = Request::get('id');
        $cero = "0,00";
        if ($prod == null){
            return [
                'codigo'=> 'required|regex:(^([a-z]+[0-9]{0,2}){5,12}$)|max:10 ',
                Constants::NOMBRE        => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)',
                Constants::UNIDAD        => Constants::REQUIRED,
                Constants::WEIGHT_SMALL  => Constants::REQUIRED,
                Constants::WEIGHT_MEDIUM => Constants::REQUIRED,
                Constants::WEIGHT_BIG    => Constants::REQUIRED,
                Constants::IMAGEN        => Constants::IMAGE,
            ];
        } else{
           if ($product_id != $prod->id) {

                return [
                    Constants::NOMBRE        => 'required|unique_name_unit:'.$this->unidad,
                    Constants::UNIDAD        => Constants::REQUIRED,
                    Constants::WEIGHT_SMALL  => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                    Constants::WEIGHT_MEDIUM => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                    Constants::WEIGHT_BIG    => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                    Constants::IMAGEN        => Constants::IMAGE,
                ];
            } else {
                return [
                    Constants::NOMBRE        => Constants::REQUIRED,
                    Constants::UNIDAD        => Constants::REQUIRED,
                    Constants::WEIGHT_SMALL  => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                    Constants::WEIGHT_MEDIUM => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                    Constants::WEIGHT_BIG    => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                    Constants::IMAGEN        => Constants::IMAGE,
                ];

            }
        }

    }
    public function messages()
    {
        return [
            'codigo.regex'                         => 'El código pesquero es alfanumerico maximo 10 caracteres',
            'nombre.unique_name_unit'                 => 'La relación nombre unidad ya se encuentra registrada',
            'nombre.required'                         => 'El nombre es obligatorio',
            'nombre.regex'                            => 'El nombre sólo permite caracteres alfabéticos',
            'unidad.required'                         => 'La unidad es obligatoria',
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
