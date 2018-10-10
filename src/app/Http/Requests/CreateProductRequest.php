<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use App\Http\Requests\Request;
use App\Product;
use App\User;
use Auth;

class CreateProductRequest extends Request
{
    public $locale;

    public function __construct()
    {
        $this->locale = App::getLocale();
    }
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
//        dd($this->weigth_small);
        $prod = Product::withTrashed()
            ->where('name', $this->nombre)
            ->Where('unit', $this->unidad)
            ->first();

$cero = "0,00";
        if ($prod == null){
            return [
                'nombre'        => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)',
                'unidad'        => 'required',
                'weigth_small'  => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:'.$cero,
                'weigth_medium' => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:'.$cero,
                'weigth_big'    => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:'.$cero,
                'imagen'        => 'required|image',
            ];
        } else{
            return [
//                'firstName' => "uniqueFirstAndLastName:{$request->lastName}"
                'nombre'        => 'required|unique_name_unit:'.$this->unidad,
                'unidad'        => 'required',
                'weigth_small'  => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:'.$cero,
                'weigth_medium' => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:'.$cero,
                'weigth_big'    => 'required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:'.$cero,
                'imagen'        => 'required|image',
            ];
        }
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
            'nombre.unique_name_unit'                 => 'La relación nombre unidad ya se encuentra registrada',
            'nombre.required'                         => 'El nombre es obligatorio',
            'nombre.regex'                            => 'El nombre sólo permite caracteres alfabéticos',
            'unidad.required'                         => 'La unidad es obligatoria',
            'weigth_small.required'                   => 'El peso de calibre chico es obligatorio',
            'weigth_big.required'                     => 'El peso de calibre grande es obligatorio',
            'weigth_medium.required'                  => 'El peso de calibre mediano es obligatorio',
            'weigth_small.greater_weight_than'        => 'El peso de calibre chico debe ser mayor a 0,00',
            'weigth_medium.greater_weight_than'       => 'El peso de calibre mediano debe ser mayor a 0,00',
            'weigth_big.greater_weight_than'          => 'El peso de calibre grande debe ser mayor a 0,00',
            'weigth_small.regex'                      => 'El peso de calibre chico sólo permite caracteres numéricos',
            'weigth_big.regex'                        => 'El peso de calibre grande sólo permite caracteres numéricos',
            'weigth_medium.regex'                     => 'El peso de calibre mediano sólo permite caracteres numéricos',
            'imagen.required'                         => 'La imagen es obligatoria',
            'imagen.image'                            => 'La imagen no es un formato válido',
            'imagen.unique'                           => 'La email ya ha sido registrada',
        ];
    }
}
