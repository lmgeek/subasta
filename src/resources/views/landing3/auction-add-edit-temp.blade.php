<?php
use App\Constants;
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
$portid=0;$boatid=0;$productid=0;$caliber='';$quality=0;$activehours=1;$privacy=Constants::AUCTION_PUBLIC;
$startdate=date_add(date_create(date('Y-m-d H:i:s')),date_interval_create_from_date_string("+2 hours"))->format('d/m/Y H:i');
$tentativedate=$startdate;

$startprice=2;$endprice=1;$quantity=1;
if(isset($auction)){
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
    if($privacy=='private'){
        $guests= App\AuctionInvited::select('user_id')->where('auction_id',Constants::EQUAL,$auction->id)->get();
    }
}
if(Auth::user()->type==Constants::VENDEDOR){
?>
@if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Error</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
<form method="post" action="/auctionstore">
    {{csrf_field()}}
    @if(isset($auction))
        @if(isset($replicate))
    <input type="hidden" name="type" value="replication">
        @endif
    <input type="hidden" name="auctionid" value="{{$auction->id}}">
    <input type="hidden" name="batchid" value="{{$auction->batch_id}}">
    <input type="hidden" name="arriveid" value="{{$auction->batch->arrive_id}}">
    @endif
<div>
    <h3>Arribo</h3>
    Bote:
    <select name="barco" id="Boat" onchange="getPreferredPort()" <?=($arriveedit==0)?'disabled':''?>>
    @foreach($boats as $boat)
        <option value="{{$boat->id}}" <?=($boatid==$boat->id)?'selected':''?>>{{$boat->name}}</option>
    @endforeach    
    </select><br>
    Puerto:
     <select id="puerto" name="puerto" <?=($arriveedit==0)?'disabled':''?>>
    @foreach($ports as $port)
        <option value ="{{$port->id}}" <?=($portid==$port->id)?'selected':''?>>{{$port->name}}</option>
    @endforeach
     </select><br>
     Fecha Tentativa:
    @if($arriveedit==0)
    <input type="text" value="<?=$tentativedate?>" placeholder="Fecha Tentativa"disabled>
    <input type="hidden" name="fechaTentativa" value="<?=$tentativedate?>">
    @else
    <input type="text" value="<?=$tentativedate?>" placeholder="Fecha Tentativa" name="fechaTentativa" id="fechaTentativa">
    @endif
    <br>
</div>
<div>
    <h3>Lote</h3>
    Producto:
    <select name="product" <?=($batchedit==0)?'disabled':''?>>
    @foreach($products as $product)
        <option value="{{$product->id}}" <?=($productid==$product->id)?'selected':''?>>{{$product->name}}</option>
    @endforeach
    </select><br>
    Calibre:
    <select name="caliber" <?=($batchedit==0)?'disabled':''?>>
        <option value="small"<?=($caliber=='small')?'selected':''?>>Pequeño</option>
        <option value="medium"<?=($caliber=='medium')?'selected':''?>>Mediano</option>
        <option value="big"<?=($caliber=='big')?'selected':''?>>Grande</option>
    </select><br>
    Unidad:
    <select name="unidad" <?=($batchedit==0)?'disabled':''?>>
        <option value="Cajones"selected>Caj&oacute;n</option>
        <option value="Kg">Kilogramo</option>
        <option value="Unidad">Unidad</option>
    </select><br>
    Calidad:
    <select name="quality" <?=($batchedit==0)?'disabled':''?>>
        @for($z=1;$z<=5;$z++)
        <option value="<?=$z?>" <?=($quality==$z)?'selected':''?>><?=$z?></option>
        @endfor
    </select><br>
</div>
<div>
    <h3>Subasta</h3>
    Fecha de inicio:
    <input type="text" value="<?=$startdate?>" name="fechaInicio" placeholder="startdate"<?=($auctionedit==0)?'disabled':''?>><br>
    Horas activa:<input type="number" value="<?=$activehours?>" min="1" name="ActiveHours" placeholder="activehours"<?=($auctionedit==0)?'disabled':''?>><br>
    Cantidad:<input type="number"value="<?=$quantity?>" min="1" name="amount" placeholder="cantidad"<?=($auctionedit==0)?'disabled':''?>><br>
    Precio Inicio:<input type="number"value="<?=$startprice?>" name="startPrice" min="2" placeholder="start price"<?=($auctionedit==0)?'disabled':''?>><br>
    Precio Fin: <input type="number"value="<?=$endprice?>" name="endPrice"min="1" placeholder="end price"<?=($auctionedit==0)?'disabled':''?>><br>
    Privacidad: 
    <select name="tipoSubasta" onchange="changePrivacy()"<?=($auctionedit==0)?'disabled':''?> id="tipoSubasta">
        <option value="public"<?=($privacy== Constants::AUCTION_PUBLIC || old('tipoSubasta')==Constants::AUCTION_PUBLIC)?'selected':''?>>P&uacute;blica</option>
        <option value="private"<?=($privacy== Constants::AUCTION_PRIVATE || old('tipoSubasta')==Constants::AUCTION_PRIVATE)?'selected':''?>>Privada</option>
    </select><br><br>
    <input type="text" name="guests" placeholder="Escribe un nombre y selecciona" style="<?=($privacy== Constants::AUCTION_PUBLIC || old('tipoSubasta')==Constants::AUCTION_PUBLIC)?'display:none;':''?>width:100%" id="guests" onkeypress="getUsers()" onclick="getUsers()"<?=($auctionedit==0)?'disabled':''?>>
    <div id="UsersShowTemp"></div>
    <div id="UsersShow">
        @if(isset($guests))
            @foreach($guests as $guest)
                <?php $user= \App\User::select('name','lastname','nickname')->where('id',Constants::EQUAL,$guest->user_id)->get()[0];?>
                <div id="TagUser<?=$guest->user_id?>"><?=$user->name.' '.$user->lastname.' ('.$user->nickname.')'?> <?php if($auctionedit==1){?><div class="fa fa-close" onclick="removeGuest(<?=$guest->user_id?>)"></div><?php }?><div>
            @endforeach
        @endif
    </div>
    <div id="UsersInputs">
        @if(isset($guests))
            @foreach($guests as $guest)
                <input type="hidden" class="UserInput" name="invitados[]" value="<?=$guest->user_id?>" id="InputUser<?=$guest->user_id?>">
            @endforeach
        @endif
    </div>
    
</div>
            <textarea name="descri" placeholder="description"<?=($auctionedit==0)?'disabled':''?> style="width: 100%"><?=$description?></textarea><br>
            <input type="submit"<?=(($auctionedit+$arriveedit+$batchedit)==0)?'disabled':''?> value="Enviar">
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
    function changePrivacy(){
        if($('#tipoSubasta').val()=='public'){
            $('#guests').fadeOut();
            $('#UsersShow').html('');$('#UsersShowTemp').html('');$('#guests').val('');
        }else{
            $('#guests').fadeIn();
        }
    }
    function getUsers(){
        var $ids=[],$cont=0,$val=$('#guests').val();
        if($val==''){
            $('#UsersShowTemp').html('');
            return;
        }
        $('.UserInput').each(function(){
            $ids[$cont]=$(this).val();
            $cont++;
        });
        console.log($ids)
        $.get('/getusersauctionprivate',{val:$val,ids:$ids},function(result){
            console.log(result)
            var $result=JSON.parse(result),$html='',$inputs='';
            for(var $z=0;$z<$result.length;$z++){
                $html+='<button id="guest'+$result[$z]['id']+'" onclick="addGuest('+$result[$z]['id']+',\''+$result[$z]['name']+' '+$result[$z]['lastname']+'\',\''+$result[$z]['nickname']+'\')">'+$result[$z]['name']+' '+$result[$z]['lastname']+' ('+$result[$z]['nickname']+')'+'</button>';
            }
            $('#UsersShowTemp').html($html);
        })
    }
    function addGuest($id,$name,$username){
        $('#UsersShowTemp').html('');$('#guests').val('');
        $('#UsersShow').html($('#UsersShow').html()+'<div id="TagUser'+$id+'">'+$name+' ('+$username+') <div class="fa fa-close" onclick="removeGuest('+$id+')"></div><div>');
        $('#UsersInputs').html($('#UsersInputs').html()+'<input type="hidden" class="UserInput" name="invitados[]" value="'+$id+'" id="InputUser'+$id+'">');
    }
    function removeGuest($id){
        $('#TagUser'+$id).remove();
        $('#InputUser'+$id).remove();
    }
    $(document).ready(function(){
        changePrivacy()
    });
</script>
<?php }else{
    echo '<h1>Solo pueden crear subastas los usuarios de tipo vendedor</h1>';
}