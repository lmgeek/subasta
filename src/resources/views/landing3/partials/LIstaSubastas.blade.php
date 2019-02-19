<?php 
$nopic=($limit==1)?0:1;
?>
@if(count($auctions)>0)
    @foreach($auctions as $auction)
        @include('/landing3/partials/auctionNoDetail')
    @endforeach
    @if(isset($limit) && $limit>1)
        @include('/landing3/partials/paginator', ['paginator' => $auctions,'request'=>$request])
    @endif
@endif