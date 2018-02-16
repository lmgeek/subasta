<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;

class CreateBoatRequest extends Request
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
            'name' => 'required|unique:boats,name,NULL,id,user_id,'.Auth::user()->id,
            'matricula' => 'required|unique:boats,matricula,NULL,id,user_id,'.Auth::user()->id
        ];
    }
}
