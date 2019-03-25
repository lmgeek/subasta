<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\App;

use App\Http\Requests\Request;
use App\Product;
use App\User;
use Auth;
use App\Constants;
use Illuminate\Support\Facades\Input;

class EditProductRequest extends Request
{
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
        $cero = "0,00";
        return [
                Constants::CODIGO        => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\-\s]+$)|max:10',
                Constants::NAME        => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)',
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
                "codigo"  => "Código Pesquero",
                "name"  => "Nombre",
                "imagen" => "Imagen",
                "salep"  => "Unidad de Venta del Calibre Chico",
                "saleg"  => "Unidad de Venta del Calibre Grande",
                "salem"  => "Unidad de Venta del Calibre Mediana",
                "unidadp"  => "Unidad de Presentación del Calibre Chico",
                "unidadm"  => "Unidad de Presentación del Calibre Mediano",
                "unidadg"  => "Unidad de Presentación del Calibre Grande",
                "weight_small"  => "Peso por Calibre Chico",
                "weight_medium" => "Peso por Calibre Mediano",
                "weight_big"    => "Peso por Calibre Grande",
            ];
        }
        return [];
    }
    public function messages()
    {
        return [
            'codigo.regex'                            => 'El :attribute es alfanumerico y "-" maximo 10 caracteres',
            'name.regex'                            => 'El :attribute sólo permite caracteres alfabéticos',
            'weight_small.greater_weight_than'        => 'El :attribute debe ser mayor a 0,00',
            'weight_medium.greater_weight_than'       => 'El :attribute debe ser mayor a 0,00',
            'weight_big.greater_weight_than'          => 'El :attribute debe ser mayor a 0,00',
            'weight_small.regex'                      => 'El :attribute sólo permite caracteres numéricos',
            'weight_big.regex'                        => 'El :attribute sólo permite caracteres numéricos',
            'weight_medium.regex'                     => 'El :attribute sólo permite caracteres numéricos',
            'imagen.image'                            => 'La :attribute no es un formato válido',
        ];
    }
}
