<?php 

namespace App\Services;

use App\Auction;

class BidService {

    public function __construct()
    {
       
    }

    public function calculatePrice($auction_id,$bidDate)
	{
			$auction = Auction::findOrFail($auction_id);
			$finalPrice = null;
			
			$timeStart = $auction->start;
			$timeEnd = $auction->end;
			$priceStart = $auction->start_price;
			$priceEnd = $auction->end_price;
			$interval = $auction->interval;
			
			
			$diffminutesSE = ceil((strtotime($timeEnd) - strtotime($timeStart) )/ 60);
			$numberIntervals = $diffminutesSE  / $interval;
			
			$diffPriceSE = $priceStart -  $priceEnd; 
			
			$intvPrice = round(($diffPriceSE / $numberIntervals),2);
			
			$diffBuyDatStarDat = ceil((strtotime($bidDate) - strtotime($timeStart) )/ 60);
			
			$intervalBuy = ($diffBuyDatStarDat / $interval);
			
			$finalPrice = $priceStart - ($intervalBuy * $intvPrice);
			
			return $finalPrice;
		
    }

}
