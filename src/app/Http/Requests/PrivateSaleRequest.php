<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PrivateSaleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'amount'=>'required|numeric',
//            'peso' => 'required|numeric',
            'importe' => 'required|min:0',
            'comprador' => 'required|regex:(^[a-zA-Zá-úÁ-Ú0-9\s]+$)'
        ];
    }
}
