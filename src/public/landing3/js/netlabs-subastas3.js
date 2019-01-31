function endAuction($id) {
    $('#timer' + $id).html("¡Finalizada!");
    if (!$('#Auction_' + $id).hasClass('nodelete')){
        $('#OpenerPopUpCompra' + $id).remove();
        $('#Auction_' + $id).addClass('bg-disabled');
        $('#ClosePrice' + $id).html('Precio Final');
        $("#timer" + $id).removeAttr('data-timefin');
        $("#timer" + $id).removeClass('data-timerauction');
        var $html = '<div id="Auction_' + $id + '" data-close="' + $('#Auction_' + $id).data('close') + '" data-endorder="' + $('#Auction_' + $id).data('endorder') + '" data-end="' + $('#Auction_' + $id).data('end') + '" data-price="' + $('#Auction_' + $id).data('price') + '" class="task-listing auction bg-disabled" style="display:none"data-id="' + $id + '">' + $('#Auction_' + $id).html() + '</div>' + $('#FinishedAuctions').html();
        $('#Auction_' + $id).fadeOut();
        $('#Auction_' + $id).remove();
        $('#FinishedAuctions').html($html);
        $('#PriceContainer' + $id).removeClass('red');
        $('#BlueClock' + $id).removeClass('primary');
        $('#OffersCounter' + $id).remove();
        $('#BidsCounter' + $id).remove();
        $('#HotAuction' + $id).remove();
        setTimeout(function () {
            $('#Auction_' + $id).fadeIn();
        }, 400);
        getMoreAuctions(1);
    }
}
function avoidSending(){
    var e=window.event,$key=e.keyCode;
    if($key==13){
        e.preventDefault();
    }
}
function getMoreAuctions($limit=1,$limitcontainer=3){
    var $cant=$('#FeaturedAuctions').children('.auction').length;
    if ($cant >= $limitcontainer) {
        return;
    }
    var $ids='', $cont = 0;
    $('.auction').each(function () {
        $ids+= $(this).data('id');
        $cont++;
        if($cont<$('.auction').length){
            $ids+='**'
        }
    });
    $.get('/getMoreAuctions', {limit:$limit,ids: $ids}, function (result) {
        var $result=JSON.parse(result),$html='';
        if(result!=''){
            for(var $z=0;$z<$result.length;$z++){
                $html+=$result[$z];
            }
            $('#FeaturedAuctions').html($('#FeaturedAuctions').html()+$html);
            $('.auction').each(function () {
                if(!$ids.includes($(this).data('id'))){
                    getInfo($(this).data('id'),1);timer($(this).data('id'));
                }
            });
            $('.popup-with-zoom-anim').magnificPopup({
                type: 'inline',

                fixedContentPos: false,
                fixedBgPos: true,

                overflowY: 'auto',

                closeBtnInside: true,
                preloader: false,

                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in'
            });
            starRating('.star-rating');
            orderAuctions('Featured');
        }else{
            if($('#FeaturedAuctions').children('.auction').length==0){
                $('#FeaturedAuctions').html('<h1 class="text-center">No hay Subastas para mostrar</h1>');
            }
        }
    });
}
function deleteExcessAuctionsFinished(){
    if($('#FinishedAuctions > .task-listing').length>3){
        $("#FinishedAuctions").children('.task-listing').last().remove();
        deleteExcessAuctionsFinished();
    }
}
function timer($id) {
    if ($("#timer" + $id).attr('data-timefin') != null) {
        var $end = new Date($("#timer" + $id).attr('data-timefin'));
        window['now'] = new Date().getTime();
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
function orderAuctions($type='Finished'){
    orderAuction($type);
    deleteExcessAuctionsFinished();
}
function modifyAvailability($id,$availability,$total,$unit){
    var $availabilitytext='<small style="font-weight: 400">Disponibilidad:</small> '+$availability+' <small>de</small> '+$total+' '+$('#UnitAuction'+$id).val();
    $('#auctionAvailabilitypopup'+$id).html($availabilitytext);
    $('#auctionAvailability'+$id).html($availabilitytext);
    $('#cantidad-'+$id).attr('max',$availability);
    if($('#cantidad-'+$id).val()>$availability){
        $('#cantidad-'+$id).val($availability);
    }
    if($availability==0){
        endAuction($id);
    }
}
function updateAuctionData($id,$price=null,$end=null,$endorder=null,$endfriendly=null,$close=null,$hot=null){
    if($price!=null){
        $('#Price' + $id).html("$" + $price);
        $('#Auction_'+$id).attr('data-price',$price);
    }
    if($end!=null){
        $('#timer' + $id).attr('data-timefin', $end);
        $('#Auction_'+$id).attr('data-end',$end);
    }
    if($endorder!=null){
        $('#Auction_'+$id).attr('data-endorder',$endorder);
    }
    if($endfriendly!=null){
        $('#FriendlyDate'+$id).html($endfriendly);
    }
    if($close!=null){
        $('#Auction_'+$id).attr('data-close',$close);
        if ($close == 1) {
            $('#ClosePrice' + $id).fadeIn();
        } else {
            $('#ClosePrice' + $id).fadeOut();
        }
    }
    if($hot!=null){
        $('#Auction_'+$id).attr('data-hot',$hot);
        if ($hot == 1) {
            $('#HotAuction' + $id).fadeIn();
        } else {
            $('#HotAuction' + $id).fadeOut();
        }
    }
}
function openPopupCompra($id){
    $('#PriceBid'+$id).val($('#Auction_'+$id).attr('data-price'));
    $('#PricePopUp' + $id).html("$" + $('#PriceBid'+$id).val() + " <small>x kg</small>");
    gtag('event', 'OpenPopUpCompra', {
        'event_category':'Auction',
        'event_label':'Auction_'+$id
    });
}

function getInfo($id,$firstrun=0) {
    var d = new Date();
    var n = d.getSeconds();
    if ((n == 0 || $firstrun==1) && !$('#Auction_'+$id).hasClass('bg-disabled')) {
        $.get('/calculateprice?i=c&auction_id=' + $id, function (result) {
            var $result = JSON.parse(result);
            updateAuctionData($id,$result['price'],$result['end'],$result['endorder'],$result['endfriendly'],$result['close'],$result['hot']);
            modifyAvailability($id,$result['availability'],$result['amount']);
            modifyOffersCounter($id,$result['bidscounter'],$result['offerscounter']);
        });
    }
    setTimeout(function(){getInfo($id)},1000);
}
window['notificationCounter']=0;
function notifications_close($id){
    $('#notificationauction'+$id).remove();
}
function notifications($type,$product=null,$price=null,$quantity=null,$text=null){
    window['notificationCounter']++;
    var $idnotification=window['notificationCounter'],$html;
    if($type==1){
        $html='<div class="notificationauction success" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su compra se ha realizado con exito</div>' +
            '<div class="fieldtitle">Producto</div><div class="fieldvalue">'+$product+'</div>'+
            '<div class="fieldtitle">Precio</div><div class="fieldvalue">'+$price+'</div>'+
            '<div class="fieldtitle">Cantidad</div><div class="fieldvalue">'+$quantity+'</div>'+
            '<div class="total">Total<div class="totalvalue">'+($price*$quantity)+'</div></div>'+
            '</div></div>'
    }else if($type==2){
        $html='<div class="notificationauction success" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su oferta se ha realizado con exito</div>' +
            '<div class="fieldtitle">Producto</div><div class="fieldvalue">'+$product+'</div>'+
            '<div class="fieldtitle">Precio</div><div class="fieldvalue">'+$price+'</div>'+
            '<div class="total">Gracias por su oferta!</div></div>'+
            '</div></div>'
    }else{
        $html='<div class="notificationauction error" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-close"></i></div><div class="notificationcontent">' +
            '<div class="title">Ha ocurrido un error</div>' +$text+
            '</div></div>'
    }
    $('#notificationsauction').html($html+$('#notificationsauction').html());

    window['timeoutNotification'+$idnotification]=setTimeout(function(){notifications_close($idnotification)},10000);
}
function modifyOffersCounter($id,$bids=null,$offers=null){
    if($bids!=null && $bids==1){
        $('#BidsCounter'+$id).html('<i class="icon-material-outline-local-offer green"></i>'+$bids+' Compra Directa');
    }else if($bids!=null && $bids>0){
        $('#BidsCounter'+$id).html('<i class="icon-material-outline-local-offer green"></i>'+$bids+' Compras Directas');
    }
    if($offers!=null && $offers==1){
        $('#OffersCounter'+$id).html('<i class="icon-material-outline-local-offer green"></i>'+$offers+' Oferta Directa');
    }else if($offers!=null && $offers>0){
        $('#OffersCounter'+$id).html('<i class="icon-material-outline-local-offer green"></i>'+$offers+' Ofertas Directas');
    }
}

function makeBid($id){
    if($('#cantidad-'+$id).val()==''){
        notifications(0,null,null,null,'Tienes que poner una cantidad');
        return;
    }
    $.magnificPopup.close();
    $.get("/makeBid?auction_id="+$id + "&price="+$('#PriceBid'+$id).val()+"&amount="+$('#cantidad-'+$id).val(),function(result){
        //console.log(result);
        var $result=JSON.parse(result);
        if($result['limited']==1){
            notifications(0,null,null,null,'Has superado el limite de compras impuesto a tu usuario');
            return null;
        }
        if($result['active']==0){
            notifications(0,null,null,null,'La subasta ha sido cancelada por el vendedor');
            return null;
        }
        if($result['isnotavailability']==0){
            modifyAvailability($id,$result['availability'],$result['totalAmount']);
            notifications(1,$result['product'],$result['price'],$result['amount']);
            modifyOffersCounter($id,$result['bidscounter'],$result['offerscounter']);
            updateAuctionData($id,$result['price'],null,null,null,null, $result['hot']);
            gtag('event', 'purchase', {
                'event_category':'Auction',
                'event_label':'Auction_'+$id,
                'value':$('#PriceBid'+$id).val()*$('#cantidad-'+$id).val(),
                'transaction_id':$result['bidid'],
                'items':[{
                    'id':$result['productid'],
                    'name':$result['product'],
                    'variant':$result['caliber'],
                    'quantity':$('#cantidad-'+$id).val(),
                    'price':$('#PriceBid'+$id).val()
                }]
            });
        }else{
            notifications(0,null,null,null,$result['error']);
        }

    }).fail(function(){
        notifications(0,null,null,null,'No se pudo realizar la compra');
    });
}
function makeOffer($id){

    if($('#OfferPrice'+$id).val()==''){
        notifications(0,null,null,null,'Tienes que poner un precio');
        return;
    }
    $.magnificPopup.close();
    $.get("/offersAuctionFront?auction_id="+$id + "&prices="+$('#OfferPrice'+$id).val(),function(result){
        var $result=JSON.parse(result);
        if($result['active']==0){
            notifications(0,null,null,null,'La subasta ha sido cancelada por el vendedor');

        }
        if($result['isnotavailability']==0){
            notifications(2,$result['product'],$result['price']);
            modifyOffersCounter($id,$result['bidscounter'],$result['offerscounter']);

        }else{
            notifications(0,null,null,null,$result['error']);

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
    var $visible=[],$checked=0;
    $('.AuctionListFilter').each(function() {
        var $field = $(this).data('field'), $val = $(this).data('value');
        if($(this).is(':checked')){
            $cantauctions=0;
            $('.auction').each(function(){
                console.log($val==$(this).data($field));
                if($val==$(this).data($field)){
                    console.log('#'+$(this).attr('id'))
                    $visible[$cantauctions]='#'+$(this).attr('id');
                    $cantauctions++;
                }
            });
            $checked++;
        }
    });
    console.log($visible)
    if($checked>0){
        $('.auction').fadeOut();
        for(var $z=0;$z<$visible.length;$z++){
            $($visible[$z]).fadeIn();
        }
    }



}
$(document).ready(function(){
    $('.timerauction').each(function(){
        timer($(this).data('id'));
    });
    $('.auction').each(function(){
        getInfo($(this).data('id'),1);
    });
    if($('#MasterFilter').length>0){
        auctionListFilter();
    }
    orderAuctions();
});


//G.B Evitar escribir espacio
function blankSpace(e) {
    var tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    var patron = /[A-Za-z0-9]/;
    var tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}