<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
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
        return [
            'nombre' => 'required|unique:products,name,'.Request::get('id').',id',
            'unidad' => 'required',
            'imagen' => 'image',
        ];
    }
}
