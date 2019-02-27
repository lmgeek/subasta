<?php

namespace App\Http\Controllers;

use App\Comprador;
use App\Http\Requests\RegisterNewUserRequest;
use App\Http\Requests\RegisterNewBuyerRequest;
use App\Jobs\SendEmail;
use App\User;
use App\Vendedor;
use Auth;
use Hash;
use App\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Session;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  
    }

    public function getRegisterBuyer(Request $request)
    {
        return view('buyer');
    }

    public function getRegisterSeller(Request $request)
    {
        return view('seller');
    }

    public function postRegisterBuyer(RegisterNewBuyerRequest $request)
    {
        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $nickname = $request->input('alias');
        $dni = $request->input('dni');
        $email = $request->input('email');
        $password = $request->input('password');
        $phone = $request->input('phone');

        $user = new User();
        $user->name = $name;
        $user->lastname = $lastname;
        $user->nickname = $nickname;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->phone = $phone;
        $user->type = User::COMPRADOR;
        $user->status = User::PENDIENTE;
        $user->hash = $this->generateRandomString(45);

        if ($user->save()) {
            $comprador = new Comprador();
            $comprador->user_id = $user->id;
            $comprador->dni = $dni;
            $comprador->save();
        }

        $this->notifyNewUserToAdmins($user);

        $template = Constants::MAIL_TEMPLATE_START . User::COMPRADOR . '.' . Constants::VERIFY;
        Mail::send($template, [Constants::USER => $user], function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM, Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME, Constants::MAIL_NAME)
            );
            $message->subject(trans(Constants::MAIL_SUBJECT_WELCOME));
            $message->to($user->email);
        });
        Session::flash(Constants::REGISTER_MESSAGE, true);
        return redirect(Constants::URL_LOGIN);

    }

    public function postRegisterSeller(RegisterNewUserRequest $request)
    {

        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $nickname = $request->input('alias');
        $cuit = $request->input('cuit');
        $email = $request->input('email');
        $password = $request->input('password');
        $phone = $request->input('phone');

        $user = new User();
        $user->name = $name;
        $user->lastname = $lastname;
        $user->nickname = $nickname;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->phone = $phone;
        $user->type = User::VENDEDOR;
        $user->status = User::PENDIENTE;
        $user->hash = $this->generateRandomString(45);

        if ($user->save()) {
            $vendedor = new Vendedor();
            $vendedor->user_id = $user->id;
            $vendedor->cuit = $cuit;
            $vendedor->save();
        }

        $this->notifyNewUserToAdmins($user);

        $template = Constants::MAIL_TEMPLATE_START . User::VENDEDOR . '.' . Constants::VERIFY;
        Mail::send($template, [Constants::USER => $user], function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM, Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME, Constants::MAIL_NAME)
            );
            $message->subject(trans(Constants::MAIL_SUBJECT_WELCOME));
            $message->to($user->email);
        });

        Session::flash(Constants::REGISTER_MESSAGE, true);
        return redirect(Constants::URL_LOGIN);

    }

    public function notifyNewUserToAdmins($user)
    {
        $adminUsers = User::where('type', User::INTERNAL)->get();
        foreach ($adminUsers as $AdminUser) {
            Mail::queue('emails.internal.newUser', [Constants::USER => $user], function ($message) use ($AdminUser) {
                $message->from(
                    env(Constants::MAIL_ADDRESS_SYSTEM, Constants::MAIL_ADDRESS),
                    env(Constants::MAIL_ADDRESS_SYSTEM_NAME, Constants::MAIL_NAME)
                );
                $message->subject(trans('emails.internal.newUser.title'));
                $message->to($AdminUser->email, $AdminUser->name);
            });
        }
    }

    public function verifyUsersEmail($hash){
        $user = User::where('hash', $hash)->first();
        $dbdate = strtotime($user->updated_at);
        if (time() - $dbdate > 15 * 60) {
            $this->updateHashUser($hash);
            Session::flash('register_message_fail', true);
            return redirect(Constants::URL_LOGIN);
        }

        if($user && $user->active_mail == 0){
            $user->active_mail =  1;
            $user->update();
        }
        $vendedor = Vendedor::where('user_id', $user->id)->first();
        if($vendedor === null){
            $template = Constants::MAIL_TEMPLATE_START . User::COMPRADOR . '.' . 'welcome';
        }else{
            $template = Constants::MAIL_TEMPLATE_START . User::VENDEDOR . '.' . 'welcome';
        }
        Mail::send($template, [Constants::USER => $user], function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM, Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME, Constants::MAIL_NAME)
            );
            $message->subject(trans(Constants::MAIL_SUBJECT_WELCOME));
            $message->to($user->email);
        });

        Auth::login($user);
        Session::flash(Constants::REGISTER_MESSAGE, true);
        return redirect('home');
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function updateHashUser($hash){
        $user = User::where('hash', $hash)->first();
        $user->hash =  $this->generateRandomString(45);
        $user->update();

        $template = Constants::MAIL_TEMPLATE_START . User::COMPRADOR . '.' . Constants::VERIFY;
        Mail::send($template, [Constants::USER => $user], function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM, Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME, Constants::MAIL_NAME)
            );
            $message->subject(trans(Constants::MAIL_SUBJECT_WELCOME));
            $message->to($user->email);
        });


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
