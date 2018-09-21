<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;

class CreateBatchRequest extends Request
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
            'product' => 'required',
            'caliber' => 'required',
        ];
    }


}
