<?php
Use App\Constants;
$link_limit = Constants::PAGINATE_MAX_LINKS;
$currentpage=(isset($request->current))?$request->current:1;
$lastpage=$paginator->lastPage();
$half_total_links=floor($link_limit/2);
$from=$currentpage-$half_total_links;
$to=$currentpage+$half_total_links;
if ($currentpage < $half_total_links) {
    $to += $half_total_links - $currentpage;
}
if ($lastpage - $currentpage < $half_total_links) {
    $from -= $half_total_links - ($lastpage - $currentpage) - 1;
}
?>
@if ($lastpage > 1)
    <ul class="pagination">
        <li class="{{($currentpage==1)?' disabled':''}}" onclick="getMoreAuctions(100,'#Auctions',1)"><em class="fa fa-angle-double-left"></em></li>
        @for ($z = 1; $z <= $lastpage; $z++)
            @if ($from < $z && $z < $to)
        <li class="{{($currentpage==$z)?' active':''}}" onclick="getMoreAuctions(100,'#Auctions',{{$z}})">{{$z}}</li>
            @endif
        @endfor
        <li class="{{($currentpage==$lastpage)?' disabled':''}}" onclick="getMoreAuctions(100,'#Auctions',{{$lastpage}})"><em class="fa fa-angle-double-right"></em></li>
    </ul>
@endif