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
            'batch'      => 'required|batch_is_mine',
            'fechaInicio'  => 'required|date_format:d/m/Y H:i|after:'.date('d/m/Y H:i'),
            'fechaFin'    => 'required|date_format:d/m/Y H:i|after:fechaInicio',
            'startPrice' => 'required|regex:/^\d{1,}(\,\d+)?$/|min:1|auction_price_greater_than:endPrice',
            'endPrice'   => 'required|regex:/^\d{1,}(\,\d+)?$/|min:1',
            'amount'     => 'required|numeric|min:1',
            'descri'   => 'required|min:120|max:1000',
            'intervalo'   => 'required|numeric|min:5',
			'invitados'   => 'required_if:tipoSubasta,' . Constants::AUCTION_PRIVATE
        ];
    }
    public function messages()
    {
        if ($this->locale == "es"){
            return [
                "invitados.required_if" => "El campo :attribute es obligatorio cuando :other es privada",
                "descri.min" => "El campo :attribute debe ser de al menos :min carateres",
                "amount.min" => "La :attribute debe ser de al menos :min"
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
                "descri" => "descripción",
                "amount" => "cantidad"
            ];
        }
        return [];
    }


}
