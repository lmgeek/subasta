<?php
namespace App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Paginator;
class Constants{
    /*
     * Constants Declaration
     */
    const CANT_MAX_BRONZE=500;
    const CANT_MAX_SILVER=1000;
    const PAGINATE_NUMBER=3;
    const PAGINATE_MAX_LINKS=6;
    const NAME='name';
    const MAIL_ADDRESS='sistema@subastas.com.ar';
    const MAIL_NAME='Subastas';
    const MIDDLEWARE='middleware';
    const EMAIL='email';
    const ARRIVE_ID='arrive_id';
    const ADDBOAT='addBoat';
    const PASSWORD='password';
    const NOMBRE='nombre';
    const CODIGO='codigo';
    const UNIDAD='unidad';
    const SALE='sale';
    const REMEMBER='remember';
    const IMAGE='image';
    const IMAGEN='imagen';
    const VERIFY='verify';
    const IN_COURSE = 'incourse';
    const FINISHED = 'finished';
    const FUTURE = 'future';
    const EQUAL='=';
    const ASC='asc';
    const DESC='desc';
    const STATUS='status';
    const NICKNAME='nickname';
    const MY_IN_CURSE = 'my_incurse';
    const MY_FINISHED = 'my_finished';
    const MY_FUTURE = 'my_future';
    const ACTIVE = '1';
    const INACTIVE = '0';
    const ACTIVE_LIT='active';
    const START='start';
    const MINUTES='minutes';
    const AMOUNT='amount';
    const AVAILABLE='available';
    const AVAILABILITY='availability';
    const IS_NOT_AVAILABLE='isnotavailability';
    const QUALITY='quality';
    const TRANS_UNITS='general.product_units.';
    const CLOSE='close';
    const PRICE='price';
    const ERROR='error';
    const DATE_FORMAT= 'Y-m-d H:i:s';
    const DATE_FORMAT_INPUT= 'd/m/Y H:i';
    const INPUT_END_PRICE='endPrice';
    const INPUT_AUCTION_ID='auction_id';
    const INPUT_CALIFICACION='calificacion';
    const INPUT_COMENTARIOS_CALIFICACION='comentariosCalificacion';
    const MAKE_BID='makeBid';
    const AUCTION='auction';
    const BATCHES_PRODUCT_ID='batches.product_detail_id';
    const AUCTIONS='auctions';
    const AUCTIONS_SELECT_ALL= 'auctions.*';
    const AUCTIONS_ID='auctions.id';
    const AUCTIONS_TYPE='auctions.type';
    const AUCTION_PRIVATE = 'private';
    const AUCTION_PUBLIC = 'public';
    const AUCTIONS_BATCH_ID='auctions.batch_id';
    const AUCTIONS_START='auctions.start';
    const AUCTIONS_END='auctions.end';
    const AUCTIONS_ACTIVE='auctions.active';
    const AUCTIONS_INVITES='auctions_invites';
    const AUCTION_INV_AUCTION_ID='auctions_invites.auction_id';
    const AUCTION_PRICE_DECIMALS='AUCTION_PRICE_DECIMALS';
    const BATCH='batch';
    const BATCHES='batches';
    const PRODUCT_DETAIL='product_detail';
    const PRODUCT_DETAIL_ID='product_detail.id';
    const BATCH_ID='batches.id';
    const BATCH_ARRIVE_ID='batches.arrive_id';
    const ARRIVES='arrives';
    const ARRIVES_ID='arrives.id';
    const ARRIVES_BOAT_ID='arrives.boat_id';
    const BOATS='boats';
    const AUCTIONEDIT='auctionedit';
    const ARRIVEEDIT='arriveedit';
    const BATCHEDIT='batchedit';
    const PRODUCTID='productid';
    const TIPOSUBASTA='tipoSubasta';
    const DESCRI='descri';
    const PRICEMAX='pricemax';
    const PRICEMIN='pricemin';
    const EN_US='en_US';
    const VIEWOPERATIONS='viewOperations';
    const BATCH_I='batch_id';
    const CURRENTPRICE='CurrentPrice';
    const SELLERID='sellerid';
    const BATCHID='batchid';
    const BOATID='boatid';
    const PORTID='portid';
    const IDTOAVOID='idtoavoid';
    const AUCTIONID='auctionid';
    const REQUEST='request';
    const URL_AUCTION_TEMP='/landing3/auction-add-edit-temp';
    const RATINGS='ratings';
    const PORT_ID='port_id';
    const REPLICATE='replicate';
    const UNIT='unit';
    const OFFERSCOUNTER='offerscounter';
    const BIDSCOUNTER='bidscounter';
    const LANDING3_OFFERS='landing3/offers';
    const EMAILS_OFFERAUCTION='emails.offerauction';
    const BOATS_ID='boats.id';
    const BOATS_USER_ID='boats.user_id';
    const USER='user';
    const USERS='users';
    const USERS_ID='users.id';
    const BIDS_DATE='bid_date';
    const BIDS_AUCTION_ID='bids.auction_id';
    const BIDS_USER_ID='bids.user_id';
    const CALIBER='caliber';
    const CALIBERS='calibers';
    const PORT='port';
    const PORTS='ports';
    const PRODUCT='product';
    const PRODUCTS='products';
    const SELLER='seller';
    const SELLERS='sellers';
    const USER_RATING='userRating';
    const NOTIFICATION_STATUS_IN_CURSE = '0';
    const NOTIFICATION_STATUS_SENDING = '1';
    const NOTIFICATION_STATUS_SENDED = '2';
    const NO_CONCRETADA = 'noConcretized';
    const CONCRETADA = 'concretized';
    const PENDIENTE = 'pending';
    const CALIFICACION_POSITIVA = 'positive';
    const CALIFICACION_NEUTRAL = 'neutral';
    const CALIFICACION_NEGATIVA = 'negative';
    const MAIL_ADDRESS_SYSTEM='MAIL_ADDRESS_SYSTEM';
    const MAIL_ADDRESS_SYSTEM_NAME='MAIL_ADDRESS_SYSTEM_NAME';
    const MAIL_SUBJECT_WELCOME='users.email_welcome_title';
    const MAIL_TEMPLATE_START='emails.';
    const CSS_SOLID='solid';
    const URL_PRODUCTS='/products';
    const URL_LOGIN='auth/login';
    const URL_SELLER_BATCH='/sellerbatch';
    const REGISTER_MESSAGE='register_message';
    const BUYER_NAME='buyer_name';
    const APROBADO = "approved";
    const RECHAZADO = "rejected";
    const USER_ID='user_id';
    const CAJAS     = "Cajas";
    const CAJONES   = "Cajones";
    const PASTILLAS = "Pastillas";
    const UNIDADES  = "Unidades";
    const KG = "Kg";
    const EMAIL_OFFERAUCTION = 'emails.offerauction';
    const USERS_OFFERAUCTION = 'users.offer_auction';
    const DISABLED='disabled';
    const SELECTED='selected';
    const CHICO     = "small";
    const MEDIANO   = "medium";
    const GRANDE    = "big";
    const WEIGHT_SMALL='weight_small';
    const WEIGHT_MEDIUM='weight_medium';
    const WEIGHT_BIG='weight_big';
    const VALIDATION_RULES_PRODUCT_WEIGHT='required|regex:/^\d{1,}(\,\d+)?$/|greater_weight_than:';
    const REQUIRED='required';
    const ICON_OFFERS_BIDS_GREEN='<em class="icon-material-outline-local-offer green"></em>';
    const OFERTA_DIRECTA=' Oferta Directa';
    const OFERTAS_DIRECTAS=' Ofertas Directas';
    const INTERNAL = "internal";
    const VENDEDOR = "seller";
    const COMPRADOR = "buyer";
    const BROKER = "broker";
    const AUCTION_ORIGIN = "auction";
    const OFFER_ORIGIN = "offer";
    
