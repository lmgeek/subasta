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

class AuctionBackController extends AuctionController
{
    public static function getParticipantes(Request $request){

	    $auction = Auction::find($request->get("auction"));
	    return $auction->userInvited;
    }
    
    public static function getOffersCount($id){
        $offers=Offers::Select()->where(Constants::INPUT_AUCTION_ID,'=',$id)->get();
        return count($offers);
    } 
    public static function getAvailable($auction_id, $amountTotal){
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
    public function subscribeUser($auction_id)
	{
		$auction = Auction::findOrFail($auction_id);
		$auction->subscribeUser(Auth::user());

		return redirect('auction?status='.Constants::FUTURE);
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
    public function deactivate($auction_id)
	 {
		 $now = strtotime(date(Constants::DATE_FORMAT));
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
    public function qualifyBid($bid_id)
    {
        $bid = Bid::findOrFail($bid_id);
        return view('bid.qualify',compact('bid'));
    }
    public function buyerBid(Request $request)
	 {
		$user = Auth::user();
		if (Auth::user()->type == \App\User::COMPRADOR){
            $this->authorize('seeMyBids', Bid::class);
			$bids = Bid::where(Constants::USER_ID , $user->id )->orderBy('bid_date', 'desc')->paginate();
			return view('bid.index',compact('bids'));
		} else {
			return redirect('home');
		}

	 }
     public static function emailOfferBid($auction,$available,$offer)
    {
        $products = array($auction->batch->productDetail->product);
        //            dd($products);
        foreach ($products as $p){
            $name = $p[0]->name;
        }
        //Datos de envio de correo
        $unit = $auction->batch->productDetail->sale_unit;
        $caliber = $auction->batch->productDetail->caliber;
        $quality = $auction->batch->quality;
        $product = $name;
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
    public function getCurrentTime()
    {
        return json_encode(gmdate('D, M d Y H:i:s T\-0300', time()));
    }
}

