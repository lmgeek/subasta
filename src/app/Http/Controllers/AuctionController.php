<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateAuctionRequest;
use App\Http\Requests\SellerQualifyRequest;
use App\Http\Requests\UpdateAuctionRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProcessBidRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\AuctionInvited;
use App\UserRating;
use App\Constants;
use Carbon\Carbon;
use App\Auction;
use App\Product;
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

        $status = $request->get(Constants::STATUS,Constants::IN_CURSE);
		$product = $request->get(Constants::PRODUCT,null);
		$seller = $request->get(Constants::SELLER,null);
		$boat = $request->get('boat',null);
		$type = $request->get('type',null);

		if ($type == Constants::AUCTION_PRIVATE)
		{	$user = Auth::user();
			$auctions = Auction::auctionPrivate($user->id , $status);
		}else{
			$auctions = Auction::filterAndPaginate($status,$product,$seller,$boat);
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
        return view('auction.index',compact(Constants::AUCTIONS,Constants::STATUS,Constants::PRODUCTS,Constants::SELLERS,'request',Constants::BOATS,Constants::USER_RATING,'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
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
        $startprice=$request->input("startPrice");
        $endprice=$request->input(Constants::INPUT_END_PRICE);
        $auction  = new Auction();
        $rand=rand(1,7)/100;
        $targetprice=$endprice+(($startprice-$endprice)*$rand);
        $auction->batch_id = $request->input(Constants::BATCH);
        $auction->correlative=self::calculateAuctionCode();
        $auction->start = $startDate->format(Constants::DATE_FORMAT);
        $auction->start_price = $startprice;
        $auction->end = $endDate->format(Constants::DATE_FORMAT);
        $auction->end_price = $endprice;
        $auction->target_price = $targetprice;
        $auction->interval = 1;
        $auction->amount = $request->input(Constants::AMOUNT);
		$auction->type = $request->input('tipoSubasta');
		$auction->description = $request->input('descri');
		$auction->save();
		if ($request->input('tipoSubasta') == Constants::AUCTION_PRIVATE )
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
    public function storeAuction(Request $request){
        if(empty($request->id)){
            $request->request->add(['id'=>SellerBoatsController::storeArrive($request)]);
        }
        if(empty($request->batchid)){
            $request->request->add(['batch'=>SellerBoatsController::storeBatch($request)]);
        }
        
        self::store($request);
    }
    public function calculatePrice(Request $request)
    {
        $data = array();
        $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
        $bidDate = date(Constants::DATE_FORMAT);
        $auction = Auction::findOrFail($auction_id);
        $prices = $auction->calculatePrice($bidDate);
        $price = str_replace(",","",$prices);
        $available=$this->getAvailable($auction_id,$auction->amount);
        $amount=$auction->amount;
        $targetamount=($amount*0.75);
        $cantventas=$available[Constants::AVAILABLE];
        $hot=($cantventas<$targetamount)?1:0;
        if($request->get("i") && $request->get('i')=='c'){
            $targetprice=$auction->target_price;
            $close=($price<$targetprice)?1:0;
            $time = round(microtime(true) * 1000);
            $data['id'] = $auction_id;
            $data[Constants::CLOSE] = $close;
            $data['end'] = $auction->end;
            $data[Constants::AVAILABILITY] = $available[Constants::AVAILABLE];
            $data['currenttime'] = $time;
            $data[Constants::PRICE] = number_format(str_replace(",","",$price),2,',','');
            $data[Constants::AVAILABLE] = $available[Constants::AVAILABLE];
            $data[Constants::AMOUNT]=$amount;
            $data['hot']=$hot;
            $data['offers']=$this->getOffersCount($auction_id);
            $data['bids']=$available['sold'];
            return json_encode($data);
        }else{
            $data[Constants::PRICE] = $price;
            $data[Constants::AVAILABLE] = $available[Constants::AVAILABLE];
            return $data;
        }

    }
    public static function calculatePriceID($id,$targetprice=null)
    {
        $bidDate = date(Constants::DATE_FORMAT);
        $auction = Auction::findOrFail($id);
        $prices = $auction->calculatePrice($bidDate);
        $price=number_format(str_replace(",","",$prices),2,',','');
        return array('CurrentPrice'=>$price,'Close'=>($price<$targetprice)?1:0);
    }

    public static function getOffersCount($id){
        $offers=Offers::Select()->where(Constants::INPUT_AUCTION_ID,'=',$id)->get();
        return count($offers);
    }

    public function checkIfBuyerCanBuy($id,$amount,$type="bid",$privacy='public'){
        if(empty(Auth::user()->id)){
            return 0;
        }
        if(Auth::user()->type!='buyer'){
            return 0;
        }
        if(Auth::user()->status!='approved'){
            return 0;
        }
        if($privacy=='private'){
            $auction = Auction::select('*')
                ->join('auctions_invites', Constants::AUCTIONS_ID, '=', 'auctions_invites.auction_id')
                ->where('auctions.type', '=', 'private')
                ->where('auctions_invites.user_id', '=', Auth::user()->id)
                ->where(Constants::AUCTIONS_ID,'=', $id);
            if(count($auction)==0){
                return 0;
            }
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
	 public function makeBid(Request $request)
	 {

         $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
         $amount = $request->input(Constants::AMOUNT);
         $auction = Auction::findOrFail($auction_id);
         $this->authorize(Constants::MAKE_BID, $auction);
         $checkuser=$this->checkIfBuyerCanBuy($auction_id,$amount,'bid',$auction->type);
         if($checkuser==0){
             return json_encode(array(Constants::ERROR=>'Tu usuario no puede comprar'));
         }
         if(empty($request->input(Constants::PRICE))){
             $bidDate = date(Constants::DATE_FORMAT);
             $prices = $auction->calculatePrice($bidDate);
             $price = str_replace(",","",$prices);
         }else{
             $price=$request->input(Constants::PRICE);
         }

        //if($this->checkIfBuyercanBuy($amount*$price)==false){return json_encode(array('limited'=>1));}
		$resp  =  array();

		if ($auction->active == 0 )
		{
			$resp[Constants::ACTIVE_LIT] = $auction->active ;

		}else{
            $availabilityboth=$this->getAvailable($auction_id,$auction->amount);
            $availability=$availabilityboth[Constants::AVAILABLE];
            $bidscounter=$availabilityboth['sold']+1;
            DB::beginTransaction();
				if ($amount > 0 && $amount <= $availability  )
				{

					$auction->makeBid($amount,$price);
					$lastbid=Bid::Select('id')->orderBy('id','desc')->limit(1)->get();
					$unit = $auction->batch->product->unit;
					$product = $auction->batch->product->name;
                    $amounttotal=$auction->amount;
                    $targetamount=($amounttotal*0.75);
                    $hot=(($availability-$amount)<$targetamount)?1:0;
					$resp[Constants::IS_NOT_AVAILABLE] = 0;
                    $resp[Constants::AVAILABILITY] = $availability-$amount;
					$resp['unit'] = trans(Constants::TRANS_UNITS.$unit);
                    $resp['bidid']=$lastbid[0]['id'];
                    $resp['productid']=$auction->batch->product->id;
					$resp[Constants::PRODUCT] = ucfirst($product);
					$resp[Constants::AMOUNT] = $amount;
					$resp[Constants::PRICE] = $price;
                    $resp[Constants::CALIBER] = Constants::caliber($auction->batch->caliber);
					$resp['totalAmount']=$amounttotal;
                    $resp['bidscounter']=$bidscounter;
                    $resp['offerscounter']=$this->getOffersCount($auction_id);
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
    public static function calculateAuctionCode(){
        $auctions=Auction::AuctionsQueryBuilder(array('sellerid'=>Auth::user()->id));
        $counter=1;$highest=0;
        foreach($auctions as $auction){
            $dateformatted=date('md',strtotime($auction->created_at));
            if($dateformatted==date('md')){
                $counter++;
                $highest=$auction->correlative;
            }
        }
        return ($highest>$counter)?$highest:$counter;
    }
    public static function getAuctionCode($correlative,$created_at){
        if($correlative<10){
            $correlative=(string)"00$correlative";
        }elseif($correlative<100){
            $correlative=(string)"0$correlative";
        }
        return 'SU-'.date('ym',strtotime($created_at)).$correlative;
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

        $auction->start = $startDate;
        $auction->end = $endDate;
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


    public function operations(Request $request, $auction_id)
    {
        setlocale(LC_MONETARY, 'en_US');

        $auction = Auction::findOrFail($auction_id);
        $this->authorize('viewOperations', $auction);
        $request->session()->put('url.intended', '/auction/operations/'.$auction_id);
        return view('auction.operations',compact(Constants::AUCTION));
    }


	 public function buyerBid(Request $request)
	 {
		$user = Auth::user();
		if (Auth::user()->type == \App\User::COMPRADOR){
            $this->authorize('seeMyBids', Bid::class);
			$bids = Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->paginate();
			return view('bid.index',compact('bids'));
		} else {
			return redirect('home');
		}

	 }

	 public function deactivate($auction_id)
	 {
		 $now = strtotime(date("Y-m-d H:i:s"));
		 $auction = Auction::findOrFail($auction_id);
		 $this->authorize('isMyAuction',$auction);
		 $auction->active = Constants::INACTIVE;

		 $start = strtotime($auction->start);
		 $end = strtotime($auction->end);

		 $auction->save();

		 if ( ($start  > $now) ||  ($start < $now && $end > $now) )
		 {

			$notsold = $auction->amount -  $auction->getAuctionBids();
			$auction->batch->status->assigned_auction -= $notsold ;
			$auction->batch->status->remainder += $notsold;
			$auction->batch->status->save();
		 }

		 if ( count($auction->bids) == 0 )
		 {
			$auction->delete();
		 }


		 return redirect('sellerAuction');


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

    public function qualifyBid($bid_id)
    {
        $bid = Bid::findOrFail($bid_id);
        return view('bid.qualify',compact('bid'));
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

		$userRating = UserRating::where('user_id' , $user_id);

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
			$userRating->increment('positive', 1);
		}else if ($bid->seller_calification == Constants::CALIFICACION_NEGATIVA)
		{
			$userRating->increment('negative', 1);
		}else if ($bid->seller_calification == Constants::CALIFICACION_NEUTRAL)
		{
			$userRating->increment('neutral', 1);
		}
	}

	public function export($auction_id)
	{
		$auction = Auction::findOrFail($auction_id);
		$this->authorize('exportAuction', $auction);

		Excel::create('Subasta', function($excel) use($auction) {
			$excel->sheet('Compras', function($sheet) use($auction) {
				$this->exportAuctionInfo($sheet, $auction);
				$this->exportAuctionBids($sheet, $auction);
			});
		})->export('xls');
	}

	private function exportAuctionBids($sheet, Auction $auction)
	{
		$sheet->setHeight(12, 20);
		$sheet->cell('A12:H12', function($cells) {
			$cells->setFont(array(
				'size'       => '16',
				'bold'       =>  true
			));
			$cells->setAlignment('center');

			// Set all borders (top, right, bottom, left)
			$cells->setBorder('none', 'none', Constants::CSS_SOLID, 'none');
		});

		$sheet->setHeight(10, 20);
		$sheet->cell('A10', function($cells) {
			$cells->setFont(array(
				'size'       => '16',
				'bold'       =>  true
			));
		});

		$sheet->cell('E13:H'.(count($auction->bids)+14), function($cells) {
			$cells->setAlignment('right');
		});

		$data = [
			[""],
			["Información ventas"],
			[""],
			[
				'Comprador',
				'Telefono',
				'Cantidad',
				'Unidad',
				'Precio Unitario',
				'Precio Total',
				'Fecha'
			]
		];

		$product = $auction->batch->product;

		foreach ($auction->bids as $b) {
			$comprador = $b->user;

			$bid = [
				$comprador->name,
				$comprador->phone,
				$b->amount,
				trans(Constants::TRANS_UNITS.$product->unit),
				"$ ". number_format ($b->price,2),
				"$ ". number_format($b->price * $b->amount,2),
				Carbon::parse($b->bid_date)->format('d/m/Y H:i:s')
			];

			$data[] = $bid;
		}

		$sheet->fromArray($data, null, 'A1', false, false);
	}

	private function exportAuctionInfo($sheet, Auction $auction)
	{

		$data = [
			[
				"Información de la subasta"
			],
			[
				"Barco"
			],
			[
				"","Nombre",$auction->batch->arrive->boat->name
			],
			[
				"","Arribo",Carbon::parse($auction->batch->arrive->date)->format('d/m/Y H:i:s')
			],
			[
				"Producto"
			],
			[
				"","Nombre",$auction->batch->product->name
			],
			[
				"","Calibre",trans('general.product_caliber.'.$auction->batch->caliber)],
			[
				"","Calidad",$auction->batch->quality . " estrellas"
			],
		];


		$sheet->mergeCells('A1:C1');

		$sheet->setHeight(1, 20);
		$sheet->setHeight(2, 20);
		$sheet->setHeight(5, 20);

		$sheet->cell('A1:C8', function($cells) {
			$cells->setBorder(Constants::CSS_SOLID, Constants::CSS_SOLID, Constants::CSS_SOLID, Constants::CSS_SOLID);
			$cells->setBackground('#dddddd');
		});

		$sheet->cell('A1:B8', function($cells) {
			$cells->setFont(array(
				'bold'       =>  true
			));
		});

		$sheet->cell('A1:A5', function($cells) {
			$cells->setFont(array(
				'size'       => '16',
				'bold'       =>  true
			));
		});

		$sheet->fromArray($data, null, 'A1', false, false);
	}

	public function subscribeUser($auction_id)
	{
		$auction = Auction::findOrFail($auction_id);
		$auction->subscribeUser(Auth::user());

		return redirect('auction?status='.Constants::FUTURE);
	}
/** NEEW **/
	public function subastaHome(Request $request)
    {
        $status = $request->get(Constants::STATUS,Constants::IN_CURSE);
		$product = $request->get(Constants::PRODUCT,null);
		$seller = $request->get(Constants::SELLER,null);
		$boat = $request->get('boat',null);
		$type = $request->get('type',"all");
		$auctions = Auction::filterAndPaginate($status,$product,$seller,$boat,$type,true);
		$products = Product::Select()->get();
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
        return view('landing',compact(Constants::AUCTIONS,Constants::STATUS,Constants::PRODUCTS,Constants::SELLERS,'request',Constants::BOATS,Constants::USER_RATING,'type'));
        //return view('/landing3/index',compact(Constants::AUCTIONS,Constants::STATUS,Constants::PRODUCTS,Constants::SELLERS,'request',Constants::BOATS,Constants::USER_RATING,'type'));
    }
    public static function getUserRating($userinfo){
        $ratings = $userinfo->rating;
        $total = ($ratings != null) ? ($ratings->positive + $ratings->negative + $ratings->neutral) : 0;
	    return ($ratings != null && $total > 0) ? (round(($ratings->positive * 100) / $total/20, 0,PHP_ROUND_HALF_UP)) : 1;
    }
    public static function getAuctionsDataForHome($auctions){
        $port=array();$products=array();$calibers=array();$users=array();$close=0;$min=99999999;$max=0;
        $quality=array(1=>0,2=>0,3=>0,4=>0,5=>0);$ratings=array(1=>0,2=>0,3=>0,4=>0,5=>0);
        if(count($auctions)==0){
            return;
        }
        foreach($auctions as $a) {
            $calibers[$a->batch->caliber]=(isset($calibers[$a->batch->caliber]))?$calibers[$a->batch->caliber]+1:1;
            $user = $a->batch->arrive->boat->user;
            $users[$user->id]=(isset($users[$user->id]))?$users[$user->id]+1:1;
            $port[$a->batch->arrive->port_id]=(isset($port[$a->batch->arrive->port_id]))?$port[$a->batch->arrive->port_id]+1:1;
            $products[$a->batch->product->id]=(isset($products[$a->batch->product->id]))?$products[$a->batch->product->id]+1:1;
        }
        return array(
            Constants::PRODUCTS=>$products,
            Constants::PORTS=>$port,
            Constants::CALIBERS=>$calibers,
            Constants::USERS=>$users
        );
    }
    public static function convertFilterSubastas($filters){
        if(count($filters)==0){
            return;
        }
        $params=array();
        $paramskeys=array('port'=>'portid','product'=>'productid','caliber'=>'caliber','quality'=>'quality','user'=>'sellerid','pricemin'=>'pricemin','pricemax'=>'pricemax','close'=>'close','userrating'=>'userrating');
        foreach($filters as $key=>$val){
            $valtrimmed=substr($val,0,-2);
            $params[$paramskeys[$key]]=(substr_count($valtrimmed,'**')>0)?explode('**',$valtrimmed):$valtrimmed;
        }
        return $params;
    }
    public static function getMaxMinPrice($auctions){
        $min=99999999;$max=0;
        foreach($auctions as $a){
            $price=(float)self::calculatePriceID($a->id)['CurrentPrice'];
            if($price>$max){
                $max=$price;
            }
            if($price<$min){
                $min=$price;
            }
        }
        return array('min'=>$min,'max'=>$max);
    }
    public static function getauctions(Request $request){
        $limit=(int)$request->limit;
        $ids=($limit==1)?$request->ids:null;
        $time=$request->time;
        $filters=self::convertFilterSubastas($request->filters);
        $auctions=Auction::auctionHome($ids,$filters,$time);
        if($limit>1){
            $preciomin=(float)$filters['pricemin'];
            $preciomax=(float)$filters['pricemax'];
            $close=(isset($filters['close']))?1:0;
            $rating=(isset($filters['userrating']))?1:0;
            foreach($auctions as $index=>$auction){
                $priceall=  self::calculatePriceID($auction->id,$auction->target_price);
                $price=(float)str_replace(',','.',$priceall['CurrentPrice']);
                $target=$priceall['Close'];
                $userrating=self::getUserRating($auction->batch->arrive->boat->user);
                if($price<$preciomin || $price>$preciomax || ($close==1 && $target!=1) || ($rating==1 && $userrating!=$filters['userrating']) ){
                    unset($auctions[$index]);
                }
            }
            return view('/landing3/partials/ListaSubastas')
                ->withAuctions(Constants::manualPaginate($auctions,$request->url(),$request->current))
                ->with('request',$request)->with('limit',$limit);
        }else{
            return view('/landing3/partials/auctionNoDetail')
                ->withAuction($auctions[0]);
        }
        
    }
    
    public function subastasFront(Request $request){
        $buyers = User::filter(null, array(User::COMPRADOR), array(User::APROBADO));
        $boats = Boat::Select()->get();
        $auctionhome=Auction::auctionHome();
        $auctions1 = $auctionhome[Constants::IN_CURSE];
        $auctions2 = array_reverse($auctionhome[Constants::FINISHED]);
        //dd($auctions1);
        $auctiondetails1=$this->getAuctionsDataForHome($auctions1);
        
        $auctiondetails2=$this->getAuctionsDataForHome($auctions2);
        $port=$auctiondetails1[Constants::PORTS];
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
        $timeline=(isset($request->time))?$request->time:Constants::IN_CURSE;
        $params=null;
        if(isset($request->type)){
            $params['type']=Constants::AUCTION_PRIVATE;
        }
        $auctions=Auction::auctionHome(null,$params,$timeline);
        $auctiondetails1=$this->getAuctionsDataForHome($auctions);
        
        return view('/landing3/subastas')
            ->withAuctions(Constants::manualPaginate($auctions,$request->url()))
            ->withPorts($auctiondetails1[Constants::PORTS])
            ->withBoats($boats)
            ->withusers($auctiondetails1[Constants::USERS])
            ->withCaliber($auctiondetails1[Constants::CALIBERS])
            ->withProducts($auctiondetails1[Constants::PRODUCTS])
            ->withPortId($request->input('port_id'))
            ->withRequest($request)
            ->withLimit(Constants::PAGINATE_NUMBER)
            ->withClose($auctiondetails1[Constants::CLOSE])
            ->withRatings($auctiondetails1['ratings'])
            ->withQuality($auctiondetails1['quality'])
            ->withPrices(array('min'=> round($auctiondetails1['min'],0,PHP_ROUND_HALF_DOWN),'max'=>(int)$auctiondetails1['max']))
            ->withTimeline($timeline)
            ;
    }
    public function addAuction(Request $request){
        if(empty(Auth::user()->id)){
            return redirect('auth/login');
        }
        $boats=Boat::select('id','name')
            ->where('user_id', Constants::EQUAL,Auth::user()->id)->get();
        $ports=Ports::select()->get();
        $products= Product::select('id','name')->get();
        return view('/landing3/auction-add-edit')
            ->with('boats',$boats)
            ->with('ports',$ports)
            ->with('products',$products);
    }
    public function offerList(Request $request){
        $auctions=Auction::AuctionsQueryBuilder(array(
            'sellerid'=>Auth::user()->id
        ));
        $offers=array();
        foreach($auctions as $auction){
            $auction->code=self::getAuctionCode($auction->correlative,$auction->created_at);
            $offers[$auction->id]= self::getOffers($auction->id);
        }
        return view('landing3/offers')
            ->with('auctions',$auctions)->with('offers',$offers);
    }
    public function getParticipantes(Request $request){

	    $auction = Auction::find($request->get("auctions"));
	    return $auction->userInvited;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function offersAuction(Request $request)
    {
        $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
        $prices = $request->input('prices');
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
            $price = str_replace(",","",$prices);
            DB::beginTransaction();
            $available = $this->getAvailable($auction_id, $auction->amount);

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
        $template = 'emails.offerauction';
        $seller = $auction->batch->arrive->boat->user ;
        Mail::queue($template, ['user' => $user , Constants::SELLER=> $seller, Constants::PRODUCT=> $resp] , function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
            );
            $message->subject(trans('users.offer_auction'));
            $message->to($user->email);
        });

        return Redirect::back()->with('success','Su oferta se registro satisfactoriamente. Se ha enviado un correo con la información detallada');

    }
    public function offersAuctionFront(Request $request)
    {
        $auction_id = $request->input(Constants::INPUT_AUCTION_ID);
        $prices = $request->input('prices');
        $auction = Auction::findOrFail($auction_id);
        $this->authorize(Constants::MAKE_BID, $auction);
        $checkuser=$this->checkIfBuyerCanBuy($auction_id,null,'offer',$auction->type);
        if($checkuser==0){
            return json_encode(array(Constants::ERROR=>'Tu usuario no puede ofertar'));
        }
        $resp  =  array();

        if ($auction->active == 0 )
        {
            $resp[Constants::ACTIVE_LIT] = $auction->active ;

        }else{
            $price = str_replace(",","",$prices);
            DB::beginTransaction();
            $available = $this->getAvailable($auction_id, $auction->amount);

            if ($available[Constants::AVAILABLE] > 0 )
            {
                $auction->offersAuction($available[Constants::AVAILABLE],$price);
                $unit = $auction->batch->product->unit;
                $caliber = $auction->batch->caliber;
                $quality = $auction->batch->quality;
                $product = $auction->batch->product->name;
                $resp[Constants::IS_NOT_AVAILABLE] = 0;
                $resp['unit'] = trans(Constants::TRANS_UNITS.$unit);
                $resp[Constants::CALIBER] = Constants::caliber($caliber);
                $resp[Constants::QUALITY] = $quality;
                $resp[Constants::PRODUCT] = $product;
                $resp['productid']=$auction->batch->product->id;
                $resp[Constants::AMOUNT] = $available[Constants::AVAILABLE];
                $resp[Constants::PRICE] = $price;
                $resp['offerscounter']=$this->getOffersCount($auction_id);
                $resp['bidscounter']=$available['sold'];
            }else{
                $resp[Constants::ERROR]='No es posible ofertar, No hay disponibilidad de este producto.';
            }

            $resp[Constants::ACTIVE_LIT] = $auction->active ;

            DB::commit();
        }

        $user = User::findOrFail(Auth::user()->id);
        $template = 'emails.offerauction';
        $seller = $auction->batch->arrive->boat->user ;
        Mail::queue($template, ['user' => $user , Constants::SELLER=> $seller, Constants::PRODUCT=> $resp] , function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
            );
            $message->subject(trans('users.offer_auction'));
            $message->to($user->email);
        });

        $resp['success']=1;
        return json_encode($resp);

    }

    //Obtener el puerto por id
    static public function getPortById($port_id){

        $ports = Ports::Select('name')->where('id','=',$port_id)->get();
        echo $ports[0]['name'];

    }

    /**
     * @param Request $request
     * @param $auction_id
     * @return string|void
     */
    public function offersToBid(Request $request, $auction_id)
    {

        setlocale(LC_MONETARY, 'en_US');
        $auction = Auction::findOrFail($auction_id);
        $this->authorize('viewOperations', $auction);
        $request->session()->put('url.intended', '/auction/offers/'.$auction_id);
        $available = $this->getAvailable($auction_id, $auction->amount);
        $offers = $this->getOffers($auction_id);
        $count = count($offers);
//        $declineoffer = 'auction/offers/decline/'.$auction_id;
//        dd($declineoffer);
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
                    $offers = $this->getOffers($auction_id);
//                        return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">Se vendio todo</h1>');
                    return $offers;
                }

            }
        }

        if ($available[Constants::AVAILABLE] == 0){
            $offers = $this->getOffers($auction_id);
            return $offers;
        }


        if ($count>0){
            $offers = $this->getOffers($auction_id);
            return $offers;
        }else{
            return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">No hay ofertas realizadas<br>Disponibles: '. $available[Constants::AVAILABLE].'</h1>');
        }
    }

    public function autoOffersBid(Request $request, $auction_id)
    {

        setlocale(LC_MONETARY, 'en_US');
        $auction = Auction::findOrFail($auction_id);
        $this->authorize('viewOperations', $auction);
        $request->session()->put('url.intended', '/auction/offers/'.$auction_id);
        $available = $this->getAvailable($auction_id, $auction->amount);
        $offers = $this->getOffers($auction_id);

        foreach ($offers as $offer) {

            // Verifico la fecha de la subasta
            if ($auction->end >= date('Y-m-d H:i:s')){
                return;
            }

dd("hola");
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

        if (count($offers)>0)
            return;
        else
            return;
    }


    /**
     * funcion que llama la vista de detalles de una subasta
     * @param $auction_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auctionDetails($auction_id){
        $auction = Auction::findOrFail($auction_id);
        //Creamos la instacia de AuctionController para usar el metodo de calculatePriceID
        $objAuct = new AuctionController();
        $price= $objAuct->calculatePriceID($auction_id)['CurrentPrice'];
        $availability=$this->getAvailable($auction_id,$auction->amount);
        return view('landing3.subasta', compact(Constants::AUCTION,Constants::PRICE,Constants::AVAILABILITY));

    }

    public function getAvailable($auction_id, $amountTotal){
        $sold = 0;
        $data = array();
        $bids = Bid::Select()
            ->where(Constants::STATUS,'<>',Constants::NO_CONCRETADA)
            ->where(Constants::BIDS_AUCTION_ID,$auction_id)
            ->get();
        foreach ($bids as $b) {
            $sold+= $b->amount;
        }
        $available = $amountTotal-$sold;
        $data[Constants::AVAILABLE] = $available;
        $data['sold'] = count($bids);
        return $data;
    }
    public function offerForSale($auction, $offer)
    {
        $auction_id = $auction->id;
        $prices = $offer->price;
        $resp  =  array();
        $request = null;
        $price = str_replace(",","",$prices);
        $available = $this->getAvailable($auction_id, $auction->amount);

        if ($available[Constants::AVAILABLE] > 0){
            if ($price <= $auction->end_price){
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
//Update batch_statuses
//        $this->status = Batch::findOrFail($auction->batch_id)->status;
//        $this->status->assigned_auction -= $available[Constants::AVAILABLE];
//        $this->status->auction_sold += $available[Constants::AVAILABLE];
//        $this->status->save();

            $this->emailOfferBid($auction,$available,$offer);
            $offers = $this->getOffers($auction_id);
            return $offers;
        } else {
            $offer = null;
            $this->declineOffers($auction_id,$offer,$request);
            return;
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
            'batches.caliber',
            'batches.quality',
            'products.name AS Producto',
            'auctions_offers.user_id'/*,
            'users.name AS Comprador'*/
        )
            ->join(Constants::AUCTIONS,Constants::AUCTIONS_ID,'=',Constants::INPUT_AUCTION_ID)
            ->join(Constants::BATCHES,'batches.id','=',Constants::AUCTIONS_BATCH_ID)
            ->join(Constants::PRODUCTS,'products.id','=','batches.product_id')
            ->join(Constants::USERS,Constants::USERS_ID,'=','auctions_offers.user_id')
            ->where('auctions_offers.auction_id','=',$auction_id)
            ->orderBy('auctions_offers.price','desc')
            ->orderBy('auctions_offers.created_at','asc')
            ->get();
    }

    public function getCurrentTime()
    {
//        $date = date('D M d Y H:m:i \G\M\TO');
//        return date(Constants::DATE_FORMAT);
        return gmdate('D, M d Y H:i:s T\-0300', time());
    }


    //Declinar de forma masiva las ofertas

    /**
     * @param $auction_id
     * @return bool
     */
    public function declineOffers($auction_id,$offer_id = null,Request $request = null)
    {
        $auction = Auction::findOrFail($auction_id);
        $this->authorize('isMyAuction',$auction);
        $offers = $this->getOffers($auction_id);
        $available = $this->getAvailable($auction_id, $auction->amount);
//        if (isset($_GET['opc']) && $_GET['opc']==1){
//            $this->autoOffersBid($request, $auction_id);
//        }
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
                            $this->bid->save();

                            //send email
                            $this->emailOfferBid($auction,$available,$o);
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
        return $this->getOffers($auction_id);
//        return redirect('home');

    }


    public function emailOfferBid($auction,$available,$offer)
    {
        //Datos de envio de correo
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
        $resp[Constants::PRICE] = $offer->price;
        $resp[Constants::ACTIVE_LIT] = $auction->active;

        $user = User::findOrFail($offer->user_id);
        $template = 'emails.offerForBid';
        $seller = $auction->batch->arrive->boat->user ;
        Mail::queue($template, ['user' => $user , Constants::SELLER=> $seller, Constants::PRODUCT=> $resp] , function ($message) use ($user) {
            $message->from(
                env(Constants::MAIL_ADDRESS_SYSTEM,Constants::MAIL_ADDRESS),
                env(Constants::MAIL_ADDRESS_SYSTEM_NAME,Constants::MAIL_NAME)
            );
            $message->subject(trans('users.offer_Bid'));
            $message->to($user->email);
        });
    }


}
