<?php
namespace App\Http\Requests;

class ManageUsersRequest extends Request
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
            'type'=>'required',
            'name'=>"required|regex:(^[a-zA-Zá-úÁ-Ú\s']+$)",
            'lastname'=>"required|regex:(^[a-zA-Zá-úÁ-Ú\s']+$)",
            'nickname'=>"required|max:10|regex:(^[a-zA-Z_0-9])",
            'dni'=>"required_if:type,buyer|min:7|regex:(^[0-9]+$)",
            'limit'=>"required_if:type,buyer|numeric|min:1",
            'cuit'=>"required_if:type,seller|min:13|regex:(^[0-9-]+$)",
            'password'=>'required_without:id|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
			'email' => 'required|min:7|email',
            'phone' => 'required|numeric|regex:(^[()0-9-]+$)',
            //'reason'=>'required_if:status,rejected|min:1|max:100'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'phone.required' => 'El teléfono es obligatorio',
            'name.regex' => 'El nombre sólo permite caracteres alfabéticos',
            'lastname.required' => 'El apellido es obligatorio',
            'lastname.regex' => 'El apellido sólo permite caracteres alfabéticos',
            'alias.regex' => ' El alias sólo permite letras y números',
            'alias.unique' => ' Este alias ya existe',
            'alias.max' => ' El alias solo acepta 10 caracteres alfanumericos',
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
