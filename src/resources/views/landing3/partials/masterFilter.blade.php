<?php
use App\Product;
use App\Ports;
use App\User;
use App\Constants;
use Illuminate\Pagination\LengthAwarePaginator;
?>
<div class="sidebar-widget">
    <h3>Puertos</h3>
    <div class="checkbox">
        @foreach($ports as $key=>$valor)
        <input type="checkbox" id="Port{{$key}}" onclick="auctionListFilter()" class="AuctionListFilter" data-field="port" data-value="{{$key}}" name="port"<?=($port_id==$key || old('port')==$key)?'checked':''?>>
            <label for="Port{{$key}}"><span class="checkbox-icon"></span> {{Ports::getPortById($key)}} ({{$valor}})</label>
        @endforeach
    </div>
</div>

<!-- Productos -->
<div class="sidebar-widget">
    <h3>Productos</h3>
    <div class="checkbox">
        @foreach($products as $key=> $valor)
        <?php $productname=Product::getProductFromId($key);?>
        <input type="checkbox" id="Product{{$key}}" <?=($query!=null)?Constants::checkSearchQuery($productname,$query):''?> onclick="auctionListFilter()" class="AuctionListFilter" data-field="product" data-value="{{$key}}">
        <label for="Product{{$key}}"><span class="checkbox-icon"></span> {{$productname}} ({{$valor}})</label>
        @endforeach
    </div>
</div>

<!-- Calibre -->
<div class="sidebar-widget">
    <h3>Calibre</h3>
    <div class="checkbox">
        @foreach($caliber as $key=>$valor)
        <?php $caliber=\App\Constants::caliber($key);?>
        <input type="checkbox" id="Caliber{{$key}}" <?=($query!=null)?Constants::checkSearchQuery($caliber,$query):''?> onclick="auctionListFilter()" class="AuctionListFilter" data-field="caliber" data-value="{{$key}}">
        <label for="Caliber{{$key}}"><span class="checkbox-icon"></span> {{ucfirst($caliber)}} ({{$valor}})</label>
        @endforeach
    </div>
</div>

<!-- Calidad -->
<div class="sidebar-widget">
    <h3>Calidad</h3>
    <div class="checkbox">
        @for($z=1;$z<=5;$z++)
        <input type="checkbox" id="quality<?=$z?>"  onclick="auctionListFilter()" class="AuctionListFilter" data-field="quality" data-value="<?=$z?>" >
        <label for="quality<?=$z?>"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="<?=$z?>">&nbsp;&nbsp;<?=($quality[$z]>0)?"($quality[$z])":''?></div></label>
        @endfor
    </div>
</div>

<!-- Budget -->
<div class="sidebar-widget">
    <h3>Precio</h3>
    <div class="margin-top-55"></div>

    <!-- Range Slider -->
    <input class="range-slider"  type="text" value="" data-slider-currency="$" data-slider-min="<?=$prices['min']?>" data-slider-max="<?=$prices['max']?>" data-slider-step="25" data-slider-value="[<?=$prices['min']?>,<?=$prices['max']?>]" id="PriceFilter"/>
    <div class="checkbox margin-top-15">
        <input type="checkbox" id="CloseLimitPrice" onclick="auctionListFilter()" class="AuctionListFilter" data-field="close" data-value="1">
        <label for="CloseLimitPrice" class="red"><span class="checkbox-icon"></span><em class="icon-line-awesome-exclamation-circle red"></em> Cerca de precio l&iacute;mite <div id="CloseFilter" class="FilterNumber"><?=($close>0)?"($close)":''?></div></label>
    </div>
</div>

<!-- Barco -->
<div class="sidebar-widget">
    <h3>Barco</h3>
    <div class="checkbox">
        @for($z=1;$z<=5;$z++)
        <input type="checkbox" id="UserRating<?=$z?>" onclick="auctionListFilter()" class="AuctionListFilter" data-field="userrating" data-value="<?=$z?>">
        <label for="UserRating<?=$z?>"><span class="checkbox-icon"></span> <div class="star-rating" data-rating="<?=$z?>">&nbsp;&nbsp;<?=($ratings[$z]>0)?"($ratings[$z])":''?></div></label>
        @endfor
    </div>
</div>
<!-- Usuarios -->
<div class="sidebar-widget">
    <h3>Vendedores</h3>
    <div class="checkbox">
        @foreach($users as $key=>$val)
        <?php $user=User::getUserById($key);?>
        <input type="checkbox" id="Usuario{{$key}}" <?=($query!=null)?Constants::checkSearchQuery($user,$query):''?> onclick="auctionListFilter()" class="AuctionListFilter" data-field="user" data-value="{{$key}}">
        <label for="Usuario{{$key}}"><span class="checkbox-icon"></span> {{$user}} ({{$val}})</label>
        @endforeach
    </div>
</div>
