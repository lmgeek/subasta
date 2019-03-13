<?php

namespace App\Http\Requests;

use App\Constants;
use App\Http\Requests\Request;
use App\Auction;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

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
            'fechaInicio'  => 'required|date_format:d-m-Y H:i|after:'.date('d-m-Y H:i'),
            'fechaTentativa'  => 'required|date_format:d-m-Y H:i|after:'.date('d-m-Y H:i'),
            'fechaFin'    => 'date_format:d/m/Y H:i|after:fechaInicio',
            'ActiveHours'=>'required|numeric|min:1',
            'startPrice' => 'required|regex:/^\d{1,}(\,\d+)?$/|min:2|auction_price_greater_than:endPrice,startPrice',
            'endPrice'   => 'required|regex:/^\d{1,}(\,\d+)?$/|min:1',
            'amount'     => 'required|numeric|min:1',
            'descri'   => 'required|min:120|max:1000',
            'barco'   => 'required|numeric|min:1',
            'puerto'=>'required|numeric|min:1',
            'product'=>'required|numeric|min:1',
            'quality'=>'required|numeric|min:1',
            
            //'tipoSubasta' => 'required',
			//'invitados'   => 'required_if:tipoSubasta,' . Constants::AUCTION_PRIVATE
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
                'ActiveHours.min'=>'La cantidad de horas activas debe ser al menos :min',
                'barco.numeric'=>'Debes escoger un barco',
                'barco.min'=>'Debes escoger un barco',
                'barco.required'=>'Debes escoger un barco',
                'puerto.numeric'=>'Debes escoger un puerto',
                'puerto.min'=>'Debes escoger un puerto',
                'puerto.required'=>'Debes escoger un puerto',
                'product.numeric'=>'Debes escoger un producto',
                'product.required'=>'Debes escoger un producto',
                'product.min'=>'Debes escoger un producto',
                'quality.required'=>'Debes escoger una calidad del lote',
                'quality.numeric'=>'Debes escoger una calidad del lote',
                'quality.min'=>'Debes escoger una calidad del lote',
            ];
        }
        return [];
    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                "startPrice" => "precio inicial",
                "endPrice" => "precio de retiro",
                "fechaTentativa" => "fecha tentativa",
                "descri" => "descripción",
                "amount" => "cantidad",
                'quality'=>'calidad',
                'product'=>'producto',
                'ActiveHours'=>'horas activas',
            ];
        }
        return [];
    }
}
