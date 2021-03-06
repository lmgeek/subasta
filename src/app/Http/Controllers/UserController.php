<?php
namespace App\Http\Controllers;
use App\User;
use App\Vendedor;
use App\Comprador;
use App\Constants;
use App\Bid;
use App\ViewHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
//use Hash;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Mail;
 use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ManageUsersRequest;


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
            $b = new Bid();
            $total = $b->getTotalByUser($user);
            $bids = Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->limit(10)->get();

		}
//
		if ($user->type == User::COMPRADOR)
		{	
			$b = new Bid();
			$total = $b->getTotalByUser($user);
			$bids = Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->limit(10)->get();
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
    public function userAdd(){
        if(empty(Auth::user()->type) || Auth::user()->type!=User::INTERNAL){
            return redirect('/');
        }
        return view('landing3/user-add-edit');
    }
    public function userEdit($id){
        if(empty(Auth::user()->type) || (Auth::user()->id!=$id && Auth::user()->type!=User::INTERNAL)){
            return redirect('/');
        }
        $user= User::select()->where('id',Constants::EQUAL,$id)->get();
        if(count($user)>0){
            $user=$user[0];
        }else{
            return view('landing3/errors/404');
        }
        if($user->type== Constants::VENDEDOR){
            $vendedor= Vendedor::select()->where('user_id',Constants::EQUAL,$user->id)->get()[0];
            $user->vendedor=$vendedor;
        }else if($user->type== User::COMPRADOR){
            $comprador= Comprador::select()->where('user_id',Constants::EQUAL,$user->id)->get()[0];
            $user->comprador=$comprador;
        }
        $user->offers=\App\Offers::getOffersByBuyer($user->id);
        $user->bids=\App\Bid::getBidsByBuyer($user->id);
        return view('landing3/user-add-edit')->with('user',$user);
    }
    public function userList(){
        if(empty(Auth::user()->type) || Auth::user()->type!=User::INTERNAL){
            return redirect('/');
        }
        $users= User::select()->paginate(Constants::PAGINATE_NUMBER);
        return view('landing3/users')->with('users',$users);
    }
    public function userMyBids($userid=null){
        if(empty(Auth::user()->type) || (Auth::user()->type==User::VENDEDOR && Auth::user()->id!=$userid)){
            return redirect('/');
        }
        
        $id=($userid==null)?Auth::user()->id:$userid;
        $user= User::select()->where('id',Constants::EQUAL,$id)->get();
        if(count($user)>0){
            $user=$user[0];
        }else{
            return view('landing3/errors/404');
        }
        $comprador= Comprador::select()->where('user_id',Constants::EQUAL,$user->id)->get();
        if(count($comprador)>0){
            $comprador=$comprador[0];
        }else{
            return view('landing3/errors/404');
        }
        $user->comprador=$comprador;
        $user->bids=\App\Bid::getBidsByBuyer($user->id,1);
        return view('landing3/compras')->with('user',$user);
    }
    public function userMyOffers($userid=null){
        if(empty(Auth::user()->type) || Auth::user()->type==User::VENDEDOR){
            return redirect('/');
        }
        $id=($userid==null)?Auth::user()->id:$userid;
        $user= User::select()->where('id',Constants::EQUAL,$id)->get();
        if(count($user)>0){
            $user=$user[0];
        }else{
            return view('landing3/errors/404');
        }
        $comprador= Comprador::select()->where('user_id',Constants::EQUAL,$user->id)->get();
        if(count($comprador)>0){
            $comprador=$comprador[0];
        }else{
            return view('landing3/errors/404');
        }
        $user->comprador=$comprador;
        $user->offers=\App\Offers::getOffersByBuyer($user->id,1);
        return view('landing3/ofertas')->with('user',$user);
    }
    public static function checkIfUserCanChangeTypeApproval($user){
        $auctions= \App\AuctionQuery::auctionHome(null, ['sellerid'=>$user->id]);
        $bids= count(Bid::getBidsByBuyer($user->id,null,null,Constants::PENDIENTE));
        $offers=count(\App\Offers::getOffersByBuyer($user->id,null,null));
        if(count($auctions['all'])>0 && (count($auctions[Constants::IN_COURSE])+count($auctions[Constants::FUTURE]))>0){
            $return['success']=0;
            $return['error']='El usuario a rechazar tiene subastas en curso o programadas';
        }elseif($bids>0){
            $return['success']=0;
            $return['error']='El usuario a rechazar tiene compras pendientes';
        }elseif($offers>0){
            $return['success']=0;
            $return['error']='El usuario a rechazar tiene ofertas pendientes';
        }else{
            $return['success']=1;
            $return['message']='El usuario '.$user->name.' '.$user->lastname.' ('.$user->nickname.') fue rechazado.';
        }
        return $return;
    }
    public static function usersChangeApproval($id){
        $return=array();
        if(empty(Auth::user()->type) || ((isset($nickname) && Auth::user()->nickname!=$nickname) && Auth::user()->type!=User::INTERNAL)){
            $return['success']=0;
            $return['error']='No tienes permisos para hacer esto';
        }
        if(empty($id)){
            $return['success']=0;
            $return['error']='El ID del usuario es obligatorio';
        }
        $user= User::select()->where('id',Constants::EQUAL,$id)->get();
        if(count($user)>0){
            $user=$user[0];
        }else{
            $return['success']=0;
            $return['error']='El ID del usuario es invalido';
        }
        if(isset($return['error'])){
            return json_encode($return);
        }
        if($user->status=='approved'){
            $check=self::checkIfUserCanChangeTypeApproval($user);
            $return['success']=$check['success'];
            if($check['success']==0){
                $return['error']=$check['error'];
            }else{
                $return['message']=$check['message'];
                $user->status='rejected';
                $user->save();
            }
        }else{
            $return['success']=1;
            $user->status='approved';
            $user->save();
            $return['message']='El usuario '.$user->name.' '.$user->lastname.' ('.$user->nickname.') fue aprobado';
        }
        $return['title']='Usuario Modificado';
        $return['status']=$user->status;
        $return['statusTrans']=trans('general.status.'.$user->status);
        return json_encode($return);
    }
    public static function checkIfEmailIsBeingUsed($oldemail,$newemail){
        $return=true;
        if($oldemail!=$newemail){
            $checker=User::select()
                    ->where('email',Constants::EQUAL,$newemail)
                    ->get();
            if(count($checker)>0){
                $return=false;
            }
        }
        return $return;
    }
    public static function checkIfNicknameIsBeingUsed($oldnickname,$newnickname){
        $return=true;
        if($oldnickname!=$newnickname){
            $checker=User::select()
                    ->where('nickname',Constants::EQUAL,$newnickname)
                    ->get();
            if(count($checker)>0){
                $return=false;
            }
        }
        return $return;
    }
    public function userSave(ManageUsersRequest $request){
        $errors=array();
        if(empty(Auth::user()->type) || ((isset($nickname) && Auth::user()->nickname!=$nickname) && Auth::user()->type!=User::INTERNAL)){
            return redirect('/');
        }
        
        if(isset($request->id)){
            $user= User::select()->where('id',Constants::EQUAL,$request->id)->get();
            if(count($user)>0){
                $user=$user[0];
            }else{
                $errors[]='Usuario Inv&aacute;lido.';
            }
            if(self::checkIfEmailIsBeingUsed($user->email, $request->email)==false){
                $errors[]='El correo debe ser &uacute;nico';
            }
            if(self::checkIfNicknameIsBeingUsed($user->nickname, $request->nickname)==false){
                $errors[]='El alias debe ser &uacute;nico';
            }
            if(isset($request->limit) && $request->limit>9999999){
                $errors[]='El limite m&aacute;ximo de compra es 9999999';
            }
            if(count($errors)>0){
                return redirect()->back()->with('errors',$errors);
            }
            $check=self::checkIfUserCanChangeTypeApproval($user);
            if(Auth::user()->type=='internal' && isset($request->status) && $request->status!='' && $check['success']==1){
                $user->status=$request->status;
            }elseif(Auth::user()->type=='internal' && (empty($request->status) || $request->status=='')){
                $user->status=Constants::PENDIENTE;
            }
            if(Auth::user()->type=='internal' && isset($request->type) && $check['success']==1){
                $user->type=$request->type;
            }
            $user->hash= md5(date('YmdHis').$request->nickname);
            if($user->type== Constants::VENDEDOR){
                $vendedor= Vendedor::select()->where('user_id',Constants::EQUAL,$request->id)->get();
                if(count($vendedor)>0){
                    $vendedor=$vendedor[0];
                }else{
                    $vendedor = new Vendedor();
                }
            }else if($user->type== User::COMPRADOR){
                $comprador= Comprador::select()->where('user_id',Constants::EQUAL,$request->id)->get();
                if(count($comprador)>0){
                    $comprador=$comprador[0];
                }else{
                    $comprador = new Comprador();
                }
                
            }
        }else{
            $checker=User::select()
                    ->where('nickname',Constants::EQUAL,$request->nickname)
                    ->orWhere('email',Constants::EQUAL,$request->email)
                    ->get();
            if(count($checker)>0){
                return redirect()->back()->with('errors',array('El correo y/o el usuario ya estan registrados'));
            }
            $user  = new User();
            
            if(Auth::user()->type=='internal' && isset($request->status)){
                $user->status=$request->status;
            }elseif(Auth::user()->type=='internal' && empty($request->status)){
                $user->status=User::PENDIENTE;
            }
            $user->hash= md5(date('YmdHis').$request->nickname);
            $user->active_mail=0;
            if($request->type==User::COMPRADOR){
                $comprador = new Comprador();
            }elseif($request->type==User::VENDEDOR){
                $vendedor = new Vendedor();
            }
        }
        $user->name=$request->name;
        $user->lastname=$request->lastname;
        $user->nickname=$request->nickname;
        $user->email=$request->email;
        $user->phone=$request->phone;
        if($request->password!=''){
            
            if(isset($request->passwordcurrent) && Hash::check($request->passwordcurrent, $user->password)==false){
                return redirect()->back()->with('errors',array('La clave actual no coincide con la de tu usuario'));
            }
            $user->password = Hash::make($request->password);
        }
        $user->save();
        if($user->type==User::COMPRADOR){
            $comprador->user_id = $user->id;
            $comprador->dni = $request->dni;
            if(isset($request->limit)){
                $comprador->bid_limit=$request->limit;
            }
            $comprador->save();
        }elseif($user->type==User::VENDEDOR){
            
            $vendedor->user_id = $user->id;
            $vendedor->cuit=  $request->cuit;
            $vendedor->save();
        }
        if(empty($request->id)){
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
        return redirect('usuarios/editar/'.$user->id);
    }

//Funcion para traer toda la info de usuario
    public function getInfoUser(){
            $user= User::select()->where('id','=',Auth::user()->id)->get();
            return view('landing3/acount', compact('user'));
    }



}
