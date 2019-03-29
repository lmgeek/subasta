<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateAuctionRequest;
use App\Http\Requests\SellerQualifyRequest;
use App\Http\Requests\UpdateAuctionRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProcessBidRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuctionBackController;
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

class AuctionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('seeAuctions', Auction::class);

        $status = $request->get(Constants::STATUS,Constants::IN_COURSE);
		$product = $request->get(Constants::PRODUCT,null);
		$seller = $request->get(Constants::SELLER,null);
		$boat = $request->get('boat',null);
		$type = $request->get('type',null);

		if ($type == Constants::AUCTION_PRIVATE)
		{	$user = Auth::user();
			$auctions = Auction::auctionPrivate($user->id , $status);
		}else{
			$auctions = AuctionQuery::filterAndPaginate($status,$product,$seller,$boat);
		}
		$products = Product::select()->get();
		$sellers = User::filter(null, array(User::VENDEDOR), array(User::APROBADO));
		$boats = Boat::Select()->get();

		$userRating =  array();
		foreach($auctions as $a)
		{
			$porc = 0;
			$user = $a->batch->arrive->boat->user;
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
        return view('auction.index',compact(Constants::AUCTIONS,Constants::STATUS,Constants::PRODUCTS,Constants::SELLERS,Constants::REQUEST,Constants::BOATS,Constants::USER_RATING,'type'));
    }
    
    
    
    
	public function makeBid(Request $request)
	 {

         $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
         $amount = $request->input(Constants::AMOUNT);
         $auction=AuctionQuery::auctionHome(null,array(Constants::AUCTIONID=>$auction_id),'all')[0];
         $this->authorize(Constants::MAKE_BID, $auction);
         $checkuser= \App\Http\Controllers\AuctionFrontController::checkIfBuyerCanBuy($auction_id,$amount,'bid');
         if($checkuser['success']!=1){
             return json_encode(array(Constants::ERROR=>$checkuser['error']));
         }
         if(empty($request->input(Constants::PRICE))){
             $price = number_format($auction->calculatePrice(date(Constants::DATE_FORMAT)), 2,'.','');
         }else{
             $price= str_replace(',', '.',$request->input(Constants::PRICE));
         }

		$resp  =  array();

		if ($auction->active == 0 )
		{
			$resp[Constants::ACTIVE_LIT] = $auction->active ;

		}else{
            $availabilityboth= AuctionBackController::getAvailable($auction_id,$auction->amount);
            $availability=$availabilityboth[Constants::AVAILABLE];
            $bidscounter=$availabilityboth['sold']+1;
            DB::beginTransaction();
				if ($amount > 0 && $amount <= $availability  )
				{
                    $auction->makeBid($amount,$price);
					$lastbid=Bid::Select('id')->orderBy('id','desc')->limit(1)->get();
					$product = $auction->product['name'];
                    $amounttotal=$auction->amount;
                    $targetamount=($amounttotal*0.75);
                    $hot=(($availability-$amount)<$targetamount)?1:0;
					$resp[Constants::IS_NOT_AVAILABLE] = 0;
                    $resp[Constants::AVAILABILITY] = $availability-$amount;
                    $resp['bidid']=$lastbid[0]['id'];
                    $resp[Constants::PRODUCTID]=$auction->product['idproduct'];
					$resp[Constants::PRODUCT] = ucfirst($product);
					$resp[Constants::AMOUNT] = $amount;
					$resp[Constants::PRICE] = $price;
                    $resp[Constants::CALIBER] = Constants::caliber($auction->product['caliber']);
					$resp['totalAmount']=$amounttotal;
                    $resp['bidscounter']=$bidscounter;
                    $resp['offerscounter']= AuctionBackController::getOffersCount($auction_id);
                    $resp['hot']=$hot;
				}else{
					$resp[Constants::IS_NOT_AVAILABLE] = 1;
					$resp[Constants::AVAILABILITY] = $availability-$amount;
					$unit = $auction->batch->product->unit;
					$product = $auction->batch->product->name;
					$resp['unit'] = trans(Constants::TRANS_UNITS.$unit);
					$resp[Constants::PRODUCT] = $product;

				}


				$resp[Constants::ACTIVE_LIT] = $auction->active ;
			DB::commit();
		}

		return json_encode($resp);


	 }



    public function calculatePrice(Request $request)
    {
        $data = array();
        $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
        $bidDate = date(Constants::DATE_FORMAT);
        $auction = Auction::findOrFail($auction_id);
        $prices = $auction->calculatePrice($bidDate);
        $price = str_replace(',', '', $prices);
        $available=AuctionBackController::getAvailable($auction_id,$auction->amount);
        $amount=$auction->amount;
        $targetamount=($amount*0.75);
        $cantventas=$available[Constants::AVAILABLE];
        $hot=($cantventas<$targetamount)?1:0;
        if($request->get("i") && $request->get('i')=='c'){
            $targetprice=$auction->target_price;
            $close=($price<$targetprice)?1:0;
            $time = round(microtime(true) * 1000);
            $data['id'] = $auction_id;
            $data['CLOSE'] = $close;
            $data['end'] = $auction->end;
            $data[Constants::AVAILABILITY] = $available[Constants::AVAILABLE];
            $data['currenttime'] = $time;
            $data[Constants::PRICE] =number_format($price,2,'.','');
            $data[Constants::AVAILABLE] = $available[Constants::AVAILABLE];
            $data[Constants::AMOUNT]=$amount;
            $data['hot']=$hot;
            $data['offers']=AuctionBackController::getOffersCount($auction_id);
            $data['bids']=$available['sold'];
            return json_encode($data);
        }else{
            $data[Constants::PRICE] = $price;
            $data[Constants::AVAILABLE] = $available[Constants::AVAILABLE];
            return $data;
        }

    }
    public function operations(Request $request, $auction_id)
    {
        setlocale(LC_MONETARY, Constants::EN_US);

        $this->autoOffersBid($request, $auction_id);

        $auction = Auction::findOrFail($auction_id);
        $this->authorize(Constants::VIEWOPERATIONS, $auction);
        $request->session()->put('url.intended', '/auction/operations/'.$auction_id);
        return view('auction.operations',compact(Constants::AUCTION));
    }


    public function process($bid_id)
    {
        $bid = Bid::findOrFail($bid_id);
        $this->authorize('qualifyBid', $bid);

        return view('auction.process',compact('bid'));
    }
    public function saveProcess(ProcessBidRequest $request)
    {
        $bid = Bid::findOrFail($request->input('id'));
        $this->authorize('qualifyBid', $bid);

        $bid->status = $request->input('concretada');
        if($request->input('concretada') == Constants::NO_CONCRETADA){
            $bid->auction->cancelBid($bid);
            $bid->reason = $request->input('motivo_no_concretada');
        }
        $bid->user_calification = $request->input(Constants::INPUT_CALIFICACION);
        $bid->user_calification_comments = $request->input(Constants::INPUT_COMENTARIOS_CALIFICACION);
        $bid->save();

		$user_id = $bid->user_id;
		$this->incrementRatingUser($bid , $user_id);


		if ($request->input(Constants::INPUT_CALIFICACION) == Constants::CALIFICACION_NEGATIVA)
		{
			$internals = User::getInternals(array(User::APROBADO));
			foreach($internals as $i)
			{
				$user = User::findOrFail($user_id);
				$template = 'emails.userqualifynegative';
				$seller = $bid->auction->batch->arrive->boat->user ;
				Mail::queue($template, ['bid'=>$bid, 'user' => $user , Constants::INPUT_COMENTARIOS_CALIFICACION=> $request->input(Constants::INPUT_COMENTARIOS_CALIFICACION) , Constants::SELLER=> $seller , 'type' => User::COMPRADOR ] , function ($message) use ($i) {
					$message->from(
						env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
						env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
					);
					$message->subject(trans('auction.qualification_negative'));
					$message->to($i->email);
				});
			}

		}





        //Se utilizo este forma ya que el formulario puede ser llamado desde 2 listados distintos.
        // Y asi poder devolverlo al cual estaba
        return redirect()->intended('/sellerAuction');

    }

    public function saveQualifyBid(SellerQualifyRequest $request )
    {
        $bid = Bid::findOrFail($request->input('id'));
        $bid->seller_calification = $request->input(Constants::INPUT_CALIFICACION);
        $bid->seller_calification_comments = $request->input(Constants::INPUT_COMENTARIOS_CALIFICACION);
        $bid->save();

		$user_id = $bid->auction->batch->arrive->boat->user->id;
		$this->incrementRatingUser($bid , $user_id);

		if ($request->input(Constants::INPUT_CALIFICACION) == Constants::CALIFICACION_NEGATIVA)
		{
			$internals = User::getInternals(array(User::APROBADO));
			foreach($internals as $i)
			{
				$user = User::findOrFail($bid->user_id);
				$template = 'emails.userqualifynegative';
				$seller = $bid->auction->batch->arrive->boat->user ;
				Mail::queue($template, ['bid'=>$bid,'user' => $user , Constants::INPUT_COMENTARIOS_CALIFICACION=> $request->input(Constants::INPUT_COMENTARIOS_CALIFICACION) , Constants::SELLER=> $seller , 'type' => Constants::VENDEDOR ] , function ($message) use ($i) {
					$message->from(
						env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
						env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
					);
					$message->subject(trans('auction.qualification_negative'));
					$message->to($i->email);
				});
			}

		}


        return redirect('/bids');
    }
	public function incrementRatingUser($bid , $user_id)
	{

		$userRating = UserRating::where(Constants::USER_ID , $user_id);

		if ($userRating->count() == 0)
		{
			$userRating = new UserRating();
			$userRating->user_id  = $user_id;
			$userRating->positive = 0;
			$userRating->negative = 0;
			$userRating->neutral  = 0;
			$userRating->save();
		}

		if ($bid->seller_calification == Constants::CALIFICACION_POSITIVA)
		{
			$userRating->increment(Constants::CALIFICACION_POSITIVA, 1);
		}else if ($bid->seller_calification == Constants::CALIFICACION_NEGATIVA)
		{
			$userRating->increment(Constants::CALIFICACION_NEGATIVA, 1);
		}else if ($bid->seller_calification == Constants::CALIFICACION_NEUTRAL)
		{
			$userRating->increment(Constants::CALIFICACION_NEUTRAL, 1);
		}
	}



    /* 20 hasta aqui */




