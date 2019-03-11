<?php
use App\Constants;
use App\Product;
/*
 * In @section('content') goes all that's inside
 * .dashboard-content-container
 * (Lines 31->242 dash-nueva-subasta.php)
 * The content of the previously mentioned div in some point should transform to 
 * a form which action would be = /auctionStore
 * the rest of the content goes exactly as in the provided desing only changing
 * the selects of boats, ports and products
 * $code would be for the auction id field, it cant be shown because we need to 
 * avoid conflicts at saving in case of multiple sessions
 * In order to the form to work it needs the csrf_field
 * the names of the fields that are explitly declared should stay the same
 * Other name for inputs/selects
 *      Fecha Tentativa de entrega: date
 *      Calibre:    caliber
 *      Calidad:    quality
 *      Cantidad:   amount
 *      idbatch:    batch
 *      idarrive:   id
 *      datestart:  fechaInicio
 *      activehours:fechaFin
 *      startprice: startPrice
 *      endprice:   endPrice  
 *      description:descri
 *      privacy:    tipoSubasta
 *      guests:     invitados[]
 */
//die(json_encode($auction,true));
$code='SU-'.date('ym').'XXX';
$description='Curabitur turpis. Morbi nec metus. Etiam ut purus mattis mauris sodales aliquam. Ut tincidunt tincidunt erat. In hac habitasse platea dictumst.';
$portid=0;$boatid=0;$productid=0;$caliber='';$quality=0;$activehours=12;
$privacy=Constants::AUCTION_PUBLIC;
$startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
$tentativedate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+48 hours"))->format('d/m/Y H:i');
$calibers= Product::caliber();
$saleunits=Product::sale();
$presunits=Product::units();
$startprice=20;$endprice=1;$quantity=100;
$presunit='';$type='add';
if(isset($auction)){
    $type=(isset($replicate))?'replication':'edit';
    $boatid=$auction->batch->arrive->boat->id;
    $portid=$auction->batch->arrive->port_id;
    $productid=$auction->batch->product_id;
    $caliber=$auction->batch->caliber;
    $tentativedate=date('d/m/Y H:i', strtotime($auction->tentative_date));
    $description=$auction->description;
    $quality=$auction->batch->quality;
    $quantity=$auction->amount;
    $startdate=$auction->start;
    $activehours=(int)((strtotime($auction->end)-strtotime($startdate))/3600);
    $startdate=date('d/m/Y H:i', strtotime($auction->start));
    $startprice=$auction->start_price;
    $endprice=$auction->end_price;
    $code=$auction->code;
    $privacy=$auction->type;
    $presunit=$presunits[0];
    if($privacy=='private'){
        $guests= App\AuctionInvited::select('user_id')->where('auction_id',Constants::EQUAL,$auction->id)->get();
    }
}
if(Auth::user()->type==Constants::VENDEDOR){
?>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Error<?=(count($errors)>1)?'es':''?></strong><br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="post" action="/auctionstore">
    {{csrf_field()}}
    <input type="hidden" name="type" value="<?=$type?>">
    @if(isset($auction))
    <input type="hidden" name="auctionid" value="{{$auction->id}}">
    <input type="hidden" name="batchid" value="{{$auction->batch_id}}">
    <input type="hidden" name="arriveid" value="{{$auction->batch->arrive_id}}">
        @if($arriveedit==0)
    <input type="hidden" name="barco" value="<?=$boatid?>">
    <input type="hidden" name="puerto" value="<?=$portid?>">
        @endif
        @if($batchedit==0)
    <input type="hidden" name="product" value="<?=$productid?>">
    <input type="hidden" name="caliber" value="<?=$caliber?>">
    <input type="hidden" name="unidad" value="<?=$presunit?>">
    <input type="hidden" name="quality" value="<?=$quality?>">
        @endif
    @endif
<div>
    <h3>Arribo</h3>
    Bote:
    <select name="barco" id="Boat" onchange="getPreferredPort()" <?=($arriveedit==0)?Constants::DISABLED:''?>>
        <option>Seleccione...</option>
    @foreach($boats as $boat)
        <option value="{{$boat->id}}" <?=($boatid==$boat->id)?Constants::SELECTED:''?>>{{$boat->name}}</option>
    @endforeach    
    </select><br>
    Puerto:
     <select id="puerto" name="puerto" <?=($arriveedit==0)?Constants::DISABLED:''?>>
         <option>Seleccione...</option>
    @foreach($ports as $port)
        <option value ="{{$port->id}}" <?=($portid==$port->id)?Constants::SELECTED:''?>>{{$port->name}}</option>
    @endforeach
     </select><br>
     
</div>
<div>
    <h3>Lote</h3>
    Producto:
    <select name="product" <?=($batchedit==0)?Constants::DISABLED:''?>>
        <option>Seleccione...</option>
    @foreach($products as $product)
        <option value="{{$product->id}}" <?=($productid==$product->id)?Constants::SELECTED:''?>>{{$product->name}}</option>
    @endforeach
    </select><br>
    Calibre:
    <select name="caliber" <?=($batchedit==0)?Constants::DISABLED:''?> required>
        <option value="0">Seleccione...</option>
        @foreach($calibers as $c)
            <option value="{{$c}}"<?=($caliber==$c)?Constants::SELECTED:''?>>{{ trans('general.product_caliber.'.$c) }}</option>
        @endforeach
    </select><br>
    Unidad de presentacion:
    <select name="unidad" <?=($batchedit==0)?Constants::DISABLED:''?>>
        <option>Seleccione...</option>
        @foreach($presunits as $unit)
        <option value="{{$unit}}" <?=($unit==$presunit)?'selected':''?>>{{$unit}}</option>
        @endforeach
    </select><br>
    Calidad:
    <select name="quality" <?=($batchedit==0)?Constants::DISABLED:''?>>
        <option>Seleccione...</option>
        @for($z=1;$z<=5;$z++)
        <option value="<?=$z?>" <?=($quality==$z)?Constants::SELECTED:''?>><?=$z?></option>
        @endfor
    </select><br>
</div>
<div>
    <h3>Subasta</h3>
    Fecha de inicio:
    <input type="text" value="<?=$startdate?>" name="fechaInicio" placeholder="startdate"<?=($auctionedit==0)?Constants::DISABLED:''?>><br>
    Horas activa:<input type="number" value="<?=$activehours?>" min="1" name="ActiveHours" placeholder="activehours"<?=($auctionedit==0)?Constants::DISABLED:''?>><br>
    Fecha Tentativa:
    <input type="text" value="<?=$tentativedate?>" name="fechaTentativa" placeholder="Fecha Tentativa"<?=($auctionedit==0)?Constants::DISABLED:''?>>
    <br>
    Cantidad:<input type="number"value="<?=$quantity?>" min="1" name="amount" placeholder="cantidad"<?=($auctionedit==0)?Constants::DISABLED:''?>><br>
    Precio Inicio:<input type="number"value="<?=$startprice?>" name="startPrice" min="2" placeholder="start price"<?=($auctionedit==0)?Constants::DISABLED:''?>><br>
    Precio Fin: <input type="number"value="<?=$endprice?>" name="endPrice"min="1" placeholder="end price"<?=($auctionedit==0)?Constants::DISABLED:''?>><br>
    
    
</div>
            <textarea name="descri" placeholder="description"<?=($auctionedit==0)?Constants::DISABLED:''?> style="width: 100%"><?=$description?></textarea><br>
            <input type="submit"<?=(($auctionedit+$arriveedit+$batchedit)==0)?Constants::DISABLED:''?> value="Enviar">
</form>


<link href="/landing/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<script src="/landing3/js/jquery-3.3.1.min.js" type="text/javascript"></script>
<script>
    function getPreferredPort(){
        $.get('/getpreferredport',{idboat:$('#Boat').val()},function(result){
            $result=JSON.parse(result);
            console.log($result)
            $('#puerto').val($result['preferred']);
        });
    }
</script>
<?php }else{
    echo '<h1>Solo pueden crear subastas los usuarios de tipo vendedor</h1>';
}