<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Product;
use App\User;
use Auth;
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
            ->where('name', Request::get('nombre'))
            ->Where('unit', Request::get('unidad'))
            ->first();
        $product_id = Request::get('id');

        if ($prod == null){
            return [
                'nombre'        => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)',
                'unidad'        => 'required',
                'weigth_small'  => 'required',
                'weigth_medium' => 'required',
                'weigth_big'    => 'required',
                'imagen'        => 'image',
            ];
        } else{
           if ($product_id != $prod->id) {

                return [
                    'nombre'        => 'required|unique_name_unit:'.$this->unidad,
                    'unidad'        => 'required',
                    'weigth_small'  => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than',
                    'weigth_medium' => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than',
                    'weigth_big'    => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than',
                    'imagen'        => 'image',
                ];
            } else {
                return [
                    'nombre'        => 'required',
                    'unidad'        => 'required',
                    'weigth_small'  => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than',
                    'weigth_medium' => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than',
                    'weigth_big'    => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than',
                    'imagen'        => 'image',
                ];

            }
        }

    }
    public function messages()
    {
        return [
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
