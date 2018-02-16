<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Bid;


class Auction extends Model
{
    protected $table = 'auctions';
    const IN_CURSE = 'incourse';
    const FINISHED = 'finished';
    const FUTURE = 'future';
    const MY_IN_CURSE = 'my_incurse';
    const MY_FINISHED = 'my_finished';
    const MY_FUTURE = 'my_future';
	const ACTIVE = '1';
	const INACTIVE = '0';
	const AUCTION_PRIVATE = 'private';
	const AUCTION_PUBLIC = 'public';

    protected $fillable = ['batch_id', 'start','start_price','end','end_price','interval','type','notification_status','notification_status'];

    const NOTIFICATION_STATUS_IN_CURSE = '0';
    const NOTIFICATION_STATUS_SENDING = '1';
    const NOTIFICATION_STATUS_SENDED = '2';

	public function makeBid($amount , $price)
	{
		$this->bid = new Bid();
		$this->bid->user_id = Auth::user()->id;
		$this->bid->auction_id = $this->id;
		$this->bid->amount = $amount;
		$this->bid->price = $price;
		$this->bid->status = Bid::PENDIENTE;
		$this->bid->bid_date = date('Y-m-d H:i:s');
		$this->bid->save();
		
		$this->status = $this->batch->status;
		$this->status->assigned_auction -= $amount;
		$this->status->auction_sold += $amount;
        $this->status->save();	
		
		
	}
	
	public function getAvailability()
	{
		return $this->batch->status->assigned_auction;
	}
	
	public function getAvailabilityLock()
	{
		$data = \DB::table('batch_statuses')->where('batch_id', '=', $this->batch->id)->lockForUpdate()->get();	
		return $data[0]->assigned_auction;
	}

