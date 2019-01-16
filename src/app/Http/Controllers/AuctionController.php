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
use App\Ports;
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
        $auction_id = $request->input('auction_id');
        $bidDate = date('Y-m-d H:i:s');
        $auction = Auction::findOrFail($auction_id);
        $prices = $auction->calculatePrice($bidDate);
        $price = number_format(str_replace(",","",$prices),2,',','');
        if($request->get("i") and $request->get('i')=='c'){
            $targetprice=$auction->target_price;
            $close=($price<$targetprice)?1:0;
            $availabilityboth=$this->getAuctionAvailability($auction_id,$auction->amount);
            $availability=$availabilityboth['availability'];
            $offerscounter=$availabilityboth['offerscounter'];
            $time=round(microtime(true) * 1000);
            setlocale(LC_TIME,'es_ES');
            $fechafin=strftime('%d %b %Y', strtotime($auction->end));
            return json_encode(array(
                'id'=>$auction_id,
                'price'=>$price,
                'close'=>$close,
                'end'=>$auction->end,
                'endfriendly'=>$fechafin,
                'availability'=>$availability,
                'offerscounter'=>$offerscounter,
                'currenttime'=>$time
            ));
        }else{
            return $price;
        }

    }
    public function calculatePriceID($id)
    {
        $bidDate = date('Y-m-d H:i:s');
        $auction = Auction::findOrFail($id);
        $prices = $auction->calculatePrice($bidDate);
        $price = number_format(str_replace(",","",$prices),2,',','');
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
	 function getAuctionAvailability($auction_id,$amount){
         $bids = Bid::where('auction_id' , $auction_id )->get();
         $availability=$amount;$amounts=0;
         foreach($bids as $bid){
             $amounts+=$bid->amount;
         }
         $availability-=$amounts;
         return array(
             'availability'=>$availability,
             'offerscounter'=>count($bids)
         );
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
            $availabilityboth=$this->getAuctionAvailability($auction_id,$auction->amount);
            $availability=$availabilityboth['availability'];
            $offerscounter=$availabilityboth['offerscounter']+1;
			$bidDate = date('Y-m-d H:i:s');
			$prices = $auction->calculatePrice($bidDate);
            $price = str_replace(",","",$prices);
			DB::beginTransaction();
				if ($amount > 0 && $amount <= $availability  )
				{
					$auction->makeBid($amount,$price);
					$unit = $auction->batch->product->unit;
					$product = $auction->batch->product->name;
					$total = $amount * $price;
					$resp['isnotavailability'] = 0;
                    $resp['availability'] = $availability-$amount;
					$resp['unit'] = trans('general.product_units.'.$unit);
					$resp['product'] = $product;
					$resp['amount'] = $amount;
					$resp['price'] = $price;
					$resp['totalAmount']=$auction->amount;
					$resp['offerscounter']=$offerscounter;
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
		
		$auction_id = $request->get('auction_id',null);
		$invited = $request->get('invited',null);
		$type = $request->get('type',"all");
		
		$auctions = Auction::filterAndPaginate($status,$product,$seller,$boat,$type,true);
//		$auctions=Auction::auctionPrivate(Auth::user()->id,$status);
//        dump($auctions);
//        $auctions = Auction::getClosingAuction();
        $array_auctions = [];

		$products = array();
		$sellers =  array();
		$boats = array();
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
    public function getAuctionsDataForHome($auctions,$return){
        $auctionsreturn=array();$userRating =  array();$usercat=array();$port=array();$products=array();$calibers=array();$users=array();$price=array();
        foreach($auctions as $a) {
            $bids = Bid::where('auction_id', $a->id)->get();
            $availability = $a->amount;
            $amounts = 0;
            foreach ($bids as $bid) {
                $amounts += $bid->amount;
            }
            $availability -= $amounts;
            if ($availability > 0) {
                $user = $a->batch->arrive->boat->user;
                $ratings = $user->rating;
                $total = ($ratings != null) ? ($ratings->positive + $ratings->negative + $ratings->neutral) : 0;
                $userRating[$user->id] = ($ratings != null and $total > 0) ? (round(($ratings->positive * 100) / $total, 2)) : 0;
                $usercat[$user->id] = Auction::catUserByAuctions($user->id);
                $price[$a->id] = $this->calculatePriceID($a->id);
                $auctionsreturn[] = $a;
                if ($return != null) {

                }
                if (isset($caliber[$a->batch->caliber]['cant'])) {
                    $calibers[$a->batch->caliber]++;
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
        }
        return array(
            'auctions'=>$auctionsreturn,
            'products'=>$products,
            'ports'=>$port,
            'calibers'=>$calibers,
            'users'=>$users,
            'usercat'=>$usercat,
            'userrating'=>$userRating,
            'prices'=>$price
        );
    }
    public function subastasDestacadasHome($return=4)
    {
        $auctions = array();$finishedauctions = array();$userRating =  array();$usercat=array();$port=array();$products=array();$calibers=array();$users=array();$price=array();
        $auctions1 = Auction::auctionHome()[0];
        $auctiondetails1=$this->getAuctionsDataForHome($auctions1,$return);
        $auctions2 = Auction::auctionHome()[1];
        $auctiondetails2=$this->getAuctionsDataForHome($auctions2,$return);
        if ($return == 4) {
            $auctions3 = Auction::auctionHome()[2];
            $auctiondetails3=$this->getAuctionsDataForHome($auctions3,$return);
            $auctions4 = Auction::auctionHome()[3];
            $auctiondetails4=$this->getAuctionsDataForHome($auctions4,$return);
        }
        $sellers = User::filter(null, array(User::VENDEDOR), array(User::APROBADO));
        $buyers = User::filter(null, array(User::COMPRADOR), array(User::APROBADO));
        $boats = Boat::Select()->get();

        for($z=1;$z<=$return;$z++){
            $var="auctiondetails$z";
            foreach(${$var}['auctions'] as $item){
                $var2=($z<3)?'auctions':'finishedauctions';
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
        }
        if($return==4){
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
                'sellers'=>$sellers);
        }
    }
    public function listaSubastas(){
	    $all=$this->subastasDestacadasHome(2);
        return view('/landing3/subastas')
            ->withAuctions($all['auctions'])
            ->withUserrating($all['userRating'])
            ->withPorts($all['ports'])
            ->withUsercat($all['usercat'])
            ->withPrice($all['price'])
            ->withBoats($all['boats'])
            ->withusers($all['users'])
            ->withCaliber($all['caliber'])
            ->withProducts($all['products'])
            ;
    }
    public function getParticipantes(Request $request){

	    $auction = Auction::find($request->get("auctions"));
	    return $auction->userInvited;
    }


    //Obtener el puerto por id
    static public function getPortById($port_id){

        $ports = Ports::Select('name')->where('id','=',$port_id)->get();
        echo $ports[0]['name'];

    }















}
