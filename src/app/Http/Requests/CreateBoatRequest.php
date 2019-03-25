<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\App;

class CreateBoatRequest extends Request
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
        return (Auth::user()->type === User::VENDEDOR);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\s\#\-]+$)',
//            'alias'      => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\s\#\-]+$)',
            'matricula' => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\-]+$)|unique:boats,matricula,NULL,id,user_id,'.Auth::user()->id,
            'port' => 'required'
        ];

    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                'name' => 'Nombre',
                "matricula" => "Matrícula",
                'port'=> 'Puerto',
//                'alias'=> 'Alias',
            ];
        }
        return [];
    }
    public function messages()
    {
        if ($this->locale == "es"){
            return [
                'name.regex'             => 'El :attribute sólo permite caracteres alfanumericos # y -',
                'matricula.regex'        => 'La :attribute sólo permite caracteres alfanumericos # y -',

            ];
        }
        return [];
    }

}
