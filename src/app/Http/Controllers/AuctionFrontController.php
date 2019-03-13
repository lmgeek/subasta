<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AuctionBackController;
use App\Http\Requests\CreateAuctionRequest;
use App\Http\Requests\SellerQualifyRequest;
use App\Http\Requests\UpdateAuctionRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProcessBidRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\AuctionInvited;
use App\BatchStatus;
use App\UserRating;
use App\Constants;
use Carbon\Carbon;
use App\Auction;
use App\AuctionQuery;
use App\Product;
use App\Arrive;
use App\Offers;
use App\Batch;
use App\Ports;
use App\Boat;
use App\User;
use App\Bid;

use Excel;
use Auth;
use App;

class AuctionFrontController extends AuctionController
{
    /**
     * funcion que llama la vista de detalles de una subasta
     * @param $auction_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auctionDetails($auction_id){
        $auction = Auction::findOrFail($auction_id);
        //Creamos la instacia de AuctionController para usar el metodo de calculatePriceID
        $objAuct = new AuctionController();
        $price= self::calculatePriceID($auction_id)['CurrentPrice'];
        $availability=AuctionBackController::getAvailable($auction_id,$auction->amount);
        return view('landing3.subasta', compact(Constants::AUCTION,Constants::PRICE,Constants::AVAILABILITY));
    }
    /* INI Rodolfo */
    public static function storeBatch($arriveid,$productid,$caliber,$quality,$amount,$replicate=0,$batchid=null){
        if($batchid==null){
            $batch = new Batch();
//            $batchStatus = new BatchStatus();
        }else{
            if(count(Auction::select()->where(Constants::BATCH_I,Constants::EQUAL,$batchid)->get())>1 || $replicate>0){
                return $batchid;
            }
            $batch= Batch::findOrFail($batchid);
//            $batchStatus= BatchStatus::findOrFail($batchid);
        }
        $batch->arrive_id = $arriveid;
        $batch->product_id = $productid;
        $batch->caliber = $caliber;
        $batch->quality = $quality;
        $batch->amount = NULL;
        $batch->save();
        $batchid=$batch->id;
//        $batchStatus->batch_id = $batchid;
//        $batchStatus->assigned_auction = 0 ; 
//        $batchStatus->auction_sold = 0 ; 
//        $batchStatus->private_sold = 0 ; 
//        $batchStatus->remainder = $batch->amount ; 
//        $batchStatus->save();
        return $batchid;
    }
    public static function storeArrive($boatid,$portid,$date=null,$replicate=0,$arriveid=null){
        if($arriveid==null){
            $arrive=new Arrive();
        }else{
            $batches=Batch::select()->where(Constants::ARRIVE_ID,Constants::EQUAL,$arriveid)->get();
            $contauctions=0;
            foreach($batches as $batch){
                $contauctions+=count(Auction::select()->where('batch_id',Constants::EQUAL,$batch->id)->get());
            }
            if($contauctions>1 || $replicate>0){
                return $arriveid;
            }
            $arrive= Arrive::findOrFail($arriveid);
        }
        $arrive->boat_id = $boatid;
        $arrive->port_id = $portid;
        if($date!=null){
            $arrive->date = $date;
        }else{
            $arrive->date = NULL;
        }
        $arrive->save();
        return $arrive->id;
    }
    public function storeAuction(CreateAuctionRequest $request){
        $startDate =date(Constants::DATE_FORMAT,strtotime(str_replace('/','-',$request->fechaInicio)));
        $endDate=date_add(date_create($startDate),date_interval_create_from_date_string("+$request->ActiveHours hours"))->format(Constants::DATE_FORMAT);
        $tentativeDate =date(Constants::DATE_FORMAT,strtotime(str_replace('/','-',$request->fechaTentativa)));
        $startprice=$request->startPrice;
        $endprice=$request->endPrice;
        $arrivedate=null;
        $errors=array();
        if($request->type!='replication' && !in_array($request->caliber, Product::caliber())){
            $errors[]='Debes seleccionar un calibre';
        }
        if($request->type!='replication' && !in_array($request->unidad, Product::units())){
            $errors[]='Debes seleccionar una unidad de presentacion';
        }
        if($endprice>=$startprice){
            $errors[]='El precio de retiro no puede ser mayor al precio inicial.';
        }
        
        if($tentativeDate<$endDate){
            $errors[]='La fecha tentativa de entrega no puede ser antes del final de la subasta';
        }
        if(count($errors)>0){
            return Redirect::back()->withErrors($errors);
        }
        $products = $request->product;
        $caliber = $request->input(Constants::CALIBER);
        $quality = $request->quality;
        $amount = $request->input(Constants::AMOUNT);
        //$privacy=$request->input('tipoSubasta');
        $privacy=Constants::AUCTION_PUBLIC;
        
        
        
        $targetprice=$endprice+(($startprice-$endprice)*rand(1,7)/100);
        $replicate=(isset($request->type) && $request->type=='replication')?1:0;
        $arriveid=self::storeArrive($request->barco, $request->puerto, $arrivedate,$replicate,((isset($request->arriveid))?$request->arriveid:null));
        $batchid=self::storeBatch($arriveid, $products, $caliber, $quality, $amount, $replicate, ((isset($request->batchid))?$request->batchid:null));
        $auctionid=(isset($request->auctionid))?$request->auctionid:null;
        if($auctionid!=null && $replicate==0){
            $auction = Auction::findOrFail($request->auctionid);
        }else{
            $auction  = new Auction();
            $auction->correlative=self::calculateAuctionCode();
        }
        $auction->batch_id = $batchid;
        $auction->start = $startDate;
        $auction->start_price = $startprice;
        $auction->end = $endDate;
        $auction->end_price = $endprice;
        $auction->target_price = $targetprice;
        $auction->interval = 1;
        $auction->amount = $request->input(Constants::AMOUNT);
		$auction->type = $privacy;
		$auction->description = $request->input('descri');
        $auction->tentative_date = $tentativeDate;
		$auction->save();
		if ($privacy == Constants::AUCTION_PRIVATE ){
			$guests  = $request->invitados;
            $seller=Auth::user() ;
			foreach($guests as $guest){
				$auctionInvited = new AuctionInvited();
				$auctionInvited->auction_id = $auction->id;
				$auctionInvited->user_id = $guest;
				$auctionInvited->save();
				$user=User::findOrFail($guest);
				Mail::queue('emails.userinvited',['user'=>$user,Constants::SELLER=>$seller],function($message) use ($user){
					$message->from(
						env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
						env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
					);
					$message->subject(trans('users.private_auction'));
					$message->to($user->email);
				});
			}
		}
        if(empty($request->testing)){
            return redirect('subastas?type=mine&time=future&e='.$request->type.'&t=auction&id='.$auction->id.(($request->type=='replication')?('&ex='. urlencode('replicated from auction: '.$auctionid)):''));
        }else{
            $auction->tested=true;
            return json_encode($auction);
        }
    }
    public static function checkIfBuyerCanBuy($id,$amount,$type="bid",$privacy='public'){
        if(empty(Auth::user()->id) || Auth::user()->type!='buyer' || Auth::user()->status!='approved' || ($privacy==Constants::AUCTION_PRIVATE && AuctionQuery::checkifUserInvited(Auth::user()->id)== Constants::INACTIVE)){
            return 0;
        }
        if($type!='bid'){
            return 1;
        }
        $bids = Bid::where(Constants::BIDS_USER_ID, Auth::user()->id)->where(Constants::STATUS,Constants::EQUAL,Constants::PENDIENTE)->get();
        foreach($bids as $bid){
            $amount+=($bid->amount*$bid->price);
        }

        $comprador = User::select('*')
            ->join('comprador',Constants::USERS_ID,'=','comprador.user_id')
            ->where(Constants::USERS_ID, Auth::user()->id)->get();
        return ($amount>$comprador[0]->bid_limit)?0:1;

    }
    public static function getUserRating($userinfo){
        $ratings = $userinfo->rating;
        $total = ($ratings != null) ? ($ratings->positive + $ratings->negative + $ratings->neutral) : 0;
	    return ($ratings != null && $total > 0) ? (round(($ratings->positive * 100) / $total/20, 0,PHP_ROUND_HALF_UP)) : 1;
    }
    public static function calculateAuctionCode(){
        $auctions=AuctionQuery::AuctionsQueryBuilder(array('sellerid'=>Auth::user()->id));
        $counter=1;$highest=0;
        foreach($auctions as $auction){
            $dateformatted=date('md',strtotime($auction->created_at));
            $correlative=$auction->correlative;
            if($dateformatted==date('md')){
                $counter++;
                if($correlative>$highest){
                    $highest=$correlative;
                }
            }
        }
        $highest++;
        return ($highest>$counter)?$highest:$counter;
    }
    public static function getAuctionCode($correlative,$created_at){
        if($correlative==null){
            $correlative=(string)"000";
        }elseif($correlative<10){
            $correlative=(string)"00$correlative";
        }elseif($correlative<100){
            $correlative="0$correlative";
        }
        return 'SU-'.date('ym',strtotime($created_at)).$correlative;
    }
    public static function getAuctionsDataForHome($auctions){
        
        $port=array();$products=array();$calibers=array();$users=array();
        $close=0;
        $quality=array(1=>0,2=>0,3=>0,4=>0,5=>0);$ratings=array(1=>0,2=>0,3=>0,4=>0,5=>0);
        foreach($auctions as $a) {
            $calibers[$a->batch->caliber]=(isset($calibers[$a->batch->caliber]))?$calibers[$a->batch->caliber]+1:1;
            $user = $a->batch->arrive->boat->user;
            $users[$user->id]=(isset($users[$user->id]))?$users[$user->id]+1:1;
            $port[$a->batch->arrive->port_id]=(isset($port[$a->batch->arrive->port_id]))?$port[$a->batch->arrive->port_id]+1:1;
            $products[$a->batch->product_id]=(isset($products[$a->batch->product_id]))?$products[$a->batch->product_id]+1:1;
            $ratings[self::getUserRating($user)]++;
            $quality[$a->batch->quality]++;
            $close+=self::calculatePriceID($a->id,$a->target_price)[Constants::CLOSE];
        }
        return array(
            Constants::PRODUCTS=>$products,
            Constants::PORTS=>$port,
            Constants::CALIBERS=>$calibers,
            Constants::USERS=>$users,
            Constants::CLOSE=>$close,
            Constants::RATINGS=>$ratings,
            Constants::QUALITY=>$quality
        );
    }
    public static function convertFilterSubastas($filters){
        if(count($filters)==0){
            return;
        }
        $params=array();
        $paramskeys=array('port'=>'portid','product'=>'productid','caliber'=>'caliber','quality'=>'quality','user'=>'sellerid','pricemin'=>'pricemin','pricemax'=>'pricemax','close'=>'close','userrating'=>'userrating','type'=>'type');
        foreach($filters as $key=>$val){
            $valtrimmed=(substr_count($val,'**')>0)?substr($val,0,-2):$val;
            $params[$paramskeys[$key]]=(substr_count($valtrimmed,'**')>0)?explode('**',$valtrimmed):$valtrimmed;
        }
        return $params;
    }
    public static function getMaxMinPrice(){
        $max=Auction::select('*')->orderBy('start_price','desc')->limit(1)->get();
        $min=Auction::select('end_price')->orderBy('end_price','asc')->limit(1)->get();
        return array('min'=>(count($min)>0)?$min[0]->end_price:0,'max'=>(count($max)>0)?$max[0]->start_price:1000);
    }
    public static function calculatePriceID($id,$targetprice=null){
        $bidDate = date(Constants::DATE_FORMAT);
        $auction = Auction::findOrFail($id);
        $prices = $auction->calculatePrice($bidDate);
        $price=number_format(str_replace(",","",$prices),2,',','');
        return array(Constants::CURRENTPRICE=>$price,Constants::CLOSE=>($price<$targetprice && strtotime('Y-m-d H:i:s',time())> strtotime($auction->end))?1:0);
    }
    public static function getauctions(Request $request){
        $limit=(int)$request->limit;
        $ids=($limit==1)?$request->ids:null;
        $time=(isset($request->time))?$request->time:Constants::IN_COURSE;
        $filters=self::convertFilterSubastas($request->filters);
        $close=(isset($filters[Constants::CLOSE]))?1:0;
        $rating=(isset($filters[Constants::USER_RATING]))?1:0;
        $auctions=AuctionQuery::auctionHome($ids,$filters,$time);
        if($limit>1){
            $preciomin=(float)$filters[Constants::PRICEMIN];
            $preciomax=(float)$filters[Constants::PRICEMAX];
            foreach($auctions as $index=>$auction){
                $priceall=  self::calculatePriceID($auction->id,$auction->target_price);
                $price=(int)str_replace(',','.',$priceall['CurrentPrice']);
                $target=$priceall[Constants::CLOSE];
                $userrating=self::getUserRating($auction->batch->arrive->boat->user);
                if($price<$preciomin || $price>$preciomax || ($close==1 && $target!=1) || ($rating==1 && $userrating!=$filters[Constants::USER_RATING]) ){
                    unset($auctions[$index]);
                }
            }
            return json_encode(array(
                'view'=>view('/landing3/partials/listasubasta')->withAuctions(Constants::manualPaginate($auctions,$request->current))->with('request',$request)->with('limit',$limit)->with('timeline',$time)->render(),
                'quantity'=>count($auctions)
                ));
        }else{
            return view('/landing3/partials/auctionNoDetail')
                ->withAuction($auctions[0]);
        }
        
    }   
    public function offersAuctionFront(Request $request){
        $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
        $prices = $request->input('prices');
        $auction = Auction::findOrFail($auction_id);
        $checkuser=$this->checkIfBuyerCanBuy($auction_id,null,'offer',$auction->type);
        if($checkuser==0){
            return json_encode(array(Constants::ERROR=>'Tu usuario no puede ofertar'));
        }
        $resp  =  array();
        if ($auction->active == 0 ){
            $resp[Constants::ACTIVE_LIT]=$auction->active;
        }else{
            $price=str_replace(",","",$prices);
            DB::beginTransaction();
            $available = AuctionBackController::getAvailable($auction_id, $auction->amount);
            if ($available[Constants::AVAILABLE] > 0 ){
                $auction->offersAuction($available[Constants::AVAILABLE],$price);
                $unit = $auction->batch->product->unit;
                $caliber = $auction->batch->caliber;
                $quality = $auction->batch->quality;
                $product = $auction->batch->product->name;
                $resp[Constants::IS_NOT_AVAILABLE] = 0;
                $resp[Constants::UNIT] = trans(Constants::TRANS_UNITS.$unit);
                $resp[Constants::CALIBER] = Constants::caliber($caliber);
                $resp[Constants::QUALITY] = $quality;
                $resp[Constants::PRODUCT] = $product;
                $resp[Constants::PRODUCTID]=$auction->batch->product->id;
                $resp[Constants::AMOUNT] = $available[Constants::AVAILABLE];
                $resp[Constants::PRICE] = $price;
                $resp[Constants::OFFERSCOUNTER]=AuctionBackController::getOffersCount($auction_id);
                $resp[Constants::BIDSCOUNTER]=$available['sold'];
            }else{
                $resp[Constants::ERROR]='No es posible ofertar, No hay disponibilidad de este producto.';
            }
            $resp[Constants::ACTIVE_LIT] = $auction->active ;
            DB::commit();
        }
        $user = User::findOrFail(Auth::user()->id);
        $template = Constants::EMAIL_OFFERAUCTION;
        $seller = $auction->batch->arrive->boat->user ;
        Mail::queue($template, ['user' => $user , Constants::SELLER=> $seller, Constants::PRODUCT=> $resp] , function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
            );
            $message->subject(trans(Constants::USERS_OFFERAUCTION));
            $message->to($user->email);
        });
        $resp['success']=1;
        return json_encode($resp);
    }
    public function getUsersAuctionPrivate(Request $request){
        $ids='';
        if($request->ids!=null){
            foreach($request->ids as $id){$ids.=$id.',';}$ids=substr($ids,0,-1);
        }
        $ids=(($ids!='')?"`id` NOT IN ($ids) AND ":'');
        $val=$request->val;
        $users=User::select('id',Constants::NAME,'lastname','nickname')
                ->whereRaw(
                    "$ids `status`='approved' AND `type`='buyer' AND (`nickname` like '%$val%' OR `name` like '%$val%' OR `lastname` like '%$val%' OR CONCAT_WS(' ',`name`,`lastname`) LIKE '%$val%')"
                )->get();
        return json_encode($users);

    }
     
    public function subastasFront(Request $request){
        $buyers = User::filter(null, array(User::COMPRADOR), array(User::APROBADO));
        $boats = Boat::Select()->get();
        $auctionhome=AuctionQuery::auctionHome();
        $auctions1 = $auctionhome[Constants::IN_COURSE];
        $auctions2 = array_reverse($auctionhome[Constants::FINISHED]);
        $auctiondetails1=$this->getAuctionsDataForHome($auctions1);
        $port=(isset($auctiondetails1[Constants::PORTS]))?$auctiondetails1[Constants::PORTS]:array();
        /*
         * Retornan tanto las subastas en curso como las finalizadas
         * Buyers y Boats para los contadores del header
         * Ports para el nombre del puerto y la cantidad de subastas por puerto
         */
        $view=view('/landing3/index')
            ->withAuctions($auctions1)
            ->withAuctionsf($auctions2)
            ->withPorts($port)
            ->withBoats($boats)
            ->withBuyers($buyers);
        if($request->get('log')==1){
            $view=$view->withLog('1');
        }
        return $view;
    }
    public function listaSubastas(Request $request){
        $boats = Boat::Select()->get();
        $timeline=(isset($request->time))?$request->time:Constants::IN_COURSE;
        $params=null;
        if(isset($request->type)){
            $params['type']=$request->type;
        }
        $auctions=AuctionQuery::auctionHome(null,$params,$timeline);
        $auctiondetails1=$this->getAuctionsDataForHome($auctions);
        $prices=self::getMaxMinPrice();
        return view('/landing3/subastas')
            ->withAuctions(Constants::manualPaginate($auctions))
            ->withAuctioncounter(count($auctions))
            ->withPorts($auctiondetails1[Constants::PORTS])
            ->withBoats($boats)
            ->withusers($auctiondetails1[Constants::USERS])
            ->withCaliber($auctiondetails1[Constants::CALIBERS])
            ->withProducts($auctiondetails1[Constants::PRODUCTS])
            ->withPortId($request->input(Constants::PORT_ID))
            ->withRequest($request)
            ->withLimit(Constants::PAGINATE_NUMBER)
            ->withClose($auctiondetails1[Constants::CLOSE])
            ->withRatings($auctiondetails1[Constants::RATINGS])
            ->withQuality($auctiondetails1[Constants::QUALITY])
            ->withPrices(array('min'=> $prices['min'],'max'=>$prices['max']))
            ->withTimeline($timeline)
            ;
    }
    public function addAuction(Request $request){
        if(empty(Auth::user()->id)){
            return redirect(Constants::URL_LOGIN);
        }
        $boats=Boat::select('id',Constants::NAME)->where(Constants::USER_ID, Constants::EQUAL,Auth::user()->id)
                ->where('status', Constants::EQUAL,'approved')
                ->orderBy('name','ASC')->get();
        $ports=Ports::select()->get();
        $products= Product::select('id',Constants::NAME)->get();
        return view('landing3/auction-add-edit')
            ->with('title','| Agregar Subasta')
            ->with(Constants::BOATS,$boats)
            ->with(Constants::PORTS,$ports)
            ->with(Constants::BATCHEDIT,1)
            ->with(Constants::ARRIVEEDIT,1)
            ->with(Constants::AUCTIONEDIT,1)
            ->with(Constants::PRODUCTS,$products);
    }
    public function addAuctionNew(Request $request){
        if(empty(Auth::user()->id)){
            return redirect(Constants::URL_LOGIN);
        }
        $boats=Boat::select('id',Constants::NAME)->where(Constants::USER_ID, Constants::EQUAL,Auth::user()->id)
                ->where('status', Constants::EQUAL,'approved')
                ->orderBy('name','ASC')->get();
        $ports=Ports::select()->get();
        $products= Product::select('id',Constants::NAME)->get();
        return view('landing3/auction-add-edit')
            ->with('title','| Agregar Subasta')
            ->with(Constants::BOATS,$boats)
            ->with(Constants::PORTS,$ports)
            ->with(Constants::BATCHEDIT,1)
            ->with(Constants::ARRIVEEDIT,1)
            ->with(Constants::AUCTIONEDIT,1)
            ->with(Constants::PRODUCTS,$products);
    }
    public function editAuction($auction_id){
        if(empty(Auth::user()->id)){
            return redirect(Constants::URL_LOGIN);
        }
        $boats=Boat::select('id',Constants::NAME)
            ->where('status', Constants::EQUAL,'approved')
            ->where(Constants::USER_ID, Constants::EQUAL,Auth::user()->id)->get();
        $ports=Ports::select()->get();
        $products= Product::select('id',Constants::NAME,'unit')->get();
        $auction=AuctionQuery::auctionHome(null,array(Constants::AUCTIONID=>$auction_id),'all')[0];
        $contbatch=count(Auction::select()->where('batch_id',Constants::EQUAL,$auction->batch_id)->get());
        $contarrive=count(Batch::select()->where(Constants::ARRIVE_ID,Constants::EQUAL,$auction->batch->arrive_id)->get());
        $auctiontime=($auction->timeline==Constants::FUTURE)?1:0;
        return view('landing3/auction-add-edit')
            ->with(Constants::AUCTION_ORIGIN,$auction)
            ->with(Constants::BATCHEDIT,(($contbatch>1 || $auctiontime==0)?0:1))
            ->with(Constants::ARRIVEEDIT,(($contbatch>1 || $contarrive>1 || $auctiontime==0)?0:1))
            ->with(Constants::AUCTIONEDIT,$auctiontime)
            ->with(Constants::BOATS,$boats)
            ->with(Constants::PORTS,$ports)
            ->with(Constants::PRODUCTS,$products);
    }
    public function replicateAuction($auction_id){
        if(empty(Auth::user()->id)){
            return redirect(Constants::URL_LOGIN);
        }
        $auction=AuctionQuery::auctionHome(null,array(Constants::AUCTIONID=>$auction_id),'all')[0];
        $boats=Boat::select('id',Constants::NAME)
            ->where('status', Constants::EQUAL,'approved')
            ->where(Constants::USER_ID, Constants::EQUAL,Auth::user()->id)->get();
        $ports=Ports::select()->get();
        $products= Product::select('id',Constants::NAME,'unit')->get();
        return view('landing3/auction-add-edit')
            ->with(Constants::AUCTION_ORIGIN,$auction)
            ->with(Constants::BATCHEDIT,0)
            ->with(Constants::ARRIVEEDIT,0)
            ->with(Constants::AUCTIONEDIT,'1')
            ->with(Constants::REPLICATE,'1')
            ->with(Constants::BOATS,$boats)
            ->with(Constants::PORTS,$ports)
            ->with(Constants::PRODUCTS,$products);
    }
    
    
    public function offerList(Request $request){
        $auctions=AuctionQuery::auctionHome(null,array('type'=>'mine'),Constants::FINISHED);
        $this->authorize('viewOperations', $auctions);
        $offers=array();
        foreach($auctions as $auction){
            $auction->code=self::getAuctionCode($auction->correlative,$auction->created_at);
            $offers[$auction->id]= self::getOffers($auction->id);
            $max[$auction->id] = self::getOffers($auction->id)->first();
            $available[$auction->id] = AuctionBackController::getAvailable($auction->id, $auction->amount);
        }
        return view(Constants::LANDING3_OFFERS)
            ->with('auctions',$auctions)
            ->with('offers',$offers)
            ->with('max',$max)
            ->with('available',$available);
    }

}

