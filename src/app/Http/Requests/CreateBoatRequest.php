<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;

class CreateBoatRequest extends Request
{
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
            'matricula' => 'required|regex:(^[0-9a-zA-Zá-úÁ-Ú\-]+$)|unique:boats,matricula,NULL,id,user_id,'.Auth::user()->id
        ];
//        $rules = [
//            'name'      => 'required|unique:boats,name,NULL,id,user_id,'.Auth::user()->id,
//            'matricula' => 'required|unique:boats,matricula,NULL,id,user_id,'.Auth::user()->id
//        ];
//
//        $messages = [
//            'name.require'           => 'El Nombre del barco no puede estar vacío.',
//            'name.unique'            => 'El nombre del barco ya se encuentra registrado.',
//            'matricula.require'      => 'La Matrícula no puede estar vacío.',
//            'matricula.unique'       => 'La Matrícula ya se encuentra registrada.',
//        ];
//
//        return ($rules,$messages);
    }

    public function messages()
    {
        return [
            'name.require'           => 'El Nombre del barco no puede estar vacío.',
            'name.unique'            => 'El nombre del barco ya se encuentra registrado.',
            'name.regex'             => 'El nombre sólo permite caracteres alfanumericos y # y -',
//            'alias.require'             => 'El alias es obligatorio',
            'matricula.require'      => 'La Matrícula no puede estar vacío.',
            'matricula.unique'       => 'La Matrícula ya se encuentra registrada.',
            'matricula.regex'        => 'La Matrícula sólo permite caracteres alfanumericos y -',
        ];
    }

}
