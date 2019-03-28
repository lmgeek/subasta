<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\App;
class RegisterNewUserRequest extends Request
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
            'name'=>"required|regex:(^[a-zA-Zá-úÁ-Ú\s']+$)",
            'lastname'=>"required|regex:(^[a-zA-Zá-úÁ-Ú\s']+$)",
            'alias'=>"required|unique:users,nickname|max:10|regex:(^[a-zA-Z_0-9])",
            'dni'=>"required|min:7|regex:(^[0-9]+$)",
            'cuit'=>"required|min:13|regex:(^[0-9-]+$)",
            'password'=>'required|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
			'email' => 'required|min:7|unique:users,email|email',
            'phone' => 'required|regex:(^[0-9\-\+\#\(\)\*]+$)',
        ];
    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                'name' => 'Nombre',
                'phone' => 'Teléfono',
                'lastname'=> 'Apellido',
                'dni' => 'DNI',
                'cuit' => 'CUIT',
                'password'=> 'Contraseña',
                'email' => 'Email',
                'alias' => 'Alias',
            ];
        }
        return [];
    }

    public function messages()
    {
        return [

            'name.regex' => 'El nombre sólo permite caracteres alfabéticos',
            'lastname.regex' => 'El apellido sólo permite caracteres alfabéticos',
            'alias.regex' => ' El alias sólo permite letras y números',
            'alias.max' => ' El alias solo acepta 10 caracteres alfanumericos',
            'dni.min' => 'El DNI debe tener mínimo 7 caracteres',
            'dni.regex' => 'El DNI sólo permite caracteres numéricos',
            'cuit.min' => 'El CUIT debe tener mínimo 11 caracteres',
            'cuit.regex' => 'El CUIT sólo permite caracteres numéricos',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe tener de 6 a 10: 1 mayúscula, 1 letra minúscula, 1 número y  1 carácter especial',
            'email.min' => 'El email debe tener mínimo 7 caracteres',
            'email.email' => 'El email no es un correo válido',
            'phone.numeric' => 'El teléfono sólo permite caracteres numéricos',
        ];
    }
}
