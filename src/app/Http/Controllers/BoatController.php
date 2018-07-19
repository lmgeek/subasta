<?php

namespace App\Http\Controllers;

use App\Batch;
use App\User;
use Illuminate\Http\Request;
use App\Boat;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class BoatController extends Controller
{

	public function __construct()
    {
        $this->beforeFilter('@findBoat',['only'=>['show','edit','update','destroy','approve','reject']]);
        $this->beforeFilter('@checkEvaluatePermissions',['only'=>['approve','reject']]);
    }
	
	public function findBoat(Route $route)
    {
        $this->boat = Boat::findOrFail($route->getParameter('boats'));
    }

    public function checkEvaluatePermissions()
    {
        if(Gate::denies('evaluateBoat',$this->boat)) {
            abort(401);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::allows('seeAllBoatsList')) {

            $users = User::where('type',User::VENDEDOR)->where('status',User::APROBADO)->get();

            $boats = Boat::filterAndPaginate($request->input('users'),$request->input('status'),$request->input('name'));
            return view('boat.index',compact('boats','request','users'));


        }else{
            abort(404);
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
        if(Gate::allows('seeBoatDetail',$this->boat)) {
            $boat = $this->boat;
            return view('boat.show',compact('boat'));
        }
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
        $this->boat->status = Boat::APROBADO;
        $this->boat->save();
		
		$template = 'emails.boatapproved';
		$user = $this->boat->user;
		Mail::queue($template, ['boat' => $this->boat , 'user'=> $user ] , function ($message) use ($user) {
			$message->from(
				env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
				env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
			);
			$message->subject(trans('boats.accept_boat_msg'));
			$message->to($user->email);
		});
		

        $request->session()->flash('confirm_msg', trans('boats.accept_boat_msg'));
        return redirect()->route('boats.index');
    }

    public function reject(Request $request, $id)
    {
        $this->boat->status = Boat::RECHAZADO;
        $this->boat->rebound = $request->input('motivo');
        $this->boat->save();
		
		
		$template = 'emails.boatrebound';
		$user = $this->boat->user;
		Mail::queue($template, ['boat' => $this->boat , 'user'=> $user ] , function ($message) use ($user) {
			$message->from(
				env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
				env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
			);
			$message->subject(trans('boats.reject_boat_msg'));
			$message->to($user->email);
		});
		

        $request->session()->flash('confirm_msg', trans('boats.reject_boat_msg'));
        return redirect()->route('boats.index');
    }
	
	
	public function sellerbatch(Request $request)
	{
        if (Auth::user()->type == \App\User::VENDEDOR){
            $this->authorize('seeBatch', new Batch());

            $boats = array();
		
			$boats = Boat::where('user_id',Auth::user()->id)->where('status',Boat::APROBADO)->paginate();

            return view('boat.sellerbatch',compact('boats','request'));
        } else {
            return redirect('/home');
            // return view('landing');
        }
		
			
		
		 
	}
	
}
