window['notificationCounter']=0;
window['now'] = new Date().getTime();
function endAuction($id) {
    $('#timer' + $id).html("¡Finalizada!");
    if (!$('#Auction_' + $id).hasClass('nodelete')){
        $('#OpenerPopUpCompra' + $id).remove();
        $('#ClosePrice' + $id).html('Precio Final');
        $('#ClosePrice' + $id).fadeIn();
        $("#timer" + $id).removeAttr('data-timefin');
        $("#timer" + $id).removeClass('data-timerauction');
        var $html = '<div id="Auction_' + $id + '" data-close="' + $('#Auction_' + $id).data('close') + '" data-endorder="' + $('#Auction_' + $id).data('endorder') + '" data-end="' + $('#Auction_' + $id).data('end') + '" data-price="' + $('#Auction_' + $id).data('price') + '" class="task-listing bg-disabled" style="display:none"data-id="' + $id + '">' + $('#Auction_' + $id).html() + '</div>' + $('#FinishedAuctions').html();
        $('#Auction_' + $id).fadeOut();
        $('#Auction_' + $id).remove();
        $('#FinishedAuctions').html($html);
        $('#PriceContainer' + $id).removeClass('red');
        $('#BlueClock' + $id).removeClass('primary');
        $('#OffersCounter' + $id).remove();
        $('#BidsCounter' + $id).remove();
        $('#HotAuction' + $id).remove();
        $('#AuctionLeft'+$id).css('background-color','#fff');
        $('#LinkSubasta'+$id).attr('href','#');
        setTimeout(function () {
            $('#Auction_' + $id).fadeIn();
        }, 400);
        getMoreAuctions();
    }
}
function avoidSending(){
    var e=window.event,$key=e.keyCode;
    if($key==13){
        e.preventDefault();
    }
}
function getMoreAuctions($limit=1,$idTarget='#FeaturedAuctions',$currentpage=1){
    var $ids=[], $filters=[],$cont = 0;
    $('.auction').each(function () {
        $ids[$cont]=$(this).data('id');
        $cont++;
    });
    $('#Loader').fadeIn();
    if($('#MasterFilter').length>0){
        $filters=getFilters()[0];
        $($idTarget).html('');
    }
    $.post('/getauctions', {limit:$limit,current:$currentpage,ids:$ids,filters:$filters,_token:$('#csrf').attr('content')}, function (result) {
        var $html=result;
        if(result.includes('#MasterFilter')==true){
            $('#Loader').fadeOut();
            notifications(0,null,null,null,'Tu sesion ha expirado');
            return;
        }
        if(result!=''){
            if($idTarget=='#Auctions'){
                $($idTarget).html($html);
            }else{
                $($idTarget).html($($idTarget).html()+$html);
                orderAuctions('Featured');
            }
            inicializeEverything();
        }else{
            if($($idTarget).children('.auction').length==0){
                $($idTarget).html('<h1 class="text-center">No hay Subastas para mostrar</h1>');
            }
        }
    }).always(function(){$('#Loader').fadeOut();});
}
function deleteExcessAuctionsFinished(){
    if($('#FinishedAuctions > .task-listing').length>3){
        $("#FinishedAuctions").children('.task-listing').last().remove();
        deleteExcessAuctionsFinished();
    }
}
function timer($id) {
    if ($("#timer" + $id).attr('data-timefin') != null) {
        $("#timer" + $id).attr('started','1');
        var $end = new Date($("#timer" + $id).attr('data-timefin'));
        window['now'] = new Date().getTime();
        var $difservloc=$('#Loader').data('local') - $('#Loader').data('server');
        var distance = $end - window['now'] - $difservloc, string = '';
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
            var url = 'auction/offers/' + $id;
            $.get(url, function (data) {
            })

            endAuction($id);
        } else {
            window['timer'+$id]=setTimeout(function () {
                timer($id);
            }, 1000);
        }
    }
}
function orderAuction($type='Finished'){
    $('#' + $type + "Auctions").find('.task-listing').sort(function (a, b) {
        var $a = a.getAttribute('data-endorder'), $b = b.getAttribute('data-endorder');
        if($type=='Featured'){
            if ($a > $b) {
                return 1;
            } else if($a<$b){
                return -1;
            }else{
                return 0;
            }
        }else{
            if ($a < $b) {
                return 1;
            } else if($a>$b){
                return -1;
            }else{
                return 0;
            }
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
function openPopupOferta($id){
    gtag('event', 'OpenPopUpOferta', {
        'event_category':'Auction',
        'event_label':'Auction_'+$id
    });
}
function modifyQuantity($id,$direction){
    var $input=$('#cantidad-'+$id);$cant=parseInt($input.val()),$max=parseInt($input.attr('max')),$min=parseInt($input.attr('min'));
    if(!$('#checkbox'+$id).is(':checked')){
        if($direction==0 && $cant>$min){
            $input.val($cant-1);
        }else if($direction==1 && $cant<$max){
            $input.val($cant+1);
        }
    }
    
}
function getInfo($id,$firstrun=0) {
    var d = new Date();
    var n = d.getSeconds();
    if ((n == 0 || $firstrun==1) && !$('#Auction_'+$id).hasClass('bg-disabled') && $('#Auction_'+$id).length>0) {
        console.log('Timeout '+$id+': '+window['Timeout'+$id]);
        $.get('/calculateprice?i=c&auction_id=' + $id, function (result) {
            var $result = JSON.parse(result);
            $('#Loader').attr('data-local',window['now']);
            $('#Loader').attr('data-server',$result['currenttime']);
            updateAuctionData($id,$result['price'],$result['end'],$result['endorder'],$result['endfriendly'],$result['close'],$result['hot']);
            modifyAvailability($id,$result['availability'],$result['amount']);
            modifyOffersCounter($id,$result['bidscounter'],$result['offerscounter']);
        }).done(function(){
             $('.timerauction').each(function(){
                var $id=$(this).data('id');
                if(!$("#timer" + $id).attr('started')){
                    $("#timer" + $id).attr('started','1')
                    timer($id);
                }
            });
        });
    }
    if($('#Auction_'+$id).length>0){
        window['Timeout'+$id]='';
        window['Timeout'+$id]=setTimeout(function(){getInfo($id)},1000);
    }
}
function notifications_close($id){
    $('#notificationauction'+$id).remove();
}
function notifications($type,$product=null,$price=null,$quantity=null,$text=null){
    window['notificationCounter']++;
    var $idnotification=window['notificationCounter'],$html;
    if($type==1){
        $html='<div class="notificationauction success" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su compra se ha realizado con éxito</div>' +
            '<div class="fieldtitle">Producto</div><div class="fieldvalue">'+$product+'</div>'+
            '<div class="fieldtitle">Precio</div><div class="fieldvalue">'+$price+'</div>'+
            '<div class="fieldtitle">Cantidad</div><div class="fieldvalue">'+$quantity+'</div>'+
            '<div class="total">Total<div class="totalvalue">'+(parseFloat($price)*$quantity)+'</div></div>'+
            '</div></div>'
    }else if($type==2){
        $html='<div class="notificationauction success" id="notificationauction'+$idnotification+'" onclick="notifications_close('+$idnotification+')"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su oferta se ha realizado con éxito</div>' +
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

    window['timeoutNotification'+$idnotification]=setTimeout(function(){notifications_close($idnotification)},15000);
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
                    'name':$result['product']+' '+$result['caliber']+' ('+$result['unit']+')',
                    'variant':$result['caliber'],
                    'quantity':$('#cantidad-'+$id).val(),
                    'price':$('#PriceBid'+$id).val()
                }]
            });
        }else{
            notifications(0,null,null,null,'No hay disponibilidad en esta subasta');
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
            gtag('event', 'Offer', {
                'event_category':'Auction',
                'event_label':'ID Auction: '+$id+'. Price: '+$('#OfferPrice'+$id).val()+'ARS. ID Product: '+$result['productid']+'. Product Name: '+$result['product']+' '+$result['caliber']+' - '+$result['unit']+'.'
            });

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
function ThousandSeparator(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function getFilters(){
    var $filters={};
    var $prices=$priceval=$('#PriceFilter').val().toString().split(',');
    $filters['pricemin']=$prices[0]+'**';$filters['pricemax']=$prices[1]+'**';
    var $filterstring='Filter Price: Min='+$priceval[0]+'ARS. Max='+$priceval[1]+'ARS. ';
    $('.AuctionListFilter').each(function(){
        if($(this).is(':checked')) {
            var $fieldtemp=$(this).data('field');
            if ($filters[$fieldtemp] != null) {
                $filters[$fieldtemp] += $(this).data('value') + '**';
                $filterstring+='Field '+$fieldtemp+': '+$(this).data('value')+'. ';
            } else {
                $filters[$fieldtemp] = $(this).data('value') + '**';
                $filterstring+='Field '+$fieldtemp+': '+$(this).data('value')+'. ';
            }
        }
    });
    
    return [$filters,$filterstring];
}
function auctionListFilter(){
    $('.auction').show();
    var $filtergetter=getFilters();
    var $filterstring=$filtergetter[1];
    getMoreAuctions(100,'#Auctions')
    gtag('event', 'FiltradoListaSubastas', {
        'event_category':'Filter',
        'event_label':$filterstring,
        'event_value':$filterstring
    });
}
function inicializeEverything($firstrun=0){
    $('.auction').each(function(){
        var $id=$(this).data('id');
        window['Timeout'+$id]=setTimeout(function(){getInfo($id,1)},1000);
    });
    if($('#MasterFilter').length>0){
        var currencyAttr = $(".range-slider").attr('data-slider-currency');
        $(".range-slider").slider({
            formatter: function(value) {
                return currencyAttr + ThousandSeparator(parseInt(value[0])) + " - " + currencyAttr + ThousandSeparator(parseInt(value[1]));
            },
        }).on('slideStop', auctionListFilter);
        var $cant=0;
        $('.AuctionListFilter').each(function(){
            if($(this).is(':checked')){
                $cant++;
            }
        })
        if($cant>0 && $firstrun==1){
            
            getMoreAuctions(100,'#Auctions');
        }
    }
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
}
$(document).ready(function(){
    inicializeEverything(1);
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

/* G.B fucion para limitar la cantidad de caracteres en un input*/
function inputseMaxLength(Event, Object, MaxLen)
{
    return (Object.value.length <= MaxLen)||(Event.keyCode == 8 ||Event.keyCode==46||(Event.keyCode>=35&&Event.keyCode<=40))
}


/*G.B funcion para no permitir acentos, se debe pasar
como parametro el id de la tag html*/
function doesNotAllowAccents(idTag){

    $(document).on('keydown keyup',idTag,function(e){
        var evt = e || window.event;
        var x = evt.key;
        var str = this.value;
        var index = str.indexOf(',');
        var check = x == 0 ? 0: (parseInt(x) || -1);
        console.log(x);

        str = str.replace(/[^\d|\s|^\w]/g,"");

        $(this).val(str);

    });
}
function homeFilterBuilder(){
    var $query='',$cantselected=$("#port option:selected").length,$text=$('#query').val();
    if($cantselected>0){
        $query+='Ports: ';var $cont=0;
        $("#port option:selected").each(function(){
            $query+=$(this).text();
            $cont++
            if($cont<$cantselected){
                $query+=', '
            }else{
                $query+='. '
            }
        });
    }
    if($text!=''){
        $query+='Query: '+$text+'.'
    }
    $('#ExtraParamsAnalytics').val($query);
}
