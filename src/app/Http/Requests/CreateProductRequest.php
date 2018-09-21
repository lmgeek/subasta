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
        $prod = Product::withTrashed()
            ->where('name', $this->nombre)
            ->Where('unit', $this->unidad)
            ->first();

        if ($prod == null){
            return [
                'nombre' => 'required|regex:(^[a-zA-Zá-úÁ-Ú\s]+$)',
                'unidad' => 'required',
                'weigth' => 'required',
                'imagen' => 'required|image',
            ];
        } else{
            return [
                'nombre' => 'required|unique:products,name,'.$this->nombre,
                'unidad' => 'required|unique:products,unit,'.$this->nombre,
                'weigth' => 'required',
                'imagen' => 'required|image',
            ];
        }
    }

    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                "weigth" => "peso"
            ];
        }
        return [];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre sólo permite caracteres alfabéticos',
            'unidad.required' => 'La unidad es obligatoria',
            'weigth.required' => 'El peso es obligatorio',
            'weigth.min' => 'Debe tener un peso mayor a 0',
            'weigth.regex' => 'El peso sólo permite caracteres numéricos',
            'imagen.required' => 'La imagen es obligatoria',
            'imagen.image' => 'La imagen no es un formato válido',
            'imagen.unique' => 'La email ya ha sido registrada',
        ];
    }
}
