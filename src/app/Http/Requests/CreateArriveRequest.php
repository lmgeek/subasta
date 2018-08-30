<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;

class CreateArriveRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::user()->type === User::VENDEDOR);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'barco' => 'required|exists:boats,id,user_id,'.Auth::user()->id,
            'date' => 'required|date|date_format:Y-m-d H:i:s|after:'.date('Y-m-d H:i:s')
        ];
    }

    public function attributes()
    {
        return [
          'barco' => 'El barco',
          'date' => 'La fecha de arribo'
        ];
    }
}
