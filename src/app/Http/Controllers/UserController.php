<?php

namespace App\Http\Controllers;

use App\Comprador;
use App\User;
use App\Bid;
use App\ViewHelper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Auth;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{

    public function __construct()
    {
        $this->beforeFilter('@findUser',['only'=>['show','edit','update','destroy','approve','reject']]);
    }

    public function findUser(Route $route)
    {
        $this->user = User::findOrFail($route->getParameter('users'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('seeUsersList', Auth::user());
        $users = User::filter($request->get('name'),$request->get('type'),$request->get('status'));
		
		$userRating =  array();
		foreach($users as $user)
		{
			$porc = 0;
			$ratings = $user->rating;
			if (null != $ratings )
			{
				$total = $ratings->positive + $ratings->negative + $ratings->neutral;
				if ($total > 0)
				{
					$porc = round(($ratings->positive*100)/$total , 2);
				}
				
			}
			$userRating[$user->id]= $porc;
		}
		
		
        return view('user.index',compact('users','request','userRating'));

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
        $this->authorize('seeUserDetail', Auth::user());

        $user = $this->user;
        $info = ViewHelper::UserAdditionalInfo($user);
        $info->bid_limit = str_replace('.',',', $info->bid_limit);

		$bids = array();
		$total = 0;
		$total2 = 0; 
		$score = 0;
		if ($user->type == User::VENDEDOR)
		{
			$total = $user->seller->getTotalSales();
			$total2 = $user->seller->getTotalPrivateSales();
			$bids = $user->seller->mySales()->orderBy('bid_date','desc')->limit(10);
			
		}
		
		if ($user->type == User::COMPRADOR)
		{	
			$b = new Bid();
			$total = $b->getTotalByUser($user);
			$bids = Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->limit(10);
		}	
		
		
		
		$ratings = $user->rating;
		if (null != $ratings )
		{
			$rat = $ratings->positive + $ratings->negative + $ratings->neutral;
			if ($rat > 0)
			{
				$score = round(($ratings->positive*100)/$rat , 2);
			}
			
		}

        return view('user.show',compact('user','info','bids','total','total2','score'));
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

    public function approve(Request $request, $id)
    {
        $this->user->status = User::APROBADO;
        $this->user->save();
		
		
		$template = 'emails.userapproved';
		$user = $this->user;
		Mail::queue($template, ['user' => $user] , function ($message) use ($user) {
			$message->from(
				env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
				env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
			);
			$message->subject(trans('users.email_welcome_title'));
			$message->to($user->email);
		});
		

        $request->session()->flash('confirm_msg', trans('users.accept_user_msg'));
        return redirect()->route('users.index');
    }
	
	public function editBidLimit(Request $request)
	{
		$user  = User::findOrFail($request->input('user_buyer_id'));
		$bid_limit = $request->input('bid_limit');
		$user->buyer->bid_limit = $bid_limit;
		$user->buyer->save();
		return redirect()->route('users.show', $user->id);
	}

    public function reject(Request $request, $id)
    {
        $this->user->status = User::RECHAZADO;
        $this->user->rebound = $request->input('motivo');
        $this->user->save();
		
		
		$template = 'emails.userrebound';
		$user = $this->user;
		Mail::queue($template, ['user' => $user] , function ($message) use ($user) {
			$message->from(
				env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
				env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
			);
			$message->subject(trans('users.email_rejected_title'));
			$message->to($user->email);
		});
		
		

        $request->session()->flash('confirm_msg', trans('users.reject_user_msg'));
        return redirect()->route('users.index');
    }
}