    /*
     * Commonly used functions
     */
    public static function formatDate($fecha,$format='d M Y'){
        $fecha= strtolower(date($format, strtotime($fecha)));
        $months=['jan','apr','aug','dec'];
        $meses =['ene','abr','ago','dic'];
        $mes=substr($fecha,3,3);
        for($z=0;$z<count($months);$z++){
            if($mes==$months[$z]){
                $fecha= str_replace($months[$z], $meses[$z], $fecha);
            }
        }
        return ucwords($fecha);
    }

    public static function formatDateOffer($fecha){
        return self::formatDate($fecha,'d M Y H:m');
    }

    /* Funcion para validar el tamaño del producto*/
    public static function caliber($caliber){
        $mediumbigdifferencer=(($caliber=='medium')?'mediano':'grande');
        return ($caliber=='small')?'chico':$mediumbigdifferencer;
    }
    public static function array_merge_join($arrayofarrays){
	    $return=array();
	    foreach($arrayofarrays as $array){
	        foreach($array as $key=>$valor){
	            if(isset($return[$key])){
                    $return[$key]+=$valor;
                }else{
                    $return[$key]=1;
                }

            }
        }
	    return $return;
    }
    public static function checkSearchQuery($valuetocheck,$query){
        $querymin= strtolower($query);
        $valuemin= strtolower($valuetocheck);
        return (substr_count($querymin, $valuemin)>0)?'checked':'';
    }
    public static function manualPaginate($array,$currentpage=1){
        $itemCollection = collect($array);
        $perPage = self::PAGINATE_NUMBER;
        $currentPageItems = $itemCollection->slice(($currentpage * $perPage) - $perPage, $perPage)->all();
        return new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
    }
     public static function getRealQuery($query, $dumpIt = false){
        $params = array_map(function ($item) {
            return "'{$item}'";
        }, $query->getBindings());
        $result = str_replace_array('\?', $params, $query->toSql());
        if ($dumpIt) {
            dd($result);
        }
        return $result;
    }
    public static function getTimeline($start,$end,$availability){
        $now =date(self::DATE_FORMAT);
        if($end<$now || $availability<=0){
            $timeline= Constants::FINISHED;
        }elseif($start<$now && $availability>0){
            $timeline=Constants::IN_COURSE;
        }elseif($start>$now){
            $timeline= Constants::FUTURE;
        }
        return $timeline;
    }
    public static function colorByStatus($status){
        if($status==self::PENDIENTE){
            return 'yellow';
        }elseif($status==self::APROBADO || $status==self::CONCRETADA){
            return 'green';
        }else{
            return 'red';
        }
    }
    public static function individualize($word){
        $last=substr($word,-1);
        $end=substr($word,-2);
        $return='';
        if($end=='es'){
            $return=substr($word,0,-2);
        }elseif($last=='s'){
            $return=substr($word,0,-1);
        }else{
            $return=$word;
        }
        $newend=substr($return,-2);
        if($newend=='on'){
            $return=substr($return,0,-2).'&oacute;n';
        }
        return $return;
    }
    public static function individualizeSentence($sentence,$cant=null){
        if($cant!=1 && $cant!=null){
            return $cant.' '.$sentence;
        }
        $array= explode(' ', $sentence);$return=$cant.' ';
        for($z=0;$z<count($array);$z++){
            $return.=self::individualize($array[$z]).' ';
        }
        return $return;
    }
    
    /*
     * Commonly Used Arrays
     */
    public $paises=[
        ['name'=>'Andorra','code2'=>'AD','code3'=>'AND'],
        ['name'=>'Afghanistan','code2'=>'AF','code3'=>'AFG'],
        ['name'=>'Islas','code2'=>'','code3'=>''],
        ['name'=>'Argentina','code2'=>'AR','code3'=>'ARG'],
        ['name'=>'','code2'=>'','code3'=>''],
    ];
}
