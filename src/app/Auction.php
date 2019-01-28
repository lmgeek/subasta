<?php
namespace App;
use App\Http\Traits\priceTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Bid;
use Nexmo\Call\Collection;
use App\Constants;
class Auction extends Model{
    use priceTrait;
    protected $table = 'auctions';
    protected $fillable = ['batch_id', 'start','start_price','end','end_price','interval','type','notification_status','description'];

    public function makeBid($amount , $price){
        $prices = str_replace(",","",$price);
        $this->bid = new Bid();
        $this->bid->user_id = Auth::user()->id;
        $this->bid->auction_id = $this->id;
        $this->bid->amount = $amount;
        $this->bid->price = $prices;
        $this->bid->status = Constants::PENDIENTE;
        $this->bid->bid_date = date(Constants::DATE_FORMAT);
        $this->bid->save();
        $this->status = $this->batch->status;
        $this->status->assigned_auction -= $amount;
        $this->status->auction_sold += $amount;
        $this->status->save();
    }
    /**
     * @param $amount
     * @param $price
     */
    public function offersAuction($amount , $price){
        $prices = str_replace(",","",$price);
        $this->offers = new Offers();
        $this->offers->auction_id = $this->id;
        $this->offers->user_id = Auth::user()->id;
        $this->offers->price = $prices;
        $this->offers->status = Constants::PENDIENTE;
        $this->offers->save();
        $this->status = $this->batch->status;
        $this->status->assigned_auction -= $amount;
        $this->status->auction_sold += $amount;
        $this->status->save();
    }
    public function getAvailability(){
        return $this->batch->status->assigned_auction;
    }
    public function getAvailabilityLock(){
        $data = \DB::table('batch_statuses')->where('batch_id', Constants::EQUAL, $this->batch->id)->lockForUpdate()->get();
        return $data[0]->assigned_auction;
    }

    public function subscription(){
        return $this->hasMany('App\Subscription');
    }

    public function batch()
    {
        return $this->belongsTo('App\Batch');
    }

    public function bids(){
        return $this->hasMany('App\Bid');
    }

    public function getTimeToNextInterval()
    {
        $now = Carbon::now()->timestamp;
        $position = $now % ($this->interval * 60);
        $segundosRestantes = ($this->interval * 60) - $position;
        return ($position == 0) ? $this->interval*60 : $segundosRestantes;

    }

    public function getAuctionDurationInMinutes()
    {
        return $this->getAuctionDuration(Constants::MINUTES);
    }

    public function getAuctionLeftTimeInMinutes()
    {
        return $this->getAuctionLeftTime(Constants::MINUTES);
    }

    public function getAuctionBids()
    {
        $sold = 0;
        foreach ($this->bids  as $bid)
        {
            $sold += $bid->amount;
        }
        return $sold;
    }

