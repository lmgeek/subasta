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
                'nombre' => 'required',
                'unidad' => 'required',
                'weigth' => 'required',
                'imagen' => 'image',
            ];
        } else{
           if ($product_id != $prod->id) {
                return [
                    'nombre' => 'required|unique:products,name,'.Request::get('nombre'),
                    'unidad' => 'required|unique:products,unit,'.Request::get('unidad'),
                    'weigth' => 'required',
                    'imagen' => 'image',
                ];
            } else {
                return [
                    'nombre' => 'required',
                    'unidad' => 'required',
                    'weigth' => 'required',
                    'imagen' => 'image',
                ];
            }
        }
//        return [
//            'nombre' => 'required|unique:products,name,'.Request::get('id').',id',
//            'unidad' => 'required',
//            'imagen' => 'image',
//        ];
    }
}