    public function subscription()
    {
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
        $minutosRestantes = ceil($segundosRestantes/60);
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

    private function getAuctionLeftTime($type)
    {
        switch ($type){
            case 'minutes':
                $rtrn = Carbon::now()->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s',$this->end));
                break;
            case 'seconds':
                $rtrn = Carbon::now()->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s',$this->end));
                break;
        }

        return $rtrn;
    }

    private function getAuctionDuration($type)
    {
        switch ($type){
            case 'minutes':
                $rtrn = Carbon::createFromFormat('Y-m-d H:i:s',$this->start)->diffInMinutes(Carbon::createFromFormat('Y-m-d H:i:s',$this->end));
                break;
            case 'seconds':
                $rtrn = Carbon::createFromFormat('Y-m-d H:i:s',$this->start)->diffInSeconds(Carbon::createFromFormat('Y-m-d H:i:s',$this->end));
                break;
        }

        return $rtrn;
    }

    public static function filterAndPaginate($status , $product = null , $seller = null , $boat = null , $type = self::AUCTION_PUBLIC )
    {
        $now =date("Y-m-d H:i:s");
        switch ($status){
            case self::FINISHED:
                $rtrn = Auction::where('end','<=',$now)
					->where('auctions.type','=',$type)
                    ->orderBy('start','DESC')
                    ->paginate();
                break;
            case self::FUTURE:
                $rtrn = Auction::select('auctions.*')
						   ->join('batches','auctions.batch_id','=','batches.id');
					if ( null != $product  )
					{
						$rtrn = $rtrn->whereIn('batches.product_id',$product);
					}
					if ( null != $seller || null != $boat )
					{
						$rtrn = $rtrn->join('arrives','batches.arrive_id','=','arrives.id')
									 ->join('boats','arrives.boat_id','=','boats.id')
									 ->join('users','boats.user_id','=','users.id');
									 
							if (null != $seller)
							{
								$rtrn = $rtrn->whereIn('users.id',$seller);
							}
							
							if (null != $boat)
							{
								$rtrn = $rtrn->whereIn('boats.id',$boat);
							}
							
					}
				$rtrn = $rtrn->where('start','>',$now)
						->where('auctions.type','=',$type)
						->orderBy('start','DESC')
                    ->paginate();
                break;
            case self::MY_IN_CURSE:
                $rtrn = Auction::select('auctions.*')
                    ->join('batches','auctions.batch_id','=','batches.id')
                    ->join('arrives','batches.arrive_id','=','arrives.id')
                    ->join('boats','arrives.boat_id','=','boats.id')
                    ->join('users','boats.user_id','=','users.id')
                    ->where('users.id',Auth::user()->id)
                    ->where('auctions.start','<',$now)
                    ->where('auctions.end','>',$now)
                    ->orderBy('auctions.end','desc')
                    ->paginate();
                break;
            case self::MY_FUTURE:
                $rtrn = Auction::select('auctions.*')
                    ->join('batches','auctions.batch_id','=','batches.id')
                    ->join('arrives','batches.arrive_id','=','arrives.id')
                    ->join('boats','arrives.boat_id','=','boats.id')
                    ->join('users','boats.user_id','=','users.id')
                    ->where('users.id',Auth::user()->id)
                    ->where('auctions.start','>',$now)
                    ->orderBy('auctions.created_at','desc')
                    ->paginate();
                break;
            case self::MY_FINISHED:
                $rtrn = Auction::select('auctions.*')
                    ->join('batches','auctions.batch_id','=','batches.id')
                    ->join('arrives','batches.arrive_id','=','arrives.id')
                    ->join('boats','arrives.boat_id','=','boats.id')
                    ->join('users','boats.user_id','=','users.id')
                    ->where('users.id',Auth::user()->id)
                    ->where('auctions.end','<=',$now)
                    ->orderBy('auctions.created_at','desc')
                    ->paginate();
                break;
            default:
			
					$rtrn = Auction::select('auctions.*')
						   ->join('batches','auctions.batch_id','=','batches.id');
					if ( null != $product  )
					{
						$rtrn = $rtrn->whereIn('batches.product_id',$product);
					}
					if ( null != $seller || null != $boat )
					{
						$rtrn = $rtrn->join('arrives','batches.arrive_id','=','arrives.id')
									 ->join('boats','arrives.boat_id','=','boats.id')
									 ->join('users','boats.user_id','=','users.id');
									 
							if (null != $seller)
							{
								$rtrn = $rtrn->whereIn('users.id',$seller);
							}
							
							if (null != $boat)
							{
								$rtrn = $rtrn->whereIn('boats.id',$boat);
							}
							
					}
					
					$rtrn = $rtrn->where('start','<',$now)
                    ->where('end','>',$now)
					->where('auctions.type','=',$type)
					->where('active','=',self::ACTIVE)
                    ->orderBy('end','DESC')
                    ->paginate();
					
                break;
        }

        return $rtrn;
    }
	
	public static function auctionPrivate($user_id , $status)
    {
        $now =date("Y-m-d H:i:s");
    	$rtrn = Auction::select('auctions.*')
						   ->join('batches','auctions.batch_id','=','batches.id')
						   ->join('auctions_invites','auctions.id','=','auctions_invites.auction_id');
					
					if ($status == self::FUTURE)
					{
						$rtrn = $rtrn->where('start','>',$now);
					}else{
						$rtrn = $rtrn->where('start','<',$now);
						$rtrn = $rtrn->where('end','>',$now);
					}
					
                   
					$rtrn = $rtrn->where('auctions.type','=',self::AUCTION_PRIVATE)
					->where('auctions_invites.user_id','=',$user_id)
					->where('active','=',self::ACTIVE)
                    ->orderBy('end','DESC')
                    ->paginate();

        return $rtrn;
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

        if ($cStart < $cToday and $cEnd > $cToday) {
            $interval = $this->interval;

            $diffminutesSE = ceil((strtotime($timeEnd) - strtotime($timeStart)) / 60);
            $numberIntervals = $diffminutesSE / $interval;

            $diffPriceSE = $priceStart - $priceEnd;

            $intvPrice = round(($diffPriceSE / $numberIntervals), 2);

            $diffBuyDatStarDat = ceil((strtotime($bidDate) - strtotime($timeStart)) / 60);

            $intervalBuy = ($diffBuyDatStarDat / $interval);

            $finalPrice = $priceStart - ($intervalBuy * $intvPrice);

            return number_format($finalPrice, env('AUCTION_PRICE_DECIMALS', 2));
        }else if($cEnd < $cToday) {
            return number_format($priceEnd, env('AUCTION_PRICE_DECIMALS', 2));
        }else{
            return number_format($priceStart, env('AUCTION_PRICE_DECIMALS', 2));
        }
		
    }

    public function isInCourse()
    {
        $now = date("Y-m-d H:i:s");
        return ( $this->start < $now and $this->end > $now );
    }

    public function isFinished()
    {
        $now = date("Y-m-d H:i:s");
        return ( $this->end < $now );
    }

    public function isNoStarted()
    {
        $now = date("Y-m-d H:i:s");
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
	
}
