<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Product;
use App\User;
use Auth;

class CreateProductRequest extends Request
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
        if (file_exists("mifichero.txt")){
            echo "El fichero existe";
        }else{
            echo "El fichero no existe";
        }

        $prod = Product::withTrashed()
            ->where('name', $this->nombre)
            ->Where('unit', $this->unidad)
            ->first();

        if ($prod == null){
            return [
                'nombre' => 'required',
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
}
