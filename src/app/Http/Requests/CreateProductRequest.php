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
//        dd($this->unidad);
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
}
