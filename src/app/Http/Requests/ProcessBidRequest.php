<?php

namespace App\Http\Requests;

use App\Bid;
use App\Constants;
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
            Constants::INPUT_CALIFICACION => 'required',
            'motivo_no_concretada' => 'required_if:concretada,'.Constants::NO_CONCRETADA,
            Constants::INPUT_COMENTARIOS_CALIFICACION => 'required_if:calificacion,'.Constants::CALIFICACION_NEGATIVA.'|required_if:calificacion,'.Constants::CALIFICACION_NEUTRAL
        ];
    }
    public function messages()
    {
        return [
            "motivo_no_concretada.required_if" => "El campo :attribute es obligatorio cuando la operaci&oacute;n no fue concretada",
            "comentariosCalificacion.required_if" => "El campo :attribute es obligatorio cuando :other es ".trans("validation.".$this->get(Constants::INPUT_CALIFICACION))
        ];
    }
    public function attributes()
    {
        return [
            Constants::INPUT_CALIFICACION => "calificaci&oacute;n",
            Constants::INPUT_COMENTARIOS_CALIFICACION => "comentario",
            "motivo_no_concretada" => "motivo"
        ];
    }
}
