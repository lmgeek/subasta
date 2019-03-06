<?php
namespace App;
use Auth;
class AuctionQuery extends Auction{
   /* INI Rodolfo*/
    public static function checkifUserInvited($id){
        if(!isset(Auth::user()->id)){
            return 0;
        }
        $auction=AuctionInvited::select('*')
            ->where(Constants::AUCTION_INV_AUCTION_ID, Constants::EQUAL,$id)
            ->where('user_id', Constants::EQUAL,Auth::user()->id)->get();
        return (count($auction)>0)? Constants::ACTIVE: Constants::INACTIVE;
    }

    public static function catUserByAuctions($iduser){
        $cant=count(Auction::select( Constants::AUCTIONS_SELECT_ALL)
            ->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)
            ->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL,Constants::ARRIVES_ID)
            ->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL,Constants::BOATS_ID)
            ->where('boats.user_id', Constants::EQUAL,$iduser)->get());
        if($cant<Constants::CANT_MAX_BRONZE){
            return 'Bronze';
        }elseif($cant>=Constants::CANT_MAX_BRONZE && $cant<Constants::CANT_MAX_SILVER){
            return 'Silver';
        }else{
            return 'Gold';
        }
    }
    /**
     * AuctionsQueryBuilder: 
     *      This function just builds the query to fit all the needs of information
     *      for auctions
     * @param $params=array(
     *                  'auctionid'=>$valor, If a specific auction is needed
     *                  'idtoavoid'=>$valor, If in case of a load more, you can send the ids that you already have
     *                  'batch/product/...id'=>$valor,   If you need to get all the auctions of a batch or whatever
     *                  'orderby'=>$valor, In case you need to order the results by a specific column
     *                  'order'=>$valor,   If you add the orderby, you might need to add the direction of order
     *                  'paginate'=>$valor, If you send something, it will paginate, if not, just get all.
     *                  );
     * @return The auction(s) with all the information associated with it
     */
   
    public static function AuctionsQueryBuilder($params=null,$dump=false){
        $auctions = Auction::select('auctions.*')
            ->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)
            ->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL, Constants::ARRIVES_ID)
            ->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL, Constants::BOATS_ID)
            ->join(Constants::USERS, Constants::BOATS_USER_ID, Constants::EQUAL, Constants::USERS_ID)
            ;
        if(isset($params[Constants::AUCTIONID])){
            $auctions=$auctions->where(Constants::AUCTIONS_ID, Constants::EQUAL,$params['auctionid']);
        }
        if(isset($params[Constants::IDTOAVOID])){
            $auctions=$auctions->whereNotIn(Constants::AUCTIONS_ID,$params['idtoavoid']);
        }
        if(isset($params[Constants::BATCHID])){
            $auctions=$auctions->where(Constants::AUCTIONS_BATCH_ID, Constants::EQUAL,$params['batchid']);
        }
        if(isset($params[Constants::BOATID])){
            $auctions=$auctions->where(Constants::BOATS_ID, Constants::EQUAL,$params['boatid']);
        }
        if(isset($params[Constants::SELLERID]) && is_array($params[Constants::SELLERID])){
            $auctions=$auctions->whereIn('users.id',$params[Constants::SELLERID]);
        }elseif(isset($params[Constants::SELLERID]) && !is_array($params[Constants::SELLERID])){
            $auctions=$auctions->where('users.id', Constants::EQUAL,$params[Constants::SELLERID]);
        }
        if(isset($params[Constants::PRODUCTID])){
            $ports=$params[Constants::PRODUCTID];
            if(is_array($ports)){
                $auctions=$auctions->whereIn(Constants::BATCHES_PRODUCT_ID,$params['productid']);
            }else{
                $auctions=$auctions->where(Constants::BATCHES_PRODUCT_ID, Constants::EQUAL,$params['productid']);
            }
        }
        if(isset($params[Constants::QUALITY])){
            $ports=$params[Constants::QUALITY];
            if(is_array($ports)){
                $auctions=$auctions->whereIn('batches.quality',$params['quality']);
            }else{
                $auctions=$auctions->where('batches.quality', Constants::EQUAL,$params['quality']);
            }
        }
        if(isset($params[Constants::PORTID])){
            $ports=$params[Constants::PORTID];
            if(is_array($ports)){
                $auctions=$auctions->whereIn('arrives.port_id',$params['portid']);
            }else{
                $auctions=$auctions->where('arrives.port_id', Constants::EQUAL,$params['portid']);
            }
        }
        if(isset($params[Constants::CALIBER])){
            $ports=$params[Constants::CALIBER];
            if(is_array($ports)){
                $auctions=$auctions->whereIn('batches.caliber',$params['caliber']);
            }else{
                $auctions=$auctions->where('batches.caliber', Constants::EQUAL,$params['caliber']);
            }
        }
        $orderby=(isset($params['orderby']))?$params['orderby']:'end';
        $order=(isset($params['order']))?$params['order']:'asc';
        $auctions=$auctions->where(Constants::ACTIVE_LIT, Constants::EQUAL, Constants::ACTIVE)
                ->where('auctions.deleted_at','=',NULL)
                ->orderBy($orderby,$order);
        if($dump){
            echo Constants::getRealQuery($auctions);die();
        }
        if(empty($params['paginate'])){
            return $auctions->get();
        }else{
            return $auctions->paginate($params['paginate']);
        }
    }
    public static function auctionTimeSplitter($auctions){
        
        $return=array('all'=>array(),Constants::FINISHED=>array(), Constants::IN_CURSE=>array(), Constants::FUTURE=>array(),'mine'=>array('all'=>array(),Constants::FINISHED=>array(), Constants::IN_CURSE=>array(), Constants::FUTURE=>array()));
        foreach($auctions as $auction){
            $availability=self::getAvailable($auction->id,$auction->amount)['available'];
            $invitation=self::checkifUserInvited($auction->id);
            $timeline= Constants::getTimeline($auction->start, $auction->end, $availability);
            if(isset(Auth::user()->id) && $auction->batch->arrive->boat->user->id==Auth::user()->id){
                $return['mine'][$timeline][]=$auction;
                $return['mine']['all'][]=$auction;
            }
            $auction->code= Http\Controllers\AuctionController::getAuctionCode($auction->correlative, $auction->created_at);
            $auction->timeline=$timeline;
            if($auction->type==Constants::AUCTION_PUBLIC){
                $return[$timeline][]=$auction;
                $return['all'][]=$auction;
            }elseif($auction->type==Constants::AUCTION_PRIVATE && ($invitation==Constants::ACTIVE || (isset(Auth::user()->id) && $auction->batch->arrive->boat->user->id==Auth::user()->id))){
                $return[Constants::AUCTION_PRIVATE][$timeline][]=$auction;
                $return[Constants::AUCTION_PRIVATE]['all'][]=$auction;
                $return[$timeline][]=$auction;
                $return['all'][]=$auction;
            }
        }
        return $return;
    }
    public static function auctionHome($ids=null,$filters=null,$time=null){
        if($ids!=null){
            $filters['idtoavoid']=$ids;
        }
        $return=self::auctionTimeSplitter(self::AuctionsQueryBuilder($filters));
        if($time==null){
            return $return;
        }elseif(isset ($filters['type']) && isset(Auth::user()->type) && Auth::user()->type==Constants::VENDEDOR){
            return $return['mine'][$time];
        }else{
            return $return[$time];
        }
        
        
    }
    /* FIN Rodolfo*/
    public static function filterAndPaginate($status , $product = null , $seller = null , $boat = null , $type = Constants::AUCTION_PUBLIC , $withStock = false){
        $now =date(Constants::DATE_FORMAT);
        switch ($status){
            case Constants::FINISHED:
                $rtrn = Auction::where('end','<=',$now)->where(Constants::AUCTIONS_TYPE, Constants::EQUAL,$type)->orderBy(Constants::START,'DESC');
                break;
            case Constants::FUTURE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID);
                if ( null != $product  ) {
                    $rtrn = $rtrn->whereIn(Constants::BATCHES_PRODUCT_ID,$product);
                }
                if ( null != $seller || null != $boat ) {
                    $rtrn = $rtrn->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL, Constants::ARRIVES_ID)->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL, Constants::BOATS_ID)->join(Constants::USERS, Constants::BOATS_USER_ID, Constants::EQUAL, Constants::USERS_ID);
                    if (null != $seller) {
                        $rtrn = $rtrn->whereIn(Constants::USERS_ID,$seller);
                    }
                    if (null != $boat) {
                        $rtrn = $rtrn->whereIn(Constants::BOATS_ID,$boat);
                    }

                }
                $rtrn = $rtrn->where(Constants::START,'>',$now)->where(Constants::AUCTIONS_TYPE, Constants::EQUAL,$type)->orderBy(Constants::START,'DESC');
                break;
            case Constants::MY_IN_CURSE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL, Constants::ARRIVES_ID)->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL, Constants::BOATS_ID)->join(Constants::USERS, Constants::BOATS_USER_ID, Constants::EQUAL, Constants::USERS_ID)->where(Constants::USERS_ID,Auth::user()->id)->where(Constants::AUCTIONS_START,'<',$now)->where(Constants::AUCTIONS_END,'>',$now)->orderBy(Constants::AUCTIONS_END,'desc');
                break;
            case Constants::MY_FUTURE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL, Constants::ARRIVES_ID)->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL, Constants::BOATS_ID)->join(Constants::USERS, Constants::BOATS_USER_ID, Constants::EQUAL, Constants::USERS_ID)->where(Constants::USERS_ID,Auth::user()->id)->where(Constants::AUCTIONS_START,'>',$now)->orderBy('auctions.created_at','desc');
                break;
            case Constants::MY_FINISHED:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID)->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL, Constants::ARRIVES_ID)->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL, Constants::BOATS_ID)->join(Constants::USERS, Constants::BOATS_USER_ID, Constants::EQUAL, Constants::USERS_ID)->where(Constants::USERS_ID,Auth::user()->id)->where(Constants::AUCTIONS_END,'<=',$now)->orderBy('auctions.created_at','desc');
                break;
            default:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES, Constants::AUCTIONS_BATCH_ID, Constants::EQUAL, Constants::BATCH_ID);
                if ( null != $product  ) {
                    $rtrn = $rtrn->whereIn(Constants::BATCHES_PRODUCT_ID,$product);
                }
                if ( null != $seller || null != $boat ) {
                    $rtrn = $rtrn->join(Constants::ARRIVES, Constants::BATCH_ARRIVE_ID, Constants::EQUAL, Constants::ARRIVES_ID)->join(Constants::BOATS, Constants::ARRIVES_BOAT_ID, Constants::EQUAL, Constants::BOATS_ID)->join(Constants::USERS, Constants::BOATS_USER_ID, Constants::EQUAL, Constants::USERS_ID);
                    if (null != $seller) {
                        $rtrn = $rtrn->whereIn(Constants::USERS_ID,$seller);
                    }
                    if (null != $boat) {
                        $rtrn = $rtrn->whereIn(Constants::BOATS_ID,$boat);
                    }
                }
                $rtrn = $rtrn->where(Constants::START,'<',$now)->where('end','>',$now)->where(Constants::AUCTIONS_TYPE, Constants::EQUAL,$type)->where(Constants::ACTIVE_LIT, Constants::EQUAL, Constants::ACTIVE)->orderBy('end','asc');
                break;
        }
        if ($withStock){
            $array_auctions = [];
            $rtrn = $rtrn->get();
            foreach ($rtrn as $value){
                $bids = $value->bids;
                if (!empty($bids)){
                    $amount = 0;
                    foreach ($bids as $bid){
                        $amount += intval($bid->amount);
                    }
                    if ($amount < $value->amount){
                        $array_auctions[] = $value;
                    }
                }
                else {
                    $array_auctions[] = $value;
                }
            }
            $rtrn = collect($array_auctions);
        }
        else {
            $rtrn = $rtrn->paginate();
        }
        return $rtrn;
    }
}
