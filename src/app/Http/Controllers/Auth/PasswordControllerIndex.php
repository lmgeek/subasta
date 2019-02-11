<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\PasswordController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\User;
use App\Constants;

class PasswordControllerIndex extends PasswordController
{
    public function postEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $email=$request->input('email');
        $statu  = User::Select('status')->where('email','=',$email)->get()->toArray();



            $response = Password::sendResetLink($request->only('email'),
                function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
        if ($response== "passwords.sent" && $statu[0]["status"] == "rejected"){
            $response = "passwords.user";
        }

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
;
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            Constants::EMAIL => 'required|email',
            'password'=>'required|confirmed|regex:(^\S*(?=\S{6,8})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)',
        ]);

        $credentials = $request->only(
            Constants::EMAIL, 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });
        if($response==Password::PASSWORD_RESET){
            return redirect("home");
        }else{
            return redirect()->back()
                ->withInput($request->only(Constants::EMAIL))
                ->withErrors([Constants::EMAIL => trans($response)]);
        }
    }
    

/**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
}
