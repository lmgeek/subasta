<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterNewUserRequest extends Request
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
            'name'=>"required|regex:(^[a-zA-Zá-úÁ-Ú\s']+$)",
            'lastname'=>"required|regex:(^[a-zA-Zá-úÁ-Ú\s']+$)",
            'dni'=>"required|min:7|regex:(^[0-9]+$)",
            'cuit'=>"required|min:13|regex:(^[0-9-]+$)",
            'password'=>'required|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
//            'password'=>'required|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
			'email' => 'required|min:7|unique:users,email|email',
            'password'=>'required|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
			'email' => 'required|min:7|unique:users,email|email',
            'phone' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.regex' => 'El nombre sólo permite caracteres alfabéticos',
            'lastname.required' => 'El apellido es obligatorio',
            'lastname.regex' => 'El apellido sólo permite caracteres alfabéticos',
            'dni.required' => 'El DNI es obligatorio',
            'dni.min' => 'El DNI debe tener mínimo 7 caracteres',
            'dni.regex' => 'El DNI sólo permite caracteres numéricos',
            'cuit.required' => 'El CUIT es obligatorio',
            'cuit.min' => 'El CUIT debe tener mínimo 11 caracteres',
            'cuit.regex' => 'El CUIT sólo permite caracteres numéricos',
            'password.required' => 'La contraseña es obligatorio',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe tener de 6 a 10: 1 mayúscula, 1 letra minúscula, 1 número y  1 carácter especial',
            'email.required' => 'El email es obligatorio',
            'email.min' => 'El email debe tener mínimo 7 caracteres',
            'email.email' => 'El email no es un correo válido',
            'email.unique' => 'El email ya ha sido registrado',
            'phone.numeric' => 'El teléfono sólo permite caracteres numéricos',
        ];
    }
}
