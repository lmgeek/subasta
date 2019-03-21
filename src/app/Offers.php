<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\priceTrait;
use Carbon\Carbon;
use Auth;
use App\Constants;
use App\AuctionQuery;

class Offers extends Model
{
    use priceTrait;
	protected $table = 'auctions_offers';
	
	protected $fillable = ['id', 'auction_id','user_id','amount','price','status'];

	const NO_ACEPTADA = 'rejected';
	const ACEPTADA = 'accepted';
	const PENDIENTE = 'pending';


	public function auction(){
        return $this->belongsTo('App\Auction');
    }

	public function user()
	{
		return $this->belongsTo('App\User');
	}
    
    public static function getOffersByBuyer($userid,$paginate=null,$limit=Constants::PAGINATE_NUMBER,$status=null){
        $return=AuctionQuery::select(
                'auctions.correlative',
                'auctions.created_at as StartDateAuction',
                'products.name',
                'product_detail.caliber',
                'product_detail.presentation_unit',
                'product_detail.sale_unit',
                'auctions_offers.price',
                'auctions_offers.status',
                'auctions_offers.created_at'
                )
                ->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)
                ->join('product_detail', 'product_detail.id', Constants::EQUAL,'batches.product_detail_id')
                ->join('products','products.id', Constants::EQUAL,'product_detail.product_id')
                ->join('auctions_offers', 'auctions_offers.auction_id', Constants::EQUAL,'auctions.id')
                ->where('auctions_offers.user_id', Constants::EQUAL,$userid)
                ;
        if($status!=null){
            $return=$return->where(Constants::STATUS,Constants::EQUAL,$status);
        }
        $return=$return->orderBy('auctions_offers.created_at','DESC');
        if($paginate==null){
            if($limit==null){
                return $return->get();
            }else{
                return $return->limit($limit)->get();
            }
        }else{
            return $return->paginate($limit);
        }
    }

}
