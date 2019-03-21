<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\priceTrait;
use App\Constants;
use App\AuctionQuery;

class Bid extends Model
{
    use priceTrait;
	protected $table = 'bids';
	
	protected $fillable = ['user_id', 'auction_id','amount','price','bid_date','status','reason','user_calification','user_calification_comments','weight','buyer_name','offer_id'];



	public function auction(){
        return $this->belongsTo('App\Auction');
    }

	public function user()
	{
		return $this->belongsTo('App\User');
	}
	
	public function getTotalByUser($user)
	{
		return Bid::where(Constants::BIDS_USER_ID, $user->id )->orderBy(Constants::BIDS_DATE, Constants::DESC)->where(Constants::STATUS,Constants::CONCRETADA)->sum('price');
	}
	
	public function getTotalPendienteByUser($user)
	{
		return Bid::where(Constants::BIDS_USER_ID, $user->id )->orderBy(Constants::BIDS_DATE, Constants::DESC)->where(Constants::STATUS,Constants::PENDIENTE)->count('id');
	}
	
    public static function getBidsByBuyer($userid,$paginate=null,$limit=Constants::PAGINATE_NUMBER,$status=null){
        $return=AuctionQuery::select(
                'auctions.correlative',
                'auctions.created_at as StartDateAuction',
                'products.name',
                'product_detail.caliber',
                'product_detail.presentation_unit',
                'product_detail.sale_unit',
                'bids.price',
                'bids.status',
                'bids.bid_date',
                'bids.amount',
                'bids.reason',
                'bids.user_calification',
                'bids.user_calification_comments',
                'bids.seller_calification',
                'bids.seller_calification_comments'
                )
                ->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)
                ->join('product_detail', 'product_detail.id', Constants::EQUAL,'batches.product_detail_id')
                ->join('products','products.id', Constants::EQUAL,'product_detail.product_id')
                ->join('bids', 'bids.auction_id', Constants::EQUAL,'auctions.id')
                ->where('bids.user_id', Constants::EQUAL,$userid)
                ;
        if($status!=null){
            $return=$return->where(Constants::STATUS,Constants::EQUAL,$status);
        }
        $return=$return->orderBy('bids.bid_date','DESC');
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
