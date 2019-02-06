<?php

namespace App\Http\Requests;

use App\Bid;
use App\Http\Requests\Request;
use App\Constants;

class SellerQualifyRequest extends Request
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
            'calificacion' => 'required',
            'comentariosCalificacion' => 'required_if:calificacion,'.Constants::CALIFICACION_NEGATIVA.'|required_if:calificacion,'.Constants::CALIFICACION_NEUTRAL
        ];
    }
}
