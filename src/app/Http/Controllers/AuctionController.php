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
//        dd(Auth::user()->privateAuctions[0]->userInvited);

        $status = $request->get('status',Auction::IN_CURSE);
		$product = $request->get('product',null);
		$seller = $request->get('seller',null);
		$boat = $request->get('boat',null);
		$type = $request->get('type',null);

		if ($type == Auction::AUCTION_PRIVATE)
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
        return view('auction.index',compact('auctions','status','products','sellers','request','boats','userRating','type'));
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
        return view('auction.create',compact('buyers'))->with('batch',$batch);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAuctionRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAuctionRequest $request)
    {
        $batch = Batch::findOrFail($request->input('batch'));
        $this->authorize('createAuction', $batch);

        $batch->assignForAuction($request->input('amount'));

        $startDate = Carbon::createFromFormat('d/m/Y H:i', $request->input('fechaInicio'));
        $endDate = Carbon::createFromFormat('d/m/Y H:i', $request->input('fechaFin'));
        $endprice=$request->input('endPrice');
        $rand=rand(1,7)/100;
        $targetprice=($endprice*$rand)+$endprice;
        $auction  = new Auction();
        $startprice=$request->input("startPrice");
        $endprice=$request->input('endPrice');
        $difprice=$startprice-$endprice;
        $rand=rand(1,7)/100;
        $targetprice=$endprice+($difprice*$rand);
        $auction->batch_id = $request->input('batch');
        $auction->start = $startDate->format('Y-m-d H:i:s');
        $auction->start_price = $startprice;
        $auction->end = $endDate->format('Y-m-d H:i:s');
        $auction->end_price = $endprice;
        $auction->target_price = $targetprice;
        $auction->interval = $request->input('intervalo');
        $auction->amount = $request->input('amount');
		$auction->type = $request->input('tipoSubasta');
		$auction->description = $request->input('descri');
		$auction->save();
		if ($request->input('tipoSubasta') == \App\Auction::AUCTION_PRIVATE )
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
				Mail::queue($template, ['user' => $user , 'seller'=> $seller] , function ($message) use ($user) {
					$message->from(
						env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
						env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
					);
					$message->subject(trans('users.private_auction'));
					$message->to($user->email);
				});

			}
			
			
			
		}
		
        
		

        return redirect('/sellerbatch');
    }

    public function calculatePrice(Request $request)
    {
        $data = array();
        $auction_id = $request->input('auction_id');
        $bidDate = date('Y-m-d H:i:s');
        $auction = Auction::findOrFail($auction_id);
        $prices = $auction->calculatePrice($bidDate);
//        $price = number_format(str_replace(",","",$prices),2,',','');
        $price = str_replace(",","",$prices);
        $available=$this->getAvailable($auction_id,$auction->amount);
        $amount=$auction->amount;
        $targetamount=($amount*0.75);
        $cantventas=$available['available'];
        $hot=($cantventas<$targetamount)?1:0;
        if($request->get("i") && $request->get('i')=='c'){
            $targetprice=$auction->target_price;
            $close=($price<$targetprice)?1:0;
            $time = round(microtime(true) * 1000);

            $data['id'] = $auction_id;
            $data['close'] = $close;
            $data['end'] = $auction->end;
            $data['availability'] = $available['available'];
            $data['currenttime'] = $time;
            $data['price'] = $price;
            $data['available'] = $available['available'];
            $data['amount']=$amount;
            $data['hot']=$hot;
            $data['offers']=$this->getOffersCount($auction_id);
            $data['bids']=$available['sold'];
            return json_encode($data);
        }else{
            $data['price'] = $price;
            $data['available'] = $available['available'];
            return $data;
        }

    }
    public function calculatePriceID($id)
    {
        $bidDate = date('Y-m-d H:i:s');
        $auction = Auction::findOrFail($id);
        $prices = $auction->calculatePrice($bidDate);
        return number_format(str_replace(",","",$prices),2,',','');
    }
	 public function calculatePeso(Request $request)
	 {
		// dd($product_id);
		return $request->input('product_id');
		// $weigth = $request->input('weigth');

		// $auction = Auction::findOrFail($weigth);
		
		// $finalWeigth = 0;

		// dd('hola');
        
  //       $timeStart = $this->start;
  //       $timeEnd = $this->end;
  //       $priceStart = $this->start_price;
  //       $priceEnd = $this->end_price;


  //       if ($weigth != 0) {

  //           $finalWeigth = ($intervalBuy * $weigth);

  //       }else{
  //           return number_format($finalWeigth, env('AUCTION_PRICE_DECIMALS', 2));
  //       }






	 }

    public static function getOffersCount($id){
        $offers=Offers::Select()->where('auction_id','=',$id)->get();
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
                ->join('auctions_invites', 'auctions.id', '=', 'auctions_invites.auction_id')
                ->where('auctions.type', '=', 'private')
                ->where('auctions_invites.user_id', '=', Auth::user()->id)
                ->where('auctions.id','=', $id);
            if(count($auction)==0){
                return 0;
            }
        }
        if($type!='bid'){
            return 1;
        }
        $bids = Bid::where('user_id', Auth::user()->id)->where('status','=','pending')->get();
        foreach($bids as $bid){
            $amount+=($bid->amount*$bid->price);
        }

        $comprador = User::select('*')
            ->join('comprador','users.id','=','comprador.user_id')
            ->where('users.id', Auth::user()->id)->get();
        return ($amount>$comprador[0]->bid_limit)?0:1;

    }
	 public function makeBid(Request $request)
	 {

         $auction_id = $request->input('auction_id');
         $amount = $request->input('amount');
         $auction = Auction::findOrFail($auction_id);
         $this->authorize('makeBid', $auction);
         $checkuser=$this->checkIfBuyerCanBuy($auction_id,$amount,'bid',$auction->type);
         if($checkuser==0){
             return json_encode(array('error'=>'Tu usuario no puede comprar'));
         }
         if(empty($request->input('price'))){
             $bidDate = date('Y-m-d H:i:s');
             $prices = $auction->calculatePrice($bidDate);
             $price = str_replace(",","",$prices);
         }else{
             $price=$request->input('price');
         }
        //if($this->checkIfBuyercanBuy($amount*$price)==false){return json_encode(array('limited'=>1));}
		$resp  =  array();
		
		if ($auction->active == 0 )
		{
			$resp['active'] = $auction->active ;
			
		}else{
            $availabilityboth=$this->getAvailable($auction_id,$auction->amount);
            $availability=$availabilityboth['available'];
            $bidscounter=$availabilityboth['sold']+1;
            DB::beginTransaction();
				if ($amount > 0 && $amount <= $availability  )
				{

					$auction->makeBid($amount,$price);

					$unit = $auction->batch->product->unit;
					$product = $auction->batch->product->name;
                    $amounttotal=$auction->amount;
                    $targetamount=($amounttotal*0.75);
                    $hot=(($availability-$amount)<$targetamount)?1:0;
					$resp['isnotavailability'] = 0;
                    $resp['availability'] = $availability-$amount;
					$resp['unit'] = trans('general.product_units.'.$unit);
					$resp['product'] = $product;
					$resp['amount'] = $amount;
					$resp['price'] = $price;
					$resp['totalAmount']=$amounttotal;
                    $resp['bidscounter']=$bidscounter;
                    $resp['offerscounter']=$this->getOffersCount($auction_id);
                    $resp['hot']=$hot;
				}else{
					$resp['isnotavailability'] = 1;
					$resp['availability'] = $availability-$amount;
					$unit = $auction->batch->product->unit;
					$product = $auction->batch->product->name;
					$resp['unit'] = trans('general.product_units.'.$unit);
					$resp['product'] = $product;
				
				}
				
			
				$resp['active'] = $auction->active ;
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

        return view('auction.edit')->with('auction',$auction);
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

        $startDate = Carbon::createFromFormat('d/m/Y H:i', $request->input('fechaInicio'));
        $endDate = Carbon::createFromFormat('d/m/Y H:i', $request->input('fechaFin'));

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
        $newAmount = $request->input('amount');
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
        return view('auction.operations',compact('auction'));
    }


	 public function buyerBid(Request $request)
	 {
		$user = Auth::user();
		if (Auth::user()->type == \App\User::COMPRADOR){
//		if (Auth::user()->type == "buyer"){
            $this->authorize('seeMyBids', Bid::class);
			$bids = Bid::where('user_id' , $user->id )->orderBy('bid_date', 'desc')->paginate();
//	dd(Auth::user()->type == \App\User::COMPRADOR);
			return view('bid.index',compact('bids'));
		} else {
//            echo "<script>alert('No tiene acceso');</script>";
			return redirect('home');
		}
			
	 }
	 
	 public function deactivate($auction_id)
	 {
		 $now = strtotime(date("Y-m-d H:i:s"));
		 $auction = Auction::findOrFail($auction_id);
		 $this->authorize('isMyAuction',$auction);
		 $auction->active = Auction::INACTIVE; 
		 
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
        if($request->input('concretada') == Bid::NO_CONCRETADA){
            $bid->auction->cancelBid($bid);
            $bid->reason = $request->input('motivo_no_concretada');
        }
        $bid->user_calification = $request->input('calificacion');
        $bid->user_calification_comments = $request->input('comentariosCalificacion');
        $bid->save();

		$user_id = $bid->user_id;
		$this->incrementRatingUser($bid , $user_id);
		
		
		if ($request->input('calificacion') == Bid::CALIFICACION_NEGATIVA)
		{
			$internals = User::getInternals(array(User::APROBADO));
			foreach($internals as $i)
			{
				$user = User::findOrFail($user_id);
				$template = 'emails.userqualifynegative';
				$seller = $bid->auction->batch->arrive->boat->user ;
				Mail::queue($template, ['bid'=>$bid, 'user' => $user , 'comentariosCalificacion'=> $request->input('comentariosCalificacion') , 'seller'=> $seller , 'type' => User::COMPRADOR ] , function ($message) use ($i) {
					$message->from(
						env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
						env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
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
        $bid->seller_calification = $request->input('calificacion');
        $bid->seller_calification_comments = $request->input('comentariosCalificacion');
        $bid->save();
		
		$user_id = $bid->auction->batch->arrive->boat->user->id;
		$this->incrementRatingUser($bid , $user_id);
		
		if ($request->input('calificacion') == Bid::CALIFICACION_NEGATIVA)
		{
			$internals = User::getInternals(array(User::APROBADO));
			foreach($internals as $i)
			{
				$user = User::findOrFail($bid->user_id);
				$template = 'emails.userqualifynegative';
				$seller = $bid->auction->batch->arrive->boat->user ;
				Mail::queue($template, ['bid'=>$bid,'user' => $user , 'comentariosCalificacion'=> $request->input('comentariosCalificacion') , 'seller'=> $seller , 'type' => User::VENDEDOR ] , function ($message) use ($i) {
					$message->from(
						env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
						env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
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
		
		if ($bid->seller_calification == Bid::CALIFICACION_POSITIVA)
		{
			$userRating->increment('positive', 1);
		}else if ($bid->seller_calification == Bid::CALIFICACION_NEGATIVA)
		{
			$userRating->increment('negative', 1);
		}else if ($bid->seller_calification == Bid::CALIFICACION_NEUTRAL)
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
			$cells->setBorder('none', 'none', 'solid', 'none');
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
				trans('general.product_units.'.$product->unit),
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
			$cells->setBorder('solid', 'solid', 'solid', 'solid');
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

		return redirect('auction?status='.Auction::FUTURE);
	}









/** NEEW **/



	public function subastaHome(Request $request)
    {
        // $this->authorize('seeAuctions', Auction::class);
        $status = $request->get('status',Auction::IN_CURSE);
		$product = $request->get('product',null);
		$seller = $request->get('seller',null);
		$boat = $request->get('boat',null);
		
//		$auction_id = $request->get('auction_id',null);
//		$invited = $request->get('invited',null);
		$type = $request->get('type',"all");
		
		$auctions = Auction::filterAndPaginate($status,$product,$seller,$boat,$type,true);
//		$auctions=Auction::auctionPrivate(Auth::user()->id,$status);
//        dump($auctions);
//        $auctions = Auction::getClosingAuction();
//        $array_auctions = [];
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
        return view('landing',compact('auctions','status','products','sellers','request','boats','userRating','type'));
        //return view('/landing3/index',compact('auctions','status','products','sellers','request','boats','userRating','type'));
    }
    public function orderAuctions($auctions){
	    $cant=count($auctions);
	    for($z=0;$z<$cant;$z++){
	        if($z<($cant-1) && $auctions[$z]->end>$auctions[$z+1]->end){
	            $temp=$auctions[$z];
	            $auctions[$z]=$auctions[$z+1];
	            $auctions[$z+1]=$temp;
            }
        }
	    return $auctions;
    }
    public function getAuctionsDataForHome($auctionsnoorder,$return=null){
        $auctionsreturn=array();$userRating =  array();$usercat=array();$port=array();$products=array();$calibers=array();$users=array();$price=array();$close=array();
        $auctions=$this->orderAuctions($auctionsnoorder);
        foreach($auctions as $a) {
            $user = $a->batch->arrive->boat->user;
            $ratings = $user->rating;
            $total = ($ratings != null) ? ($ratings->positive + $ratings->negative + $ratings->neutral) : 0;
            $userRating[$user->id] = ($ratings != null && $total > 0) ? (round(($ratings->positive * 100) / $total, 2)) : 0;
            $usercat[$user->id] = Auction::catUserByAuctions($user->id);
            $price[$a->id] = $this->calculatePriceID($a->id);
            $close[$a->id]=($price[$a->id]<$a->target_price)?1:0;
            $auctionsreturn[] = $a;
            if (isset($caliber[$a->batch->caliber]['cant'])) {
            } else {
                $calibers[$a->batch->caliber] = 1;
            }
            if (isset($users[$user->id])) {
                $users[$user->id]['cant']++;
            } else {
                $users[$user->id] = array(
                    'cant' => 1,
                    'nickname' => $user->nickname
                );
            }
            if (isset($port[$a->batch->arrive->port_id]['cant'])) {
                $port[$a->batch->arrive->port_id]['cant']++;
            } else {
                $ports = Ports::Select('port.name')->where('port.id','=',$a->batch->arrive->port_id)->get();
                $port[$a->batch->arrive->port_id] = array(
                    'name' => $ports[0]['name'],
                    'cant' => 1
                );

            }
            if (isset($products[$a->batch->product->name]['cant'])) {
                $products[$a->batch->product->name]['cant']++;
            } else {
                $products[$a->batch->product->name] = array(
                    'id' => $a->batch->product->id,
                    'cant' => 1
                );
            }
        }
        return array(
            'auctions'=>$auctionsreturn,
            'products'=>$products,
            'ports'=>$port,
            'calibers'=>$calibers,
            'users'=>$users,
            'usercat'=>$usercat,
            'userrating'=>$userRating,
            'prices'=>$price,
            'close'=>$close
        );
    }
    public function getMoreAuctions(Request $request){
        $limit=(int)$request->input('limit');
        $ids=explode("**",$request->input('ids'));
        $auctions=Auction::auctionHome($ids)['Current'];
        $views=array();
        if(count($auctions)==0){
            return json_encode($views);
        }
        $cantreturn=(count($auctions)>$limit)?$limit:count($auctions);
        $auctioninfo=$this->getAuctionsDataForHome($auctions);
        for($z=0;$z<$cantreturn;$z++){
            $view= view('/landing3/partials/auctionNoDetail')
                ->withAuction($auctions[$z])
                ->withUserrating($auctioninfo['userrating'])
                ->withPorts($auctioninfo['ports'])
                ->withUsercat($auctioninfo['usercat'])
                ->withPrice($auctioninfo['prices'])
                ->withClose($auctioninfo['close'])
                ->withProducts($auctioninfo['products']);
            $views[]=(string)$view;
        }
        return json_encode($views);


    }
    public function subastasDestacadasHome($return=2){
        $sellers = User::filter(null, array(User::VENDEDOR), array(User::APROBADO));
        $buyers = User::filter(null, array(User::COMPRADOR), array(User::APROBADO));
        $boats = Boat::Select()->get();
        $auctionhome=Auction::auctionHome();
        $auctions1 = $auctionhome['Current'];
        $auctiondetails1=$this->getAuctionsDataForHome($auctions1,$return);
        if ($return == 2) {
            $auctions2 = $auctionhome['Finished'];
            $auctiondetails2=$this->getAuctionsDataForHome($auctions2,$return);
        }
        $auctions = array();$finishedauctions = array();$userRating =  array();$usercat=array();$port=array();$products=array();$calibers=array();$users=array();$price=array();$close=array();
        for($z=1;$z<=$return;$z++){
            $var="auctiondetails$z";
            foreach(${$var}['auctions'] as $item){
                $var2=($z==1)?'auctions':'finishedauctions';
                ${$var2}[]=$item;
            }
            foreach (${$var}['products'] as $item => $val) {
                $products[$item] = $val;
            }
            foreach (${$var}['ports'] as $item => $val) {
                $port[$item] = $val;
            }
            foreach (${$var}['calibers'] as $item => $val) {
                $calibers[$item] = $val;
            }
            foreach (${$var}['users'] as $item => $val) {
                $users[$item] = $val;
            }
            foreach (${$var}['usercat'] as $item => $val) {
                $usercat[$item] = $val;
            }
            foreach (${$var}['userrating'] as $item => $val) {
                $userRating[$item] = $val;
            }
            foreach (${$var}['prices'] as $item => $val) {
                $price[$item] = $val;
            }
            foreach (${$var}['close'] as $item => $val) {
                $close[$item] = $val;
            }
        }
        if($return==2){
            $ports=Ports::Select()->get();
            return view('/landing3/index')
                ->withAuctions($auctions)
                ->withAuctionsf($finishedauctions)
                ->withUserrating($userRating)
                ->withPorts($port)
                ->withPortsall($ports)
                ->withUsercat($usercat)
                ->withPrice($price)
                ->withBoats($boats)
                ->withBuyers($buyers)
                ->withClose($close)
                ->withProducts($products);
        }else{
            return array(
                'auctions'=>$auctions,
                'usercat'=>$usercat,
                'userRating'=>$userRating,
                'price'=>$price,
                'ports'=>$port,
                'boats'=>$boats,
                'products'=>$products,
                'caliber'=>$calibers,
                'users'=>$users,
                'close'=>$close,
                'sellers'=>$sellers);
        }
    }
    public function listaSubastas(){
	    $all=$this->subastasDestacadasHome(1);
        return view('/landing3/subastas')
            ->withAuctions($all['auctions'])
            ->withUserrating($all['userRating'])
            ->withPorts($all['ports'])
            ->withUsercat($all['usercat'])
            ->withPrice($all['price'])
            ->withBoats($all['boats'])
            ->withusers($all['users'])
            ->withClose($all['close'])
            ->withCaliber($all['caliber'])
            ->withProducts($all['products'])
            ;
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
        $auction_id = $request->input('auction_id');
        $prices = $request->input('prices');
        $auction = Auction::findOrFail($auction_id);
        $this->authorize('makeBid', $auction);
        //$this->authorize('canBid',Auction::class);

        if ($auction->type == \App\Auction::AUCTION_PRIVATE )
        {
            $this->authorize('isInvited', $auction);
        }
        $resp  =  array();

        if ($auction->active == 0 )
        {
            $resp['active'] = $auction->active ;

        }else{
            $price = str_replace(",","",$prices);
            DB::beginTransaction();
            $available = $this->getAvailable($auction_id, $auction->amount);

            if ($available['available'] > 0 )
            {
                $auction->offersAuction($available['available'],$price);
                $unit = $auction->batch->product->unit;
                $caliber = $auction->batch->caliber;
                $quality = $auction->batch->quality;
                $product = $auction->batch->product->name;
//                $total = $available['available'] * $price;
                $resp['isnotavailability'] = 0;
                $resp['unit'] = trans('general.product_units.'.$unit);
                $resp['caliber'] = $caliber;
                $resp['quality'] = $quality;
                $resp['product'] = $product;
                $resp['amount'] = $available['available'];
                $resp['price'] = $price;

            }else{
                return Redirect::back()->with('error','No es posible ofertar, no hay disponibilidad de este producto');
            }

            $resp['active'] = $auction->active ;

            DB::commit();
        }

        $user = User::findOrFail(Auth::user()->id);
        $template = 'emails.offerauction';
        $seller = $auction->batch->arrive->boat->user ;
        Mail::queue($template, ['user' => $user , 'seller'=> $seller, 'product'=> $resp] , function ($message) use ($user) {
            $message->from(
                env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
                env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
            );
            $message->subject(trans('users.offer_auction'));
            $message->to($user->email);
        });

        return Redirect::back()->with('success','Su oferta se registro satisfactoriamente. Se ha enviado un correo con la información detallada');

    }
    public function offersAuctionFront(Request $request)
    {
        $auction_id = $request->input('auction_id');
        $prices = $request->input('prices');
        $auction = Auction::findOrFail($auction_id);
        $this->authorize('makeBid', $auction);
        //$this->authorize('canBid',Auction::class);

        $checkuser=$this->checkIfBuyerCanBuy($auction_id,null,'offer',$auction->type);
        if($checkuser==0){
            return json_encode(array('error'=>'Tu usuario no puede ofertar'));
        }
        $resp  =  array();

        if ($auction->active == 0 )
        {
            $resp['active'] = $auction->active ;

        }else{
            $price = str_replace(",","",$prices);
            DB::beginTransaction();
            $available = $this->getAvailable($auction_id, $auction->amount);

            if ($available['available'] > 0 )
            {
                $auction->offersAuction($available['available'],$price);
                $unit = $auction->batch->product->unit;
                $caliber = $auction->batch->caliber;
                $quality = $auction->batch->quality;
                $product = $auction->batch->product->name;
                $resp['isnotavailability'] = 0;
                $resp['unit'] = trans('general.product_units.'.$unit);
                $resp['caliber'] = $caliber;
                $resp['quality'] = $quality;
                $resp['product'] = $product;
                $resp['amount'] = $available['available'];
                $resp['price'] = $price;
                $resp['offerscounter']=$this->getOffersCount($auction_id);
                $resp['bidscounter']=$available['sold'];
            }else{
                $resp['error']='No es posible ofertar, No hay disponibilidad de este producto.';
            }

            $resp['active'] = $auction->active ;

            DB::commit();
        }

        $user = User::findOrFail(Auth::user()->id);
        $template = 'emails.offerauction';
        $seller = $auction->batch->arrive->boat->user ;
        Mail::queue($template, ['user' => $user , 'seller'=> $seller, 'product'=> $resp] , function ($message) use ($user) {
            $message->from(
                env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
                env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
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
        foreach ($offers as $offer) {
            // Verifico la fecha de la subasta
            if ($auction->end >= date('Y-m-d H:i:s'))
                return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">La subasta no ha culminado</h1>');

            //verifica que el precio ofertado sea mayor e igual al de la subasta terminada
            if ($offer->price >= $offer->end_price){
                if ($offer->status == Offers::PENDIENTE){
                    //registramos la compra a la mejor opc de compra
                    $offerForSale = $this->offerForSale($auction, $offer);
                    if ($offerForSale)
                        return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">Se vendio todo</h1>');
                }
            }
        }

        if ($available['available'] == 0)
            return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">Se vendio todo</h1>');

        if (count($offers)>0)
            return $offers;
        else
            return ('<h1 style="    text-align: center; margin-top: 300px; font-size: 5em">No hay ofertas realizadas<br>Disponibles: '. $available['available'].'</h1>');
    }

    /*funcion que llama la vista de detalles de una subasta*/
    public function auctionDetails($auction_id){

        $auction = Auction::findOrFail($auction_id);

        //Creamos la instacia de AuctionController para usar el metodo de calculatePriceID
        $objAuct = new AuctionController();

        $price= $objAuct->calculatePriceID($auction_id);
        $availability=$this->getAvailable($auction_id,$auction->amount);
        return view('landing3.subasta', compact('auction','price','availability'));

    }

    public function getAvailable($auction_id, $amountTotal){
        $sold = 0;
        $data = array();
        $bids = Bid::Select()
            ->where('status','<>',\App\Bid::NO_CONCRETADA)
            ->where('auction_id',$auction_id)
            ->get();

        foreach ($bids as $b) {
            $sold+= $b->amount;
        }
        $available = $amountTotal-$sold;
//        dd($disponible);
        $data['available'] = $available;
        $data['sold'] = count($bids);
        return $data;
    }


    public function offerForSale($auction, $offer)
    {
        $auction_id = $auction->id;
        $prices = $offer->price;
        $resp  =  array();
        $price = str_replace(",","",$prices);
        $available = $this->getAvailable($auction_id, $auction->amount);

        if ($available['available'] > 0){
            if ($price <= $auction->end_price)
                return;

            //Datos de envio de correo
            $unit = $auction->batch->product->unit;
            $caliber = $auction->batch->caliber;
            $quality = $auction->batch->quality;
            $product = $auction->batch->product->name;
            $resp['isnotavailability'] = 0;
            $resp['unit'] = trans('general.product_units.'.$unit);
            $resp['caliber'] = $caliber;
            $resp['quality'] = $quality;
            $resp['product'] = $product;
            $resp['amount'] = $available['available'];
            $resp['price'] = $price;
            $resp['active'] = $auction->active;

//Guardo la venta
            $this->bid = new Bid();
            $this->bid->user_id = $offer->user_id;
            $this->bid->auction_id = $auction_id;
            $this->bid->amount = $available['available'];
            $this->bid->price = $prices;
            $this->bid->status = Bid::PENDIENTE;
            $this->bid->bid_date = date('Y-m-d H:i:s');
            $this->bid->save();

//Update status oferta
            $this->offers = Offers::findOrFail($offer->id);
            $this->offers->auction_id = $auction_id;
            $this->offers->status = Offers::ACEPTADA;
            $this->offers->save();

//Update batch_statuses
//        $this->status = Batch::findOrFail($auction->batch_id)->status;
//        $this->status->assigned_auction -= $available['available'];
//        $this->status->auction_sold += $available['available'];
//        $this->status->save();

            $user = User::findOrFail(Auth::user()->id);
            $template = 'emails.offerForBid';
            $seller = $auction->batch->arrive->boat->user ;
            Mail::queue($template, ['user' => $user , 'seller'=> $seller, 'product'=> $resp] , function ($message) use ($user) {
                $message->from(
                    env('MAIL_ADDRESS_SYSTEM','sistema@subastas.com.ar'),
                    env('MAIL_ADDRESS_SYSTEM_NAME','Subastas')
                );
                $message->subject(trans('users.offer_Bid'));
                $message->to($user->email);
            });
            return true;
        } else
            return false;
    }

    public function getOffers($auction_id)
    {
        return Offers::Select(
            'auctions_offers.id',
            'auctions_offers.auction_id',
            'auctions_offers.price',
            'auctions_offers.status',
            'auctions.end_price',
            'auctions.end AS FinSubasta',
            'batches.caliber',
            'batches.quality',
            'products.name AS Producto',
            'auctions_offers.user_id'/*,
            'users.name AS Comprador'*/
        )
            ->join('auctions','auctions.id','=','auction_id')
            ->join('batches','batches.id','=','auctions.batch_id')
            ->join('products','products.id','=','batches.product_id')
            ->join('users','users.id','=','auctions_offers.user_id')
            ->where('auctions_offers.auction_id','=',$auction_id)
            ->orderBy('auctions_offers.price','desc')
            ->get();
    }

    public function getCurrentTime()
    {
        return date('Y-m-d H:i:s');
    }


}
