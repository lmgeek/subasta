<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\App;
class UpdateArriveRequest extends Request
{
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
            'date' => 'required|date_format:Y-m-d H:i:s|after:' . date('Y-m-d H:i:s'),
        ];
    }

    public function attributes()
    {
        if ($this->locale == "es") {
            return [
                'date' => 'Fecha de Arribo'
            ];
        }
    }
}
