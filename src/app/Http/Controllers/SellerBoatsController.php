<?php

namespace App\Http\Controllers;

use App\Arrive;
use App\Batch;
use App\BatchStatus;
use App\Boat;
use App\Http\Requests\BatchRequest;
use App\Http\Requests\CreateArriveRequest;
use App\Http\Requests\CreateBoatRequest;
use App\Http\Requests\PrivateSaleRequest;
use App\Http\Requests\UpdateArriveRequest;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SellerBoatsController extends Controller
{

    public function __construct()
    {
        $this->beforeFilter('@findBoat',['only'=>['show','edit','update','destroy']]);
    }

    public function findBoat(Route $route)
    {
        $this->boat = Boat::findOrFail($route->getParameter('boat'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('seeSellerBoats', new Boat());

        $barcos = Boat::where('user_id',Auth::user()->id)->get();
        return view('sellerBoats.index',compact('barcos','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('addBoat', new Boat());

        return view('sellerBoats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBoatRequest $request)
    {
        $this->authorize('addBoat', new Boat());

        $boat = new Boat();
        $boat->name = $request->input('name');
        $boat->matricula = $request->input('matricula');
        $boat->status = Boat::PENDIENTE;
        $boat->user_id = Auth::user()->id;
        $boat->save();

        $request->session()->flash('confirm_msg', trans('sellerBoats.boat_added_msg'));
        return redirect()->route('sellerboat.index');
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

    public function arrive($boat_id)
    {
        $boats = Boat::where('user_id',Auth::user()->id)
            ->where('status',Boat::APROBADO)
            ->get();

        return view('sellerBoats.arrive',compact('boats','boat_id'));
    }

    public function editArrive($arrive_id)
    {
        $arrive = Arrive::findOrFail($arrive_id);
        $this->authorize('editArrive', $arrive);

        $boats = Boat::where('user_id',Auth::user()->id)
            ->where('status',Boat::APROBADO)
            ->get();

        return view('sellerBoats.editArrive',compact('arrive','boats'));
    }

    public function updateArrive(UpdateArriveRequest $request)
    {
        $arrive = Arrive::findOrFail($request->input('id'));
        $this->authorize('editArrive', $arrive);

        $arrive->date = $request->input('date');
        $arrive->save();

        return redirect('sellerbatch');
    }

    public function storeArrive(CreateArriveRequest $request)
    {
        $boat = Boat::findOrFail($request->input('barco'));
        $this->authorize('addBoatArrive', $boat);

        $arrive = new Arrive();
        $arrive->boat_id = $request->input('barco');
        $arrive->date = $request->input('date');
        $arrive->save();

        return redirect('home');
    }

    public function batch($arrive_id)
    {
        $this->checkBatch($arrive_id);

        $boat = Arrive::findOrFail($arrive_id)->boat;
        $this->authorize('createBatch', $boat);

        $products = Product::all();
        $calibers = Product::caliber();
        return view('sellerBoats.batch',compact('boat','products','calibers','arrive_id'));
    }

    public function storeBatch(Request $request)
    {
        $this->checkBatch($request->input('id'));
        $boat = Arrive::findOrFail($request->input('id'))->boat;
        $this->authorize('createBatch', $boat);

        $products = $request->input('product');
        $caliber = $request->input('caliber');
        $quality = $request->input('quality');
        $amount = $request->input('amount');

        if(!empty($products)){
            foreach ($products as $k => $prod) {
                $batch = new Batch();
                $batch->arrive_id = $request->input('id');
                $batch->product_id = $prod;
                $batch->caliber = $caliber[$k];
                $batch->quality = $quality[$k];
                $batch->amount = $amount[$k];
                $batch->save();
				
				$batchStatus = new BatchStatus();
				$batchStatus->batch_id = $batch->id;
				$batchStatus->assigned_auction = 0 ; 
				$batchStatus->auction_sold = 0 ; 
				$batchStatus->private_sold = 0 ; 
				$batchStatus->remainder = $batch->amount ; 
				$batchStatus->save();
				
            }
        }

        return redirect('sellerbatch');
    }

    public function deleteBatch($id)
    {
        $batch = Batch::findOrFail($id);
        $this->authorize('deleteBatch', $batch);

        $batch->status->delete();
        $batch->delete();

        return redirect('/sellerbatch');
    }

    private function checkBatch($arrive_id){
        $authorize = DB::table('arrives')
            ->join('boats','arrives.boat_id','=','boats.id')
            ->where('arrives.id',$arrive_id)
            ->where('boats.user_id',Auth::user()->id)->count();

        if(!$authorize){
            throw new \Exception('El arribo no corresponde a un barco del usuario');
        }

        return true;
    }
	
	
	public function editbatch(Request $request)
	{
		 $batch = Batch::findOrFail($request->input('hBatchId'));
		 $newamount = $request->input('amount');
		 $this->authorize('isMyBatch',$batch);
		 $assigned_auction = $batch->status->assigned_auction;
		 $auction_sold = $batch->status->auction_sold;
		 $private_sold = $batch->status->private_sold;
		 $total_sold = $auction_sold  + $private_sold;
		 $minEditBatch = $assigned_auction + $total_sold;
		 
		 if ($newamount >= $minEditBatch  )
		 {
			
			$value = $newamount - $batch->amount;
			$batch->amount = $newamount;
			$batch->save();
			$batch->status->remainder = $batch->status->remainder + $value;
			$batch->status->save();
			
			$request->session()->flash('confirm_msg_editbach', trans('sellerBoats.batch_edit_msg'));
		 }
		 
		 
		 return redirect('sellerbatch');
		 
	}

    public function priavateSale($batch_id)
    {
        $batch = Batch::findOrFail($batch_id);
        $this->authorize('makeDirectBid', $batch);

        return view('bid.direct',compact('batch'));
    }

    public function savePrivateSale(PrivateSaleRequest $request)
    {
        $batch = Batch::findOrFail($request->input('id'));
        $this->authorize('makeDirectBid', $batch);
        $importe = str_replace(',','.',$request->input('importe'));
        $batch->makePrivateSale(  $request->input('amount'),
            $importe ,
            $request->input('comprador')
        );

        return redirect('sellerbatch');

    }

    public function buyersName()
    {
        $sales = Auth::user()->seller->myPrivateSales();

        $buyersNames = $sales->select('buyer_name')->distinct('buyer_name')->where('buyer_name','<>','null  ')->get();
        $rtrn = [];
        if(Auth::user()->isSeller()){
            foreach($buyersNames as $name){
                $rtrn[] = $name['buyer_name'];
            }
        }
//        dd(Auth::user()->isBuyer());

        return json_encode($rtrn);

    }
	
}
