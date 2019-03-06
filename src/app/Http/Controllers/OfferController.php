<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\AuctionQuery;
use App\Constants;
use App\Auction;
use App\Offers;
use App\Batch;
use App\Ports;
use App\Boat;
use App\User;
use App\Bid;
use Auth;
use App;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Constants::MY_IN_CURSE;
        $auctions = AuctionQuery::filterAndPaginate($status,null,null,null);
        $offers = array();
        foreach($auctions as $a){
            $offers[$a->id] = $this->getOffers($a->id);
        }


        return response()->json($a);
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


    public function getOffers($auction_id = null){
        $rtn = Offers::Select(
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
            ->join(Constants::USERS,Constants::USERS_ID,'=','auctions_offers.user_id');
        if ($auction_id == null){
            $rtn = $rtn->where('auctions_offers.auction_id','=',$auction_id);
        }
            $rtn = $rtn->orderBy('auctions_offers.price','desc')
            ->orderBy('auctions_offers.created_at','asc')
            ->get();

        return $rtn;
    }
}
