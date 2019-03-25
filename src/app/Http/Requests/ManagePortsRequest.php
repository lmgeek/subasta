<?php
namespace App\Http\Requests;
use App\Http\Requests\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\App;
class ManagePortsRequest extends Request
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
        return (Auth::user()->type === User::INTERNAL);
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
        ];
    }
    public function attributes()
    {
        if ($this->locale == "es"){
            return [
                'name' => 'Nombre',
            ];
        }
        return [];
    }

    public function messages()
    {
        return [
            'name.max'=>'El nombre del puerto no puede tener mas de 30 caracteres'
        ];
    }
}
