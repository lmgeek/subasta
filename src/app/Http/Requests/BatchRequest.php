<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;

class BatchRequest extends Request
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
        $arrive_id = $this->route('arrive');
        $boat_id = $this->route('boat');

        return [
//            'arrive' => 'required|exists:arrives,id,boat_id,'.$boat_id,
            'boat' => 'required',
        ];
    }


}
