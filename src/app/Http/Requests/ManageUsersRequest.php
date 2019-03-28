<?php
namespace App\Http\Requests;
use App\Http\Requests\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\App;
class ManageUsersRequest extends Request
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
            'nickname'=>"required|max:10|regex:(^[a-zA-Z_0-9])",
            'dni'=>"required_if:type,buyer|min:7|regex:(^[0-9]+$)",
            'cuit'=>"required_if:type,seller|min:13|regex:(^[0-9-]+$)",
            'password'=>'required_without:id|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
			'email' => 'required|min:7|email',
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
                'nickname' => 'Alias',
            ];
        }
        return [];
    }
    public function messages()
    {
        return [
            'name.regex' => 'El nombre sólo permite caracteres alfabéticos',
            'lastname.regex' => 'El apellido sólo permite caracteres alfabéticos',
            'dni.min' => 'El DNI debe tener mínimo 7 caracteres',
            'dni.regex' => 'El DNI sólo permite caracteres numéricos',
            'cuit.min' => 'El CUIT debe tener mínimo 11 caracteres',
            'cuit.regex' => 'El CUIT sólo permite caracteres numéricos',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe tener de 6 a 10: 1 mayúscula, 1 letra minúscula, 1 número y  1 carácter especial',
            'email.min' => 'El email debe tener mínimo 7 caracteres',
            'email.email' => 'El email no es un correo válido',
            'phone.numeric' => 'El teléfono sólo permite caracteres numéricos',
            'password.required_without'=>'Al agregar un usuario, es necesaria la contrase&ntilde;a.',

        ];
    }
}
