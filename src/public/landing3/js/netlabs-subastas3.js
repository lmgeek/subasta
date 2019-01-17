function endAuction($id){
    $('#OpenerPopUpCompra'+$id).remove();
    $('#Auction_'+$id).addClass('bg-disabled');
    $('#ClosePrice'+$id).html('Precio Final');
    $("#timer"+$id).removeAttr('data-timefin');
    $("#timer"+$id).removeClass('data-timerauction');
    $('#timer'+$id).html("¡Finalizada!");
    var $html='<div id="Auction_'+$id+'" class="task-listing auction bg-disabled" style="display:none"data-id="'+$id+'">'+$('#Auction_'+$id).html()+'</div>'+$('#FinishedAuctions').html();
    $('#Auction_'+$id).fadeOut();
    setTimeout(function(){$('#Auction_'+$id).remove();},400);
    $('#FinishedAuctions').html($html);
    $('#PriceContainer'+$id).removeClass('red');
    $('#OffersCounter'+$id).remove();
    setTimeout(function(){$('#Auction_'+$id).fadeIn();},400);
    orderAuctions();
    if($('#FinishedAuctions > .task-listing').length>3){
        $("#FinishedAuctions").children('.task-listing').last().remove();
    }

}

function timer($id) {
    if ($("#timer" + $id).attr('data-timefin') != null) {
        var $end = new Date($("#timer" + $id).attr('data-timefin'));
        window['now'] = new Date().getTime();
        var countDownDate = new Date($("#" + $id).attr('data-timefin')).getTime();
        var distance = $end - window['now'], string = '';
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        if (days != 0) {
            string += days + 'd ';
        }
        if (hours != 0 || days != 0) {
            string += hours + 'h ';
        }
        if (minutes != 0 || hours != 0 || days != 0) {
            string += minutes + 'm ';
        }
        string += seconds + 's';
        $('#timer'+$id).html(string);
        if (distance < 0) {
            endAuction($id);
        } else {
            setTimeout(function () {
                timer($id);
            }, 1000);
        }
    }
}
$cont=0;
function orderAuction($type='Finished'){
    $('#' + $type + "Auctions").find('.task-listing').sort(function (a, b) {
        var $a = a.getAttribute('data-endorder'), $b = b.getAttribute('data-endorder');
        if ($a < $b) {
            return 1;
        } else if($a>$b){
            return -1;
        }else{
            return 0;
        }
    }).appendTo($('#' + $type + "Auctions"));
}
function orderAuctions(){
    orderAuction();
    orderAuction('Featured');
}
function getInfo($id) {
    var d = new Date();
    var n = d.getSeconds();
    if (n == 0 && !$('#Auction_'+$id).hasClass('bg-disabled')) {
        $.get('calculateprice?i=c&auction_id=' + $id, function (result) {
            var $result = JSON.parse(result);
            $('#Price' + $id).html("$" + $result['price']);
            $('#PricePopUp' + $id).html("$" + $result['price'] + " <small>x kg</small>")
            $('#timer' + $id).attr('data-timefin', $result['end']);
            $('#FriendlyDate'+$id).html($result['endfriendly']);
            $('#Auction_'+$id).attr('data-price',$result['price']);
            $('#Auction_'+$id).attr('data-end',$result['end']);
            if ($result['close'] == 1) {
                $('#ClosePrice' + $id).fadeIn();
            } else {
                $('#ClosePrice' + $id).fadeOut();
            }
            modifyOffersCounter($id,$result['offerscounter']);
        });
    }
    orderAuctions();
    setTimeout(function(){getInfo($id)},1000);
}
window['notificationCounter']=0;
function notifications_close($id){
    $('#notificationauction'+$id).remove();
}
function notifications($type,$product=null,$price=null,$quantity=null,$text=null){
    window['notificationCounter']++;
    var $idnotification=window['notificationCounter'];
    if($type==1){
        $html='<div class="notificationauction success" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su compra se ha realizado con exito</div>' +
            '<div class="fieldtitle">Producto</div><div class="fieldvalue">'+$product+'</div>'+
            '<div class="fieldtitle">Precio</div><div class="fieldvalue">'+$price+'</div>'+
            '<div class="fieldtitle">Cantidad</div><div class="fieldvalue">'+$quantity+'</div>'+
            '<div class="total">Total<div class="totalvalue">'+($price*$quantity)+'</div></div>'+
            '</div></div>'
    }else{
        $html='<div class="notificationauction error" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-close"></i></div><div class="notificationcontent">' +
            '<div class="title">Ha ocurrido un error</div>' +$text+
            '</div></div>'
    }
    $('#notificationsauction').html($html+$('#notificationsauction').html());

    window['timeoutNotification'+$idnotification]=setTimeout(function(){notifications_close($idnotification)},10000);
}
function modifyOffersCounter($id,$offers){
    if($offers==1){
        $('#OffersCounter'+$id).html('<i class="icon-material-outline-local-offer green"></i>'+$offers+' Oferta Directa');
    }else if($offers>0){
        $('#OffersCounter'+$id).html('<i class="icon-material-outline-local-offer green"></i>'+$offers+' Ofertas Directas');
    }
}
function makeBid($id){
    console.log('asd');
    $.magnificPopup.close();
    $.get("/makeBid?auction_id="+$id + "&amount="+$('#cantidad-'+$id).val(),function(result){
        $result=JSON.parse(result);
        console.log($result);
        if($result['active']==0){
            notifications(0,null,null,null,'La subasta ha sido cancelada por el vendedor');
            return null;
        }
        if($result['isnotavailability']==0){
            var $availability=$result['availability'];
            $('#auctionAvailability'+$id).html('<small style="font-weight: 400">Disponibilidad:</small> '+$availability+' <small>de</small> '+$result['totalAmount']+' kg');
            notifications(1,$result['product'],$result['price'],$result['amount']);
            modifyOffersCounter($id,$result['offerscounter']);
            if($availability<=0){
                endAuction($id);
            }
        }else{
            notifications(0,null,null,null,'La subasta no tiene suficiente disponibilidad');
        }
    }).fail(function(){
        notifications(0,null,null,null,'No se pudo realizar la compra');
    });
}

function popupCompraDisableText($id) {
    if($('#checkbox'+$id).is(':checked')){
        $('#cantidad-'+$id).attr('disabled','true');
        $('#cantidad-'+$id).val($('#cantidad-'+$id).attr('max'));
    }else{
        $('#cantidad-'+$id).removeAttr('disabled');
    }
}
function auctionListFilter(){
    $('.auction').each(function(){
        var $visible=0,$idsubasta=$(this).data('id'),$checked=0;
        $('.AuctionListFilter').each(function() {
            var $field = $(this).data('field'), $val = $(this).data('value');
            if($(this).is(':checked')){
                if ($('#Auction_' + $idsubasta).data($field) == $val) {
                    $visible++;
                }
                $checked++;
            }

        });
        if($visible<$checked){
            $(this).fadeOut();
        }else{
            $(this).fadeIn();
        }
    });
}
$(document).ready(function(){
    $('.timerauction').each(function(){
        timer($(this).data('id'))
    });
    $('.auction').each(function(){
        getInfo($(this).data('id'))
    });
    if($('#MasterFilter').length>0){
        auctionListFilter();
    }
    orderAuctions();
});