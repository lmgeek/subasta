<?php

namespace App\Http\Controllers;

use App\Batch;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ManagePortsRequest;
use App\Ports;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Constants;

class PortsController extends Controller
{

	public function __construct()
    {
        $this->beforeFilter('@findPorts',['only'=>['show','edit','update','destroy','approve','reject']]);
        $this->beforeFilter('@checkEvaluatePermissions',['only'=>['approve','reject']]);
    }
	
	public function findPorts(Route $route)
    {
        $this->port = Ports::findOrFail($route->getParameter('port'));
    }

    public function checkEvaluatePermissions()
    {
        if(Gate::denies('evaluateBoat',$this->port)) {
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

        $users = User::where('type',User::VENDEDOR)->where('status',User::APROBADO)->get();

        $port = Ports::filterAndPaginate();
        return view('boat.index',compact('port','request','users'));

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
        if(Gate::allows('seeBoatDetail',$this->port)) {
            $port = $this->port;
            return view('boat.show',compact('port'));
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
	public function portsList(){
        if(empty(Auth::user()->type) || Auth::user()->type!=User::INTERNAL ){
            return redirect('/');
        }
        $ports= Ports::select()->paginate(Constants::PAGINATE_NUMBER);
        return view('landing3/puertos')->with('ports',$ports);
    }
    public function portsAdd(){
        if(empty(Auth::user()->type) || Auth::user()->type!=User::INTERNAL ){
            return redirect('/');
        }
        return view('landing3/ports-add-edit');
    }
    public function portsEdit($portid){
        if(empty(Auth::user()->type) || Auth::user()->type!=User::INTERNAL ){
            return redirect('/');
        }
        $port= Ports::select()->where('id',Constants::EQUAL,$portid)->get();
        if(count($port)>0){
            $port=$port[0];
        }else{
            return view('landing3/errors/404');
        }
        return view('landing3/ports-add-edit')->with('port',$port);
    }
    public function portsSave(ManagePortsRequest $request){
        if(empty(Auth::user()->type) || Auth::user()->type!=User::INTERNAL ){
            return redirect('/');
        }
        if(isset($request->id)){
            $port= Ports::select()->where('id',Constants::EQUAL,$request->id)->get()[0];
        }else{
            $port=new Ports();
        }
        $port->image=$request->image;
        $port->name=$request->name;
        $port->save();
        return redirect('/puertos/editar/'.$port->id);
    }
	

	
}