/** NEEW **/



    /**
     * @param Request $request
     * @return mixed
     */
    public function offersAuction(Request $request)
    {
        $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
        $prices = $request->input('prices');
        if ($prices === 0){
            return;
        }
        $auction = Auction::findOrFail($auction_id);
        $this->authorize(Constants::MAKE_BID, $auction);

        if ($auction->type == Constants::AUCTION_PRIVATE )
        {
            $this->authorize('isInvited', $auction);
        }
        $resp  =  array();

        if ($auction->active == 0 )
        {
            $resp[Constants::ACTIVE_LIT] = $auction->active ;

        }else{
            $price = str_replace(",",".",$prices);
            DB::beginTransaction();
            $available = AuctionBackController::getAvailable($auction_id, $auction->amount);

            if ($available[Constants::AVAILABLE] > 0 )
            {
                $auction->offersAuction($available[Constants::AVAILABLE],$price);
                $unit = $auction->batch->product->unit;
                $caliber = $auction->batch->caliber;
                $quality = $auction->batch->quality;
                $product = $auction->batch->product->name;
                $resp[Constants::IS_NOT_AVAILABLE] = 0;
                $resp['unit'] = trans(Constants::TRANS_UNITS.$unit);
                $resp[Constants::CALIBER] = $caliber;
                $resp[Constants::QUALITY] = $quality;
                $resp[Constants::PRODUCT] = $product;
                $resp[Constants::AMOUNT] = $available[Constants::AVAILABLE];
                $resp[Constants::PRICE] = $price;

            }else{
                return Redirect::back()->with(Constants::ERROR,'No es posible ofertar, no hay disponibilidad de este producto');
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

        return Redirect::back()->with('success','Su oferta se registro satisfactoriamente. Se ha enviado un correo con la información detallada');

    }




    /**
     * @param Request $request
     * @param $auction_id
     * @return string|void
     */
    public function offersToBid(Request $request, $auction_id)
    {

        setlocale(LC_MONETARY, Constants::EN_US);
        $auction = Auction::findOrFail($auction_id);
        $this->authorize('viewOperations', $auction);
        $request->session()->put('url.intended', '/auction/offers/'.$auction_id);
        $available = AuctionBackController::getAvailable($auction_id, $auction->amount);
        $offers = $this->getOffers($auction_id);
        $count = count($offers);
        foreach ($offers as $offer) {
            // Verifico la fecha de la subasta
            if ($auction->end >= date(Constants::DATE_FORMAT)){
                return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">La subasta no ha culminado</h1>');
            }
            //verifica que el precio ofertado sea mayor e igual al de la subasta terminada
            if ($offer->price >= $offer->end_price && $offer->status == Constants::PENDIENTE){

                //registramos la compra a la mejor opc de compra
                $offerForSale = $this->offerForSale($auction, $offer);
                if ($offerForSale){
                    return $this->getOffers($auction_id);
                }

            }
        }

        if ($available[Constants::AVAILABLE] == 0 || $count>0){
            return $this->getOffers($auction_id);
        }else{
            return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">No hay ofertas realizadas<br>Disponibles: '. $available[Constants::AVAILABLE].'</h1>');
        }
    }

    public function autoOffersBid(Request $request, $auction_id)
    {

        setlocale(LC_MONETARY, Constants::EN_US);
        $auction = Auction::findOrFail($auction_id);
        $available = AuctionBackController::getAvailable($auction_id, $auction->amount);
        $offers = $this->getOffers($auction_id);

        foreach ($offers as $offer) {

            // Verifico la fecha de la subasta
            if ($auction->end >= date(Constants::DATE_FORMAT)){
                return;
            }
            //verifica que el precio ofertado sea mayor e igual al de la subasta terminada
            if ($offer->price >= $offer->end_price && $offer->status == Offers::PENDIENTE){
                    //registramos la compra a la mejor opc de compra
                    return $this->offerForSale($auction, $offer);
            }
        }

        if ($available['available'] == 0){
            $offer_id = null;
            $this->declineOffers($auction_id,$offer_id,$request);
            return;
        }

        /*if (count($offers) > 0) {
            return;
        }else {
            return;
        }*/

    }

    public function autoOffersToBid(Request $request){
        setlocale(LC_MONETARY, Constants::EN_US);
        $auctions = Auction::get();
        foreach ($auctions as $auction){
            $this->autoOffersBid($request,$auction->id);
        }
//        return Redirect::to('/');
    }





    public function offerForSale($auction, $offer)
    {
        $auction_id = $auction->id;
        $prices = $offer->price;
        $request = null;
        $price = str_replace(",",".",$prices);
        $available = AuctionBackController::getAvailable($auction_id, $auction->amount);

        if ($available[Constants::AVAILABLE] > 0){
            if ($price < $auction->end_price){
                return;
            }

//Guardo la venta
            $this->bid = new Bid();
            $this->bid->user_id = $offer->user_id;
            $this->bid->auction_id = $auction_id;
            $this->bid->amount = $available[Constants::AVAILABLE];
            $this->bid->price = $prices;
            $this->bid->status = Constants::PENDIENTE;
            $this->bid->bid_date = date(Constants::DATE_FORMAT);
            $this->bid->offer_id = $offer->id;
            $this->bid->save();

            $offers = $this->getOffers($auction_id);
            foreach ($offers as $o){
                if ($o->id == $offer->id){
                    //Update status oferta
                    $this->offers = Offers::findOrFail($offer->id);
                    $this->offers->auction_id = $auction_id;
                    $this->offers->status = Offers::ACEPTADA;
                    $this->offers->save();
                } else {
                    $this->declineOffers($auction_id,$o->id,$request);
                }

            }
            AuctionBackController::emailOfferBid($auction,$available,$offer);
            return $this->getOffers($auction_id);
        } else {
            $offer = null;
            $this->declineOffers($auction_id,$offer,$request);
        }

    }

    public static function getOffers($auction_id)
    {
        return Offers::Select(
            'auctions_offers.id',
            'auctions_offers.auction_id',
            'auctions_offers.price',
            'auctions_offers.status',
            'auctions.end_price',
            'auctions.end AS FinSubasta',
            'auctions_offers.created_at',
            'product_detail.caliber',
            'batches.quality',
            'products.name AS Producto',
            'auctions_offers.user_id'
        )
            ->join(Constants::AUCTIONS,Constants::AUCTIONS_ID,'=',Constants::INPUT_AUCTION_ID)
            ->join(Constants::BATCHES,'batches.id','=',Constants::AUCTIONS_BATCH_ID)
            ->join('product_detail','product_detail.id','=','batches.product_detail_id')
            ->join(Constants::PRODUCTS,'products.id','=','product_detail.product_id')
            ->join(Constants::USERS,Constants::USERS_ID,'=','auctions_offers.user_id')
            ->where('auctions_offers.auction_id','=',$auction_id)
            ->orderBy('auctions_offers.price','desc')
            ->orderBy('auctions_offers.created_at','asc')
            ->get();
    }




    //Declinar de forma masiva las ofertas

    /**
     * @param $auction_id
     * @return bool
     */
    public function declineOffers($auction_id,$offer_id = null,Request $request = null)
    {
        $auction = Auction::findOrFail($auction_id);
        $offers = $this->getOffers($auction_id);
        $available = AuctionBackController::getAvailable($auction_id, $auction->amount);
        if ($offer_id == null){
            foreach ($offers as $o){
                $this->offers = Offers::findOrFail($o->id);
                if ($this->offers->status == Offers::PENDIENTE){
                    $this->offers->auction_id = $auction_id;
                    $this->offers->status = Offers::NO_ACEPTADA;
                    $this->offers->save();
                }
            }
        } else {
            if ($request == null){
                foreach ($offers as $o){
                    $this->offers = Offers::findOrFail($o->id);
                    if ($this->offers->status == Offers::PENDIENTE) {
                        $this->offers->auction_id = $auction_id;
                        $this->offers->status = Offers::NO_ACEPTADA;
                        $this->offers->save();
                    }
                }
            } else {
                foreach ($offers as $o){
                    if ($o->id != $offer_id) {
                        $this->offers = Offers::findOrFail($o->id);
                        if ($this->offers->status == Offers::PENDIENTE) {
                            $this->offers->auction_id = $auction_id;
                            $this->offers->status = Offers::NO_ACEPTADA;
                            $this->offers->save();
                        }
                    } else {
                        if ($available[Constants::AVAILABLE] > 0){
                            $this->offers = Offers::findOrFail($o->id);
                            $this->offers->auction_id = $auction_id;
                            $this->offers->status = Offers::ACEPTADA;
                            $this->offers->save();

                            //guardo la venta

                            $this->bid = new Bid();
                            $this->bid->user_id = $o->user_id;
                            $this->bid->auction_id = $auction_id;
                            $this->bid->amount = $available[Constants::AVAILABLE];
                            $this->bid->price = $o->price;
                            $this->bid->status = Constants::PENDIENTE;
                            $this->bid->bid_date = date(Constants::DATE_FORMAT);
                            $this->bid->offer_id = $o->id;
                            $this->bid->save();

                            //send email
                            AuctionBackController::emailOfferBid($auction,$available,$o);
                        } else {
                            $this->offers = Offers::findOrFail($o->id);
                            if ($this->offers->status == Offers::PENDIENTE) {
                                $this->offers->auction_id = $auction_id;
                                $this->offers->status = Offers::NO_ACEPTADA;
                                $this->offers->save();
                            }
                        }
                    }
                }
            }
        }
        return redirect()->to('/ofertas');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * TODO: rechaza una oferta especifica
     */
    public function declineOffer($id){
        $this->offers = Offers::findOrFail($id);
        if ($this->offers->status == Offers::PENDIENTE){
            $this->offers->status = Offers::NO_ACEPTADA;
            $this->offers->save();
        }
        return redirect()->to('/ofertas');
    }


    

    
    
    
    /* Lo que quizas se puede ir */
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $this->authorize('seeSellerAuction',Auth::user()->id);
        $batch = Batch::findOrFail($id);
        $this->authorize('createAuction', $batch);
		$buyers = User::filter(null, array(User::COMPRADOR), array(User::APROBADO));
		$array_buyers = [];
		foreach ($buyers as $buyer){
		    $array_buyers[$buyer->id] = $buyer->full_name;
        }
        $buyers = $array_buyers;
        return view('auction.create',compact('buyers'))->with(Constants::BATCH,$batch);
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
        $auction = Auction::findOrFail($id);
        $this->authorize('editAuction', $auction);

        return view('auction.edit')->with(Constants::AUCTION,$auction);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAuctionRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAuctionRequest $request)
    {
        $batch = Batch::findOrFail($request->input(Constants::BATCH));
        $this->authorize('createAuction', $batch);

        $batch->assignForAuction($request->input(Constants::AMOUNT));

        $startDate = Carbon::createFromFormat(Constants::DATE_FORMAT_INPUT, $request->input('fechaInicio'));
        $endDate = Carbon::createFromFormat(Constants::DATE_FORMAT_INPUT, $request->input('fechaFin'));
        $Date = Carbon::createFromFormat(Constants::DATE_FORMAT_INPUT, $request->input('fechaTentativa'));
        $startprice=$request->input("startPrice");
        $endprice=$request->input(Constants::INPUT_END_PRICE);
        $auction  = new Auction();
        $rand=rand(1,7)/100;
        $targetprice=$endprice+(($startprice-$endprice)*$rand);
        $auction->batch_id = $request->input(Constants::BATCH);
        $auction->correlative= \App\Http\Controllers\AuctionFrontController::calculateAuctionCode();
        $auction->start = $startDate->format(Constants::DATE_FORMAT);
        $auction->start_price = $startprice;
        $auction->end = $endDate->format(Constants::DATE_FORMAT);
        $auction->end_price = $endprice;
        $auction->target_price = $targetprice;
        $auction->interval = 1;
        $auction->amount = $request->input(Constants::AMOUNT);
		$auction->type = $request->input(Constants::TIPOSUBASTA);
		$auction->description = $request->input(Constants::DESCRI);
        $auction->tentative_date = $Date->format(Constants::DATE_FORMAT);
		$auction->save();
		if ($request->input('Constants::TIPOSUBASTA') == Constants::AUCTION_PRIVATE )
		{
			$sInvited  = $request->input('invitados');

			foreach($sInvited as $i)
			{
				$auctionInvited = new AuctionInvited();
				$auctionInvited->auction_id = $auction->id;
				$auctionInvited->user_id = $i;
				$auctionInvited->save();

				$user = User::findOrFail($i);
				$template = 'emails.userinvited';
				$seller = $auction->batch->arrive->boat->user ;
				Mail::queue($template, ['user' => $user , Constants::SELLER=> $seller] , function ($message) use ($user) {
					$message->from(
						env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
						env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
					);
					$message->subject(trans('users.private_auction'));
					$message->to($user->email);
				});

			}



		}




        return redirect('/sellerbatch?e=created&t=auction&id='.$auction->id.'&ex='.urlencode('Product ID: '.$batch->product_id.', Name: '.$auction->batch->product->name.' '.Constants::caliber($auction->batch->caliber).'.  Quantity: '.$request->input(Constants::AMOUNT)));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuctionRequest $request, $id)
    {
        $auction = Auction::findOrFail($request->input('id'));
        $this->authorize('editAuction', $auction);

        $startDate = Carbon::createFromFormat(Constants::DATE_FORMAT_INPUT, $request->input('fechaInicio'));
        $endDate = Carbon::createFromFormat(Constants::DATE_FORMAT_INPUT, $request->input('fechaFin'));
        $tentativaDate = Carbon::createFromFormat(Constants::DATE_FORMAT_INPUT, $request->input('fechaTentativa'));

        $auction->start = $startDate;
        $auction->end = $endDate;
        $auction->tentative_date = $tentativaDate;
        $auction->start_price = $request->input('startPrice');
        $endprice=$request->input('endPrice');
        if($endprice!=$auction->end_price){
            $rand=rand(1,7)/100;
            $targetprice=($endprice*$rand)+$endprice;
            $auction->target_price=$targetprice;
        }
        $auction->end_price = $endprice;
        $auction->interval = $request->input('intervalo');
        $auction->description = $request->input('descri');
        $oldAmount = $auction->amount;
        $newAmount = $request->input(Constants::AMOUNT);
        $auction->amount = $newAmount;

        $auction->batch->status->remainder += ( $oldAmount -$newAmount );
        $auction->batch->status->save();

        $auction->save();

        return redirect('/sellerAuction');

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

