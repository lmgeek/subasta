<?php

namespace App\Http\Controllers;

use App\Auction;
use App\AuctionInvited;
use App\Batch;
use App\Bid;
use App\Boat;
use App\Http\Requests\CreateAuctionRequest;
use App\Http\Requests\ProcessBidRequest;
use App\Http\Requests\SellerQualifyRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Product;
use App\User;
use App\UserRating;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

		$auction_id = $request->get('auction_id',null);
		$invited = $request->get('invited',null);
		$type = $request->get('type',null);

		if ($type == Auction::AUCTION_PRIVATE)
		{	$user = Auth::user();
			$auctions = Auction::auctionPrivate($user->id , $status);
		}else{
			$auctions = Auction::filterAndPaginate($status,$product,$seller,$boat);
		}

		$products = array();
		$sellers =  array();
		$boats = array();
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

        $auction  = new Auction();
        $auction->batch_id = $request->input('batch');
        $auction->start = $startDate->format('Y-m-d H:i:s');
        $auction->start_price = $request->input("startPrice");
        $auction->end = $endDate->format('Y-m-d H:i:s');
        $auction->end_price = $request->input('endPrice');
        $auction->interval = $request->input('intervalo');
        $auction->amount = $request->input('amount');
		$auction->type = $request->input('tipoSubasta');
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
		$auction_id = $request->input('auction_id');
		$bidDate = date('Y-m-d H:i:s');
		$auction = Auction::findOrFail($auction_id);
		$prices = $auction->calculatePrice($bidDate);
         $price = str_replace(",","",$prices);
		
		return $price;
	 }

	 public function calculatePeso(Request $request)
	 {
		$product_id = $request->input('product_id');
		// dd($product_id);
		return $product_id;
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
	 
	 public function makeBid(Request $request)
	 {

		$auction_id = $request->input('auction_id');
		$amount = $request->input('amount');
		$auction = Auction::findOrFail($auction_id);
		$this->authorize('makeBid', $auction);
		$this->authorize('canBid',Auction::class);

		if ($auction->type == \App\Auction::AUCTION_PRIVATE )
		{
			$this->authorize('isInvited', $auction);
		}
	
		$resp  =  array();
		
		if ($auction->active == 0 )
		{
			$resp['active'] = $auction->active ;
			
		}else{
			$bidDate = date('Y-m-d H:i:s');
			$prices = $auction->calculatePrice($bidDate);
            $price = str_replace(",","",$prices);
			DB::beginTransaction();
				$availability = $auction->getAvailabilityLock();
				if ($amount > 0 && $amount <= $availability  )
				{
					$auction->makeBid($amount,$price);
					$unit = $auction->batch->product->unit;
					$product = $auction->batch->product->name;
					$total = $amount * $price;
					$resp['isnotavailability'] = 0;
					$resp['unit'] = trans('general.product_units.'.$unit);
					$resp['product'] = $product;
					$resp['amount'] = $amount;
					$resp['price'] = $price;
				}else{
					
					$resp['isnotavailability'] = 1;
					$resp['availability'] = $availability;
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
        $auction->end_price = $request->input('endPrice');
        $auction->interval = $request->input('intervalo');
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
		
		$auction_id = $request->get('auction_id',null);
		$invited = $request->get('invited',null);
		$type = $request->get('type',Auction::AUCTION_PUBLIC);
		
		
		$auctions = Auction::filterAndPaginate($status,$product,$seller,$boat,$type,true);
//        dump($auctions);
//        $auctions = Auction::getClosingAuction();
        $array_auctions = [];

		$products = array();
		$sellers =  array();
		$boats = array();
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
		
        return view('landing3/index',compact('auctions','status','products','sellers','request','boats','userRating','type'));
    }
    public function getParticipantes(Request $request){

	    $auction = Auction::find($request->get("auction"));
	    return $auction->userInvited;
    }


















}
