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
    
    const NOTIFICATION_STATUS_IN_CURSE = '0';
    const NOTIFICATION_STATUS_SENDING = '1';
    const NOTIFICATION_STATUS_SENDED = '2';
    public function makeBid($amount , $price){
        $prices = str_replace(",","",$price);
        $this->bid = new Bid();
        $this->bid->user_id = Auth::user()->id;
        $this->bid->auction_id = $this->id;
        $this->bid->amount = $amount;
        $this->bid->price = $prices;
        $this->bid->status = Bid::PENDIENTE;
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
        $this->offers->status = Offers::PENDIENTE;
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
        $data = \DB::table('batch_statuses')->where('batch_id', '=', $this->batch->id)->lockForUpdate()->get();
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
        return $this->getAuctionDuration('minutes');
    }

    public function getAuctionLeftTimeInMinutes()
    {
        return $this->getAuctionLeftTime('minutes');
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
        return ($type=='minutes')?(Carbon::now()->diffInMinutes(Carbon::createFromFormat(Constants::DATE_FORMAT,$this->end))):(Carbon::now()->diffInSeconds(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->end)));
    }

    private function getAuctionDuration($type)
    {
        return ($type=='minutes')?(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->start)->diffInMinutes(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->end))):(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->start)->diffInSeconds(Carbon::createFromFormat( Constants::DATE_FORMAT,$this->end)));
    }

    public static function filterAndPaginate($status , $product = null , $seller = null , $boat = null , $type = Constants::AUCTION_PUBLIC , $withStock = false){
        $now =date(Constants::DATE_FORMAT);
        switch ($status){
            case Constants::FINISHED:
                $rtrn = Auction::where('end','<=',$now)->where('auctions.type','=',$type)->orderBy('start','DESC');
                break;
            case Constants::FUTURE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID);
                if ( null != $product  ) {
                    $rtrn = $rtrn->whereIn('batches.product_id',$product);
                }
                if ( null != $seller || null != $boat ) {
                    $rtrn = $rtrn->join('arrives','batches.arrive_id','=','arrives.id')->join('boats','arrives.boat_id','=','boats.id')->join('users','boats.user_id','=','users.id');
                    if (null != $seller) {
                        $rtrn = $rtrn->whereIn('users.id',$seller);
                    }
                    if (null != $boat) {
                        $rtrn = $rtrn->whereIn('boats.id',$boat);
                    }

                }
                $rtrn = $rtrn->where('start','>',$now)->where('auctions.type','=',$type)->orderBy('start','DESC');
                break;
            case Constants::MY_IN_CURSE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID)->join('arrives','batches.arrive_id','=','arrives.id')->join('boats','arrives.boat_id','=','boats.id')->join('users','boats.user_id','=','users.id')->where('users.id',Auth::user()->id)->where('auctions.start','<',$now)->where('auctions.end','>',$now)->orderBy('auctions.end','desc');
                break;
            case Constants::MY_FUTURE:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID)->join('arrives','batches.arrive_id','=','arrives.id')->join('boats','arrives.boat_id','=','boats.id')->join('users','boats.user_id','=','users.id')->where('users.id',Auth::user()->id)->where('auctions.start','>',$now)->orderBy('auctions.created_at','desc');
                break;
            case Constants::MY_FINISHED:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID)->join('arrives','batches.arrive_id','=','arrives.id')->join('boats','arrives.boat_id','=','boats.id')->join('users','boats.user_id','=','users.id')->where('users.id',Auth::user()->id)->where('auctions.end','<=',$now)->orderBy('auctions.created_at','desc');
                break;
            default:
                $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID);
                if ( null != $product  ) {
                    $rtrn = $rtrn->whereIn('batches.product_id',$product);
                }
                if ( null != $seller || null != $boat ) {
                    $rtrn = $rtrn->join('arrives','batches.arrive_id','=','arrives.id')->join('boats','arrives.boat_id','=','boats.id')->join('users','boats.user_id','=','users.id');
                    if (null != $seller) {
                        $rtrn = $rtrn->whereIn('users.id',$seller);
                    }
                    if (null != $boat) {
                        $rtrn = $rtrn->whereIn('boats.id',$boat);
                    }
                }
                $rtrn = $rtrn->where('start','<',$now)->where('end','>',$now)->where('auctions.type','=',$type)->where('active','=',Constants::ACTIVE)->orderBy('end','asc');
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
        $cant=count(Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID)->join('auctions_invites',Constants::AUCTIONS_ID,'=','auctions_invites.auction_id')->where('auctions_invites.user_id','=',$iduser));
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
            ->where('status','<>',\App\Bid::NO_CONCRETADA)
            ->where('auction_id',$auction_id)
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
            ->where('auction_id','=',$id)
            ->where('user_id','=',Auth::user()->id)->get();
        return (count($auction)>0)?1:0;
    }
    public static function auctionHome($ids=null){
        $now =date(Constants::DATE_FORMAT);
        $auctions = Auction::select( Constants::AUCTIONS_SELECT_ALL)->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID)->join('arrives','batches.arrive_id','=','arrives.id')->join('port','arrives.port_id','=','port.id')->where('active','=',Constants::ACTIVE);
        if($ids!=null){
            $auctions=$auctions->whereNotIn(Constants::AUCTIONS_ID,$ids);
        }
        $auctions=$auctions->orderBy('end','asc')->paginate();
        $counter=0;$continue=1;
        $return=array(Constants::FINISHED=>array(), Constants::IN_CURSE=>array(), Constants::FUTURE=>array());
        while($continue==1){
            if($auctions[$counter]->type==Constants::AUCTION_PUBLIC || ($auctions[$counter]->type==Constants::AUCTION_PRIVATE && self::checkifUserInvited($auctions[$counter]->id)==1)){
                if(self::getAvailable($auctions[$counter]->id,$auctions[$counter]->amount)['available']>0){
                    $return[($auctions[$counter]->end>$now)?Constants::IN_CURSE:(($auctions[$counter]->start<$now)?Constants::FINISHED:Constants::FUTURE)][]=$auctions[$counter];
                }else{
                    $return[Constants::FINISHED][]=$auctions[$counter];
                }
            }
            $counter++;
            if(!isset($auctions[$counter])){
                $continue++;
            }
        }
        return $return;
    }


    public static function auctionPrivate($user_id , $status)
    {
        $now =date(Constants::DATE_FORMAT);
        $rtrn = Auction::select( Constants::AUCTIONS_SELECT_ALL)
            ->join('batches',Constants::AUCTIONS_BATCH_ID,'=',Constants::BATCH_ID)
            ->join('auctions_invites',Constants::AUCTIONS_ID,'=','auctions_invites.auction_id');

        if ($status == Constants::FUTURE)
        {
            $rtrn = $rtrn->where('start','>',$now);
        }else{
            $rtrn = $rtrn->where('start','<',$now);
            $rtrn = $rtrn->where('end','>',$now);
        }

        return $rtrn->where('auctions.type','=',Constants::AUCTION_PRIVATE)
            ->where('auctions_invites.user_id','=',$user_id)
            ->where('active','=',Constants::ACTIVE)
            ->orderBy('end','DESC')
            ->paginate();
    }


    public function auctionInvited()
    {
        return $this->hasMany('App\AuctionInvited');
    }


    public function calculatePrice($bidDate)
    {
        //$auction = Auction::findOrFail($auction_id);
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

            //$arraydump = array("start_seg" => strtotime($timeStart), "start" => $timeStart, "end_seg" => strtotime($timeEnd), "end" => $timeEnd, "priceStart" => $priceStart, "priceEnd" => $priceEnd, "interval" => $interval, "numberIntervals" => $numberIntervals, "cStart" => $cStart, "cEnd" => $cEnd, "cToday" => $cToday, "intervalSegundo" => $intervalSegundo, "diffminutesSE" => $diffminutesSE, "restante" => $hoy, "diffPriceSE" => $diffPriceSE, "diffBuyDatStartDat" => $diffBuyDatStarDat, "intervalBuy" => $intervalBuy, "bidDate_seg" => strtotime($bidDate), "bidDate" => $bidDate, "intevPrice" => $intvPrice, "finalPrice" => $finalPrice);dd($arraydump);
            return number_format($finalPrice, env('AUCTION_PRICE_DECIMALS', 2));
        }else if($cEnd < $cToday) {
            return number_format($priceEnd, env('AUCTION_PRICE_DECIMALS', 2));
        }else{
            return number_format($priceStart, env('AUCTION_PRICE_DECIMALS', 2));
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
        return $this->belongsToMany('App\User','auctions_invites')->orderBy("name");
    }
    static function getClosingAuction(){
        dd(Auction::find(66)->bids);
    }

    public function available($auction_id, $amountTotal){
        $vendido = 0;

        $bids = Bid::Select()
            ->where('status','<>',\App\Bid::NO_CONCRETADA)
            ->where('bids.auction_id',$auction_id)
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
        $amount = Bid::Select()->where('status','<>',Bid::NO_CONCRETADA)
            ->where('auction_id','=',$auction_id)
            ->get();
        $amount = $amount->toArray();
        echo count($amount);

    }


}
