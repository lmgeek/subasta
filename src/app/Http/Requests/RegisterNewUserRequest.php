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
        $accentedCharacters = "àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ";
        return [
            'name'=>"required|regex:(^[a-zA-Z$accentedCharacters]+)",
            'lastname'=>"required|regex:(^[a-zA-Z$accentedCharacters]+)",
            'dni'=>"required|min:8|regex:([0-9]+)",
            'cuit'=>"required|min:11|regex:([0-9]+)",
            'password'=>'required|min:6|confirmed|regex:((?=^.{6,10}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*()_+}{&quot;:;\'?/&gt;.&lt;,])(?!.*\s).*$)',
			'email' => 'required|min:7|unique:users,email|email'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.regex' => 'El nombre solo permite caracteres alfabéticos',
            'lastname.required' => 'El apellido es obligatorio',
            'lastname.regex' => 'El apellido solo permite caracteres alfabéticos',
            'dni.required' => 'El DNI es obligatorio',
            'dni.min' => 'El DNI debe tener mínimo 8 caracteres',
            'dni.regex' => 'El DNI solo permite caracteres numéricos',
            'cuit.required' => 'El CUIT es obligatorio',
            'cuit.min' => 'El CUIT debe tener mínimo 11 caracteres',
            'cuit.regex' => 'El CUIT solo permite caracteres numéricos',
            'password.required' => 'La contraseña es obligatorio',
            'password.min' => 'La contraseña debe tener mínimo 4 caracteres',
            'password.confirme' => 'La contraseñas no coinciden',
            'password.regex' => 'La contraseña debe tener 1 mayúscula, 1 letra minúscula, 1 digito y 1 carácter especial',
            'email.required' => 'El email es obligatorio',
            'email.min' => 'El email debe tener mínimo 7 caracteres',
        ];
    }
}
