<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Vendedor extends Model
{
    protected $table = 'vendedor';
    protected $fillable = ['user_id', 'cuit'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function mySales($status = null,$buyer = null,$batches = null ,$arrives = null,$boats = null)
    {
        $bids = Bid::select('bids.*')
            ->join('users as buyer','bids.user_id','=','buyer.id')
            ->join('auctions','bids.auction_id','=','auctions.id')
            ->join('batches','auctions.batch_id','=','batches.id')
            ->join('products','batches.product_id','=','products.id')
            ->join('arrives','batches.arrive_id','=','arrives.id')
            ->join('boats','arrives.boat_id','=','boats.id')
            ->where('boats.user_id',$this->user->id);

        if(!is_null($status) and count($status) > 0) $bids->whereIn('bids.status',$status);
        if(!is_null($buyer) and count($buyer) > 0) $bids->whereIn('bids.user_id',$buyer);
        if(!is_null($batches) and count($batches) > 0) $bids->whereIn('batches.id',$batches);
        if(!is_null($arrives) and count($arrives) > 0) $bids->whereIn('arrives.id',$arrives);
        if(!is_null($boats) and count($boats) > 0) $bids->whereIn('boats.id',$boats);

        return $bids;
    }

    public function getTotalSales($start = null, $end = null)
    {
        $sales = $this->mySales([Bid::CONCRETADA]);

        if (!is_null($start)){
            $sales->where('bid_date','>=', $start );
        }
        if (!is_null($end)){
            $sales->where('bid_date', '<=', $end );
        }

        $total = 0;
        foreach($sales->get() as $sale){
            $total += $sale->amount * $sale->price;
        }

        return $total;
    }
	
	
	public function getTotalPrivateSales($buyer=null , $start = null, $end = null)
    {
        $sales = $this->myPrivateSales();

        if (!is_null($start)){
            $sales->where('date','>=', $start );
        }
        if (!is_null($end)){
            $sales->where('date', '<=', $end );
        }
		
		

        $total = 0;
        foreach($sales->get() as $sale){
            $total += $sale->amount * $sale->price;
        }

        return $total;
    }
	

    public function myPrivateSales($buyer = null)
    {
        $sales = PrivateSale::where('user_id',$this->user->id);
		if(!is_null($buyer) and count($buyer) > 0) $sales->where('buyer_name', 'like', "%$buyer%");
        return $sales;

    }

    public function getMyMonthSales()
    {

        $dt = Carbon::now();
        $dt2 =Carbon::now();
        $dt3 =Carbon::now();
        $start_dt = $dt->endOfMonth();
        $end_dt = $dt2->addMonths(-12);


        $rtrn = [];
        for($i = 0;$i<=12;$i++){
            $rtrn[$dt3->month] = 0;
            $dt3->addMonth(-1);
        }

        $result = \DB::select("
            select sum(b.amount*b.price) ganancia,month(bid_date) mes from bids b
            inner join auctions a on b.auction_id = a.id
            inner join batches ba on a.batch_id = ba.id
            inner join arrives ar on ba.arrive_id = ar.id
            inner join boats bo on ar.boat_id = bo.id
            inner join users u on bo.user_id = u.id and u.id = ".Auth::user()->id."
            where bid_date BETWEEN '".$end_dt."' and '".$start_dt."'
            GROUP BY mes");

        foreach ($result as $item) {
            $rtrn[$item->mes] = $item->ganancia;

        }

        return array_reverse ($rtrn,true);
    }

    public function getMyMonthArrives()
    {

        $dt = Carbon::now();
        $dt2 =Carbon::now();
        $dt3 =Carbon::now();
        $start_dt = $dt->endOfMonth();
        $end_dt = $dt2->addMonths(-12);

        $rtrn = [];
        for($i = 0;$i<=12;$i++){
            $rtrn[$dt3->month] = 0;
            $dt3->addMonth(-1);
        }

        $result = \DB::select("
            SELECT
              count(ar.id) arrivos,
              month(ar.date)         mes
            FROM arrives ar
              INNER JOIN boats bo ON ar.boat_id = bo.id
              INNER JOIN users u ON bo.user_id = u.id AND u.id = ".Auth::user()->id."
            WHERE ar.date BETWEEN '".$end_dt."' AND '".$start_dt."'
            GROUP BY mes");

        foreach ($result as $item) {
            $rtrn[$item->mes] = $item->arrivos;

        }

        return array_reverse ($rtrn,true);
    }


}