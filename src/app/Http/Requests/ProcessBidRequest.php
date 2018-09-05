<?php

namespace App\Http\Requests;

use App\Bid;
use App\Http\Requests\Request;

class ProcessBidRequest extends Request
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
            'concretada' => 'required',
            'calificacion' => 'required',
            'motivo_no_concretada' => 'required_if:concretada,'.Bid::NO_CONCRETADA,
            'comentariosCalificacion' => 'required_if:calificacion,'.Bid::CALIFICACION_NEGATIVA.'|required_if:calificacion,'.Bid::CALIFICACION_NEUTRAL
        ];
    }
    public function messages()
    {
            return [
                "motivo_no_concretada.required_if" => "El campo :attribute es obligatorio cuando la operaci&oacute;n no fue no concretada"
            ];
    }
    public function attributes()
    {
            return [
                "calificacion" => "calificaci&oacute;n"
            ];
    }
}
