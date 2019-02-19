<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateAuctionRequest extends Request
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
            'id'          => 'required',
            'fechaInicio'  => 'required|date_format:d/m/Y H:i|after:'.date('d/m/Y H:i'),
            'fechaFin'    => 'required|date_format:d/m/Y H:i|after:fechaInicio',
            'fechaTentativa'  => 'required|date_format:d/m/Y H:i|after:'.date('d/m/Y H:i'),
            'startPrice' => 'required|regex:/^\d{1,}(\,\d+)?$/|min:1|auction_price_greater_than:endPrice',
            'endPrice'   => 'required|regex:/^\d{1,}(\,\d+)?$/|min:1',
            'descri'   => 'required|min:120|max:1000',
            'amount'     => 'required|numeric|min:1',
            'intervalo'   => 'required|numeric|min:5'
        ];
    }
    public function messages()
    {
        return [
            "amount.min" => "La :attribute debe ser de al menos :min",
            "startPrice.min" => "El :attribute debe ser de al menos :min",
            "descri.min" => "El campo :attribute debe ser de al menos :min",
            "endPrice.min" => "El :attribute debe ser de al menos :min"
        ];
    }
    public function attributes()
    {
        return [
            "startPrice" => "precio inicial",
            "endPrice" => "precio final",
            "descri" => "descripción",
            "amount" => "cantidad"
        ];
    }
}
