<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use App\Constants;
use App\Batch;
use App\Boat;
use App\User;
use Auth;

class BoatController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
        $this->beforeFilter('@findBoat',['only'=>['show','edit','update','destroy','approve','reject']]);
        $this->beforeFilter('@checkEvaluatePermissions',['only'=>['approve','reject']]);
    }
	
	public function findBoat(Route $route)
    {
        $this->boat = Boat::findOrFail($route->getParameter(Constants::BOATS));
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

            $users = User::where('type',User::VENDEDOR)->where(Constants::STATUS,User::APROBADO)->get();

            $boats = Boat::filterAndPaginate($request->input(Constants::USERS),$request->input(Constants::STATUS),$request->input('name'));
            return view('boat.index',compact(Constants::BOATS,'request',Constants::USERS));


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
        $this->boat->status = Constants::APROBADO;
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
        $this->boat->status = Constants::RECHAZADO;
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
		
			$boats = Boat::where('user_id',Auth::user()->id)->where(Constants::STATUS,Constants::APROBADO)->paginate();
            return view('boat.sellerbatch',compact(Constants::BOATS,'request'));
        } else {
            return redirect('/home');
        }
	}
    public function boatList(){

        if (Auth::check() && Auth::user()->type == \App\User::VENDEDOR)
        {
            $boats = Boat::select()->where('user_id', Constants::EQUAL, Auth::user()->id)->orderby('name', 'asc')->paginate(2);
            $countBoat = count(Boat::select()->where('user_id', Constants::EQUAL, Auth::user()->id)->get());
            return view('landing3/boats',compact('boats','countBoat'));

        }elseif(Auth::user()->type == \App\User::INTERNAL){

            $boats = Boat::select()->orderby('name', 'asc')->paginate(2);
            $countBoat = count(Boat::all());
            return view('landing3/boats',compact('boats','countBoat'));

        }else{
            return redirect('/home');
        }
    }
    /* INI Rodolfo*/
    public static function getPreferredPort(Request $request){
        $boat=Boat::select()->where('id',Constants::EQUAL,$request->idboat)->get();
        return json_encode(array('preferred'=>$boat[0]->preference_port));
    }
    /* FIN Rodolfo*/


    //G.B funcion para filtrar por estatus
    public function getStatusTheBoat(Request $request){

        if (Auth::check() && Auth::user()->type == \App\User::VENDEDOR)
        {
            if ($request->status != "all") {
                $boats = Boat::select()->where('status','=',$request->status)->orderby('name', 'asc')->paginate(2);
                $countBoat =count(Boat::select()->where('status','=',$request->status)->get());
                return view('landing3/boats',compact('boats', 'countBoat'));
            }else{
                return redirect()->to('/barcos');
            }

        }elseif(Auth::user()->type == \App\User::INTERNAL) {
            if ($request->status != 'all') {
                $boats = Boat::select()->where('status', '=', $request->status)->orderby('name', 'asc')->paginate(2);
                $countBoat = count($boats);
                return view('landing3/boats', compact('boats', 'countBoat'));
            }else{
                return redirect()->to('/barcos');
            }
        }else{
            return redirect('/home');
        }

    }
}