    private function getAuctionLeftTime($type){
        return ($type==Constants::MINUTES)?(Carbon::now()->diffInMinutes(Carbon::createFromFormat(Constants::DATE_FORMAT,$this->end))):(Carbon::now()->diffInSeconds(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->end)));
    }

    private function getAuctionDuration($type)
    {
        return ($type==Constants::MINUTES)?(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->start)->diffInMinutes(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->end))):(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->start)->diffInSeconds(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->end)));
    }

    public static function filterAndPaginate($status , $product = null , $seller = null , $boat = null , $type = Constants::AUCTION_PUBLIC , $withStock = false){
        $now =date(Constants::DATE_FORMAT);
        switch ($status){
            case Constants::FINISHED:
                $rtrn = Auction::where('end','<=',$now)->where(Constants::AUCTIONS_TYPE,Constants::EQUAL,$type)->orderBy(Constants::START,'DESC');
                break;
            case Constants::FUTURE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID);
                if ( null != $product  ) {
                    $rtrn = $rtrn->whereIn('batches.product_id',$product);
                }
                if ( null != $seller || null != $boat ) {
                    $rtrn = $rtrn->join(Constants::ARRIVES,Constants::BATCH_ARRIVE_ID,Constants::EQUAL,Constants::ARRIVES_ID)->join(Constants::BOATS,Constants::ARRIVES_BOAT_ID,Constants::EQUAL,Constants::BOATS_ID)->join(Constants::USERS,Constants::BOATS_USER_ID,Constants::EQUAL,Constants::USERS_ID);
                    if (null != $seller) {
                        $rtrn = $rtrn->whereIn(Constants::USERS_ID,$seller);
                    }
                    if (null != $boat) {
                        $rtrn = $rtrn->whereIn(Constants::BOATS_ID,$boat);
                    }

                }
                $rtrn = $rtrn->where(Constants::START,'>',$now)->where(Constants::AUCTIONS_TYPE,Constants::EQUAL,$type)->orderBy(Constants::START,'DESC');
                break;
            case Constants::MY_IN_CURSE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID)->join(Constants::ARRIVES,Constants::BATCH_ARRIVE_ID,Constants::EQUAL,Constants::ARRIVES_ID)->join(Constants::BOATS,Constants::ARRIVES_BOAT_ID,Constants::EQUAL,Constants::BOATS_ID)->join(Constants::USERS,Constants::BOATS_USER_ID,Constants::EQUAL,Constants::USERS_ID)->where(Constants::USERS_ID,Auth::user()->id)->where(Constants::AUCTIONS_start,'<',$now)->where(Constants::AUCTIONS_END,'>',$now)->orderBy(Constants::AUCTIONS_END,'desc');
                break;
            case Constants::MY_FUTURE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID)->join(Constants::ARRIVES,Constants::BATCH_ARRIVE_ID,Constants::EQUAL,Constants::ARRIVES_ID)->join(Constants::BOATS,Constants::ARRIVES_BOAT_ID,Constants::EQUAL,Constants::BOATS_ID)->join(Constants::USERS,Constants::BOATS_USER_ID,Constants::EQUAL,Constants::USERS_ID)->where(Constants::USERS_ID,Auth::user()->id)->where(Constants::AUCTIONS_start,'>',$now)->orderBy('auctions.created_at','desc');
                break;
            case Constants::MY_FINISHED:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID)->join(Constants::ARRIVES,Constants::BATCH_ARRIVE_ID,Constants::EQUAL,Constants::ARRIVES_ID)->join(Constants::BOATS,Constants::ARRIVES_BOAT_ID,Constants::EQUAL,Constants::BOATS_ID)->join(Constants::USERS,Constants::BOATS_USER_ID,Constants::EQUAL,Constants::USERS_ID)->where(Constants::USERS_ID,Auth::user()->id)->where(Constants::AUCTIONS_END,'<=',$now)->orderBy('auctions.created_at','desc');
                break;
            default:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID);
                if ( null != $product  ) {
                    $rtrn = $rtrn->whereIn('batches.product_id',$product);
                }
                if ( null != $seller || null != $boat ) {
                    $rtrn = $rtrn->join(Constants::ARRIVES,Constants::BATCH_ARRIVE_ID,Constants::EQUAL,Constants::ARRIVES_ID)->join(Constants::BOATS,Constants::ARRIVES_BOAT_ID,Constants::EQUAL,Constants::BOATS_ID)->join(Constants::USERS,Constants::BOATS_USER_ID,Constants::EQUAL,Constants::USERS_ID);
                    if (null != $seller) {
                        $rtrn = $rtrn->whereIn(Constants::USERS_ID,$seller);
                    }
                    if (null != $boat) {
                        $rtrn = $rtrn->whereIn(Constants::BOATS_ID,$boat);
                    }
                }
                $rtrn = $rtrn->where(Constants::START,'<',$now)->where('end','>',$now)->where(Constants::AUCTIONS_TYPE,Constants::EQUAL,$type)->where(Constant::ACTIVE_LIT,Constants::EQUAL,Constants::ACTIVE)->orderBy('end','asc');
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

    public static function catUserByAuctions($iduser){
        $cant=count(Auction::select( Constants::AUCTIONS_SELECT_ALL)->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID)->join(Constants::AUCTIONS_INVITES,Constants::AUCTIONS_ID,Constants::EQUAL,'auctions_invites.auction_id')->where('auctions_invites.user_id',Constants::EQUAL,$iduser));
        if($cant<500){
            return 'Bronze';
        }elseif($cant>=500 && $cant<1000){
            return 'Silver';
        }else{
            return 'Gold';
        }
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
        $data['available'] = $available;
        $data['sold'] = count($bids);
        return $data;
    }
    public static function checkifUserInvited($id){
        if(!isset(Auth::user()->id)){
            return 0;
        }
        $auction=AuctionInvited::select('*')
            ->where(Constants::AUCTION_INV_AUCTION_ID,Constants::EQUAL,$id)
            ->where('user_id',Constants::EQUAL,Auth::user()->id)->get();
        return (count($auction)>0)?Constants::ACTIVE:Constants::INACTIVE;
    }
    public static function auctionHome($ids=null){
        $now =date(Constants::DATE_FORMAT);
        $auctions = Auction::select( Constants::AUCTIONS_SELECT_ALL)
            ->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID)
            ->join(Constants::ARRIVES,Constants::BATCH_ARRIVE_ID,Constants::EQUAL,Constants::ARRIVES_ID)
            ->join('port','arrives.port_id',Constants::EQUAL,'port.id')
            ->where(Constants::ACTIVE_LIT,Constants::EQUAL,Constants::ACTIVE);
        if($ids!=null){
            $auctions=$auctions->whereNotIn(Constants::AUCTIONS_ID,$ids);
        }
        $auctions=$auctions->orderBy('end','asc')->paginate();
        $counter=0;$continue=1;
        $return=array(Constants::FINISHED=>array(), Constants::IN_CURSE=>array(), Constants::FUTURE=>array());
        while($continue==1){
            if(!isset($auctions[$counter])){
                return $return;
            }
            $availability=self::getAvailable($auctions[$counter]->id,$auctions[$counter]->amount)['available'];
            $timeline=($auctions[$counter]->end>$now && $availability>0)?Constants::IN_CURSE:(($auctions[$counter]->start<$now || $availability<=0)?Constants::FINISHED:Constants::FUTURE);
            if($auctions[$counter]->type==Constants::AUCTION_PUBLIC || ($auctions[$counter]->type==Constants::AUCTION_PRIVATE && self::checkifUserInvited($auctions[$counter]->id)==Constants::ACTIVE)){
                $return[$timeline][]=$auctions[$counter];
            }
            $counter++;
        }
    }


    public static function auctionPrivate($user_id , $status)
    {
        $now =date(Constants::DATE_FORMAT);
        $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)
            ->join(Constants::BATCHES,Constants::AUCTIONS_BATCH_ID,Constants::EQUAL,Constants::BATCH_ID)
            ->join(Constants::AUCTIONS_INVITES,Constants::AUCTIONS_ID,Constants::EQUAL,Constants::AUCTION_INV_AUCTION_ID);

        if ($status == Constants::FUTURE)
        {
            $rtrn = $rtrn->where(Constants::START,'>',$now);
        }else{
            $rtrn = $rtrn->where(Constants::START,'<',$now);
            $rtrn = $rtrn->where('end','>',$now);
        }

        return $rtrn->where(Constants::AUCTIONS_TYPE,Constants::EQUAL,Constants::AUCTION_PRIVATE)
            ->where('auctions_invites.user_id',Constants::EQUAL,$user_id)
            ->where(Constant::ACTIVE_LIT,Constants::EQUAL,Constants::ACTIVE)
            ->orderBy('end','DESC')
            ->paginate();
    }


    public function auctionInvited()
    {
        return $this->hasMany('App\AuctionInvited');
    }


    public function calculatePrice($bidDate)
    {
        $finalPrice = null;

        $timeStart = $this->start;
        $timeEnd = $this->end;
        $priceStart = $this->start_price;
        $priceEnd = $this->end_price;

        $cStart = Carbon::parse($timeStart)->timestamp;
        $cEnd = Carbon::parse($timeEnd)->timestamp;
        $cToday = Carbon::now()->timestamp;

        if ($cStart < $cToday && $cEnd > $cToday) {
            $interval = $this->interval;
            $intervalSegundo = $interval * 60;

            $diffminutesSE = ceil((strtotime($timeEnd) - strtotime($timeStart)) / $intervalSegundo);
            $numberIntervals = $diffminutesSE / $interval;

            $diffPriceSE = $priceStart - $priceEnd;

            $intvPrice = round(($diffPriceSE / $numberIntervals), 2);

            $diffBuyDatStarDat = ceil((strtotime($bidDate) - strtotime($timeStart)) / $intervalSegundo);

            $intervalBuy = ($diffBuyDatStarDat / $interval);

            $finalPrice = $priceStart - ($intervalBuy * $intvPrice);
            return number_format($finalPrice, env(Constants::AUCTION_PRICE_DECIMALS, 2));
        }else if($cEnd < $cToday) {
            return number_format($priceEnd, env(Constants::AUCTION_PRICE_DECIMALS, 2));
        }else{
            return number_format($priceStart, env(Constants::AUCTION_PRICE_DECIMALS, 2));
        }

    }

    public function isInCourse()
    {
        $now = date(Constants::DATE_FORMAT);
        return ( $this->start < $now && $this->end > $now );
    }

    public function isFinished()
    {
        $now = date(Constants::DATE_FORMAT);
        return ( $this->end < $now );
    }

    public function isNoStarted()
    {
        $now = date(Constants::DATE_FORMAT);
        return ( $this->start > $now );
    }

    public function cancelBid(Bid $bid)
    {
        $amount = $bid->amount;
        $bid->auction->batch->status->auction_sold -= $amount;

        if ($bid->auction->isInCourse()){
            $bid->auction->batch->status->assigned_auction += $amount;
        }
        if ($bid->auction->isFinished()){
            $bid->auction->batch->status->remainder += $amount;
        }

        $bid->auction->batch->status->save();
    }

    public function subscribeUser(User $user)
    {
        $this->subscription = new Subscription();
        $this->subscription->auction_id  = $this->id;
        $this->subscription->user_id  = $user->id;
        $this->subscription->status = Subscription::NO_NOTIFICADO;
        $this->subscription->save();
    }
    public function userInvited(){
        return $this->belongsToMany('App\User',Constants::AUCTIONS_INVITES)->orderBy("name");
    }
    static function getClosingAuction(){
        dd(Auction::find(66)->bids);
    }

    public function available($auction_id, $amountTotal){
        $vendido = 0;

        $bids = Bid::Select()
            ->where(Constants::STATUS,'<>',Constants::NO_CONCRETADA)
            ->where(Constants::BIDS_AUCTION_ID,$auction_id)
            ->get();

        foreach ($bids as $b) {
            $vendido+= $b->amount;
        }
        return $amountTotal-$vendido;
    }

    /* Funcion para validar el tamaño del producto*/
    public static function caliber($caliber){
        $calibre=($caliber=='small')?'chico':(($caliber=='medium')?'mediano':'grande');
        return $calibre;
    }

    //Funcion para darle formato a la fecha
    public function formatDate($fecha){
        setlocale(LC_TIME,'es_ES');
        return strftime('%d %b %Y', strtotime($fecha));

    }


    //Funcion para sacar la cantidad de ofertas directas

    public function amountSold($auction_id){
        $amount = Bid::Select()->where(Constants::STATUS,'<>',Bid::NO_CONCRETADA)
            ->where(Constants::BIDS_AUCTION_ID,Constants::EQUAL,$auction_id)
            ->get();
        $amount = $amount->toArray();
        echo count($amount);

    }


}
