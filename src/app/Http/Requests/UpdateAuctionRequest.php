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
            'startPrice' => 'required|numeric|min:1|auction_price_greater_than:endPrice',
            'endPrice'   => 'required|numeric|min:1',
            'amount'     => 'required|numeric|min:1',
            'intervalo'   => 'required|numeric|min:1'
        ];
    }
}
