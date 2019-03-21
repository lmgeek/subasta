<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\App;
use App\Http\Requests\Request;
use App\Product;
use App\User;
use Auth;
use App\Constants;

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
            $cero = "0,00";
            return [

                Constants::CODIGO        => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\-\s]+$)|max:10|unique:products,fishing_code',
                Constants::NOMBRE        => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)|unique:products,name',
                'unidadp'       => Constants::REQUIRED,
                'unidadm'       => Constants::REQUIRED,
                'unidadg'       => Constants::REQUIRED,
                Constants::WEIGHT_SMALL  => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                Constants::WEIGHT_MEDIUM => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                Constants::WEIGHT_BIG    => Constants::VALIDATION_RULES_PRODUCT_WEIGHT.$cero,
                'salep'         => Constants::REQUIRED,
                'salem'         => Constants::REQUIRED,
                'saleg'         => Constants::REQUIRED,
                'statusp'       => Constants::REQUIRED,
                'statusm'       => Constants::REQUIRED,
                'statusg'       => Constants::REQUIRED,
                Constants::IMAGEN        => 'required|image',
            ];

    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                "codigo"  => "código pesquero",
                "salep"  => "unidad de venta chico",
                "saleg"  => "unidad de venta grande",
                "salem"  => "unidad de venta mediana",
                "unidadp"  => "unidad de presentación chica",
                "unidadm"  => "unidad de presentación mediana",
                "unidadg"  => "unidad de presentación grande",
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
            'unidad.required'                         => 'La unidad es obligatoria',
            'weight_small.required'                   => 'El peso de calibre chico es obligatorio',
            'weight_big.required'                     => 'El peso de calibre grande es obligatorio',
            'weight_medium.required'                  => 'El peso de calibre mediano es obligatorio',
            'weight_small.greater_weight_than'        => 'El peso de calibre chico debe ser mayor a 0,00',
            'weight_medium.greater_weight_than'       => 'El peso de calibre mediano debe ser mayor a 0,00',
            'weight_big.greater_weight_than'          => 'El peso de calibre grande debe ser mayor a 0,00',
            'weight_small.regex'                      => 'El peso de calibre chico sólo permite caracteres numéricos',
            'weight_big.regex'                        => 'El peso de calibre grande sólo permite caracteres numéricos',
            'weight_medium.regex'                     => 'El peso de calibre mediano sólo permite caracteres numéricos',
            'imagen.required'                         => 'La imagen es obligatoria',
            'imagen.image'                            => 'La imagen no es un formato válido',
            'imagen.unique'                           => 'La email ya ha sido registrada',
        ];
    }
}
