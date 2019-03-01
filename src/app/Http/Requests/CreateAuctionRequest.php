<?php

namespace App\Http\Requests;

use App\Constants;
use App\Http\Requests\Request;
use App\Auction;
use Illuminate\Support\Facades\App;

class CreateAuctionRequest extends Request
{
    public $locale;

    public function __construct()
    {
        $this->locale = App::getLocale();
    }
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
            'tipoSubasta' => 'required',
            'fechaInicio'  => 'required|date_format:d/m/Y H:i|after:'.date('d/m/Y H:i'),
            'fechaTentativa'  => 'required|date_format:d/m/Y H:i|after:'.date('d/m/Y H:i'),
            'fechaFin'    => 'date_format:d/m/Y H:i|after:fechaInicio',
            'ActiveHours'=>'required|numeric|min:1',
            'startPrice' => 'required|regex:/^\d{1,}(\,\d+)?$/|min:2|auction_price_greater_than:endPrice,startPrice',
            'endPrice'   => 'required|regex:/^\d{1,}(\,\d+)?$/|min:1',
            'amount'     => 'required|numeric|min:1',
            'descri'   => 'required|min:120|max:1000',
            'intervalo'   => 'numeric',
			'invitados'   => 'required_if:tipoSubasta,' . Constants::AUCTION_PRIVATE
        ];
    }
    public function messages()
    {
        if ($this->locale == "es"){
            return [
                "invitados.required_if" => "El campo :attribute es obligatorio cuando :other es privada",
                "descri.min" => "El campo :attribute debe ser de al menos :min carateres",
                "amount.min" => "La :attribute debe ser de al menos :min",
                'ActiveHours.required'=>'El campo horas activas es obligatorio',
                'ActiveHours.min'=>'La cantidad de horas activas debe ser al menos :min'
            ];
        }
        return [];
    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                "startPrice" => "precio inicial",
                "endPrice" => "precio final",
                "fechaTentativa" => "fecha tentativa",
                "descri" => "descripción",
                "amount" => "cantidad"
            ];
        }
        return [];
    }
}
