<?php

namespace App\Http\Controllers;

use App\Auction;
use App\Bid;
use App\Constants;
use App\Vendedor;
use App\AuctionQuery;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SellerAuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('seeSellerAuction', Auction::class);
        $status = $request->get(Constants::STATUS,Constants::MY_IN_CURSE);
        $auctions = AuctionQuery::filterAndPaginate($status);
        $sellerAuction = true;
        return view('auction.index',compact(Constants::AUCTIONS,'sellerAuction',Constants::STATUS));
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

    public function sales(Request $request)
    {
        $bids = [];
        $buyers = [];
        $total = 0;
        if (Auth::user()->seller != null){
            $bids = Auth::user()->seller->mySales($request->get(Constants::STATUS),$request->get('buyer'))->orderBy('bid_date','desc');
            $buyers = clone $bids;
            $total = Auth::user()->seller->getTotalSales();
            $buyers = $buyers->select('bids.user_id')->distinct()->get();
        }

        $request->session()->put('url.intended', '/sales');
        return view('sales.index',compact('bids','buyers','total','request'));
    }
	
	public function  privatesales(Request $request)
	{
   /*     if (Auth::user()->type == \App\User::VENDEDOR) {*/
            $sales = Auth::user()->seller->myPrivateSales($request->get('comprador'));
            $buyers = clone $sales;
            $total = Auth::user()->seller->getTotalPrivateSales();
            $buyers = $buyers->select(Constants::BUYER_NAME)->distinct(Constants::BUYER_NAME)->where(Constants::BUYER_NAME, '<>', 'null  ')->get();

            return view('sales.private', compact('sales', 'total', 'buyers', 'request'));
       /* } else {
            return redirect('/home');
        }*/
	}
}
