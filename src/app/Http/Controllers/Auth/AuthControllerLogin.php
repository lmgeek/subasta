<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Constants;

class AuthControllerLogin extends AuthController
{
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }

        $active_mail= Auth::user()->active_mail;
        $status= Auth::user()->status;
        // filtro para que no inicie seccion ante de verificar su correo
        if ($status == 'rejected') {
            // cerra seccion
            Auth::logout();
            // mostrar mensaje en la plantalla
            return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), Constants::REMEMBER))
                ->withErrors([
                    $this->loginUsername() => $this->getMessageRejected(),
                ]);
        }
        elseif ($active_mail == 1) {
            return redirect('/?log=1');
        }else{
            // cerra seccion
            Auth::logout();
            // mostrar mensaje en la plantalla
            return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), Constants::REMEMBER))
                ->withErrors([
                    $this->loginUsername() => $this->getMessageMailValidated(),
                ]);
        }

    }
    protected function getMessageMailValidated()
    {
        return Lang::has('auth.emailValidated')
            ? Lang::get('auth.emailValidated')
            : 'Correo no verificado. Haga clic en el enlace enviado a su correo.';
    }
    protected function getMessageRejected()
    {
        return Lang::has('auth.rejected')
            ? Lang::get('auth.rejected')
            : 'Usuario rechazado, comunicarse con los administradores de este sitio.';
    }
}