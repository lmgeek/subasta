<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\RegisterNewUserRequest;
use App\User;
use Auth;
use Session;
use App\Comprador;
use App\Vendedor;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Mail;

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
	
	public function postRegisterBuyer(RegisterNewUserRequest $request)
	{	
		$name = $request->input('name');
		$dni = $request->input('dni');
		$email = $request->input('email');
		$password = $request->input('password');
		$phone = $request->input('phone');
		
		$user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
		$user->phone = $phone;
        $user->type = User::COMPRADOR;
        $user->status = User::PENDIENTE;

        if ($user->save())
		{
			$comprador = new Comprador();
			$comprador->user_id = $user->id;
			$comprador->dni = $dni;
			$comprador->save();
		}

		$this->notifyNewUserToAdmins($user);
	
		$template = 'emails.'.User::COMPRADOR.'.'.'welcome';
		Mail::queue($template, ['user' => $user ] , function ($message) use ($user) {
			$message->from(
				env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
				env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
			);
			$message->subject(trans('users.email_welcome_title'));
			$message->to($user->email);
		});
		
		 
		 Auth::login($user);
		 Session::flash('register_message', true);
		 return redirect('home');

	}
	
	public function postRegisterSeller(RegisterNewUserRequest $request)
	{	
		$name = $request->input('name');
		$cuit = $request->input('cuit');
		$email = $request->input('email');
		$password = $request->input('password');
		$phone = $request->input('phone');
		
		$user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
		$user->phone = $phone;
        $user->type = User::VENDEDOR;
        $user->status = User::PENDIENTE;

        if ($user->save())
		{
			$vendedor = new Vendedor();
			$vendedor->user_id = $user->id;
			$vendedor->cuit = $cuit;
			$vendedor->save();
		}

		$this->notifyNewUserToAdmins($user);

		$template = 'emails.'.User::VENDEDOR.'.'.'welcome';
		Mail::queue($template, ['user' => $user] , function ($message) use ($user) {
			$message->from(
				env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
				env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
			);
			$message->subject(trans('users.email_welcome_title'));
			$message->to($user->email);
		});
		 
		 Auth::login($user);
		 Session::flash('register_message', true);
		 return redirect('home');

	}
	
	public function notifyNewUserToAdmins($user){
		$adminUsers = User::where('type',User::INTERNAL)->get();
		foreach ($adminUsers as $AdminUser) {
			Mail::queue('emails.internal.newUser', [ 'user' => $user ] , function ($message) use ($AdminUser) {
				$message->from(
					env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
					env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
				);
				$message->subject( trans('emails.internal.newUser.title'));
				$message->to($AdminUser->email,$AdminUser->name);
			});
		}
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
