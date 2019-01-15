function endAuction($id){
    document.getElementById('timer'+$id).innerHTML = "¡Finalizada!";
    $('#OpenerPopUpCompra'+$id).remove();
    $('#Auction_'+$id).addClass('bg-disabled');
    $('#ClosePrice'+$id).html('Precio Final');
    var $html='<div id="Auction_'+$id+'" class="task-listing auction bg-disabled" style="display:none"data-id="'+$id+'">'+$('#Auction_'+$id).html()+'</div>'+$('#FinishedAuctions').html();
    $('#Auction_'+$id).fadeOut();
    setTimeout(function(){$('#Auction_'+$id).remove();},400);
    $('#FinishedAuctions').html($html);
    $('#Auction_'+$id+' .pricing-plan-label .billed-monthly-label').removeClass('red');
    $('#Auction_'+$id+' .icon-material-outline-access-time').removeClass('primary');
    setTimeout(function(){$('#Auction_'+$id).fadeIn();},400);
    if($('#FinishedAuctions > .task-listing').length>3){
        $("#FinishedAuctions").children('.task-listing').last().remove();
    }
}
function timer($id) {
    window['dateend']= new Date($("#timer"+$id).attr('data-timefin'));
    var countDownDate = new Date($("#"+$id).attr('data-timefin')).getTime();
    var now = new Date().getTime();
    var distance = window['dateend']- now,string='';
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    if(days!=0){string+=days+'d ';}
    if(hours!=0 || days!=0){string+=hours+'h ';}
    if(minutes!=0 || hours!=0 || days!=0){string+=minutes+'m ';}
    string+=seconds+'s';
    document.getElementById('timer'+$id).innerHTML = string;
    if (distance < 0) {
        endAuction($id)
    }else{
        setTimeout(function(){timer($id);},1000);
    }
}
window['notificationCounter']=0;
function notifications($type,$product=null,$price=null,$quantity=null,$text=null){
    window['notificationCounter']++;
    if($type==1){
        $html='<div class="notificationauction success" id="notificationauction'+window['notificationCounter']+'"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su compra se ha realizado con exito</div>' +
            '<div class="fieldtitle">Producto</div><div class="fieldvalue">'+$product+'</div>'+
            '<div class="fieldtitle">Precio</div><div class="fieldvalue">'+$price+'</div>'+
            '<div class="fieldtitle">Cantidad</div><div class="fieldvalue">'+$quantity+'</div>'+
            '<div class="total">Total</div><div class="totalvalue">+($price*$quantity)+</div>'+
            '</div></div>'
    }else{
        $html='<div class="notificationauction error" id="notificationauction'+window['notificationCounter']+'"><div class="notificationicon"><i class="icon-line-awesome-close"></i></div><div class="notificationcontent">' +
            '<div class="title">Ha ocurrido un error</div>' +$text+
            '</div></div>'
    }
    $('#notificationsauction').html($html+$('#notificationsauction').html());

    window['timeoutNotification'+window['notificationCounter']]=setTimeout(function(){$('#notificationauction'+window['notificationCounter']).remove();},10000);
}
function makeBid($id){
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
            if($availability<0){
                }else{
                    $('#auctionAvailability'+$id).html('<small style="font-weight: 400">Disponibilidad:</small> '+$availability+' <small>de</small> {{$total}} kg');
                }
            }
    }).fail(function(){
        notifications(0,null,null,null,'No se pudo realizar la compra');
    });
}


function getInfo($id){
    $.get('calculateprice?i=c&auction_id='+$id,function(result){
        $result=JSON.parse(result);
        $('#Price'+$id).html("$"+$result['price']);
        $('#PricePopUp'+$id).html("$"+$result['price']+" <small>x kg</small>")
        $('#timer'+$id).attr('data-timefin',$result['end']);
        if($result['close']==1){
            $('#ClosePrice'+$id).fadeIn();
        }else{
            $('#ClosePrice'+$id).fadeOut();
        }

    });
    setTimeout(function(){getInfo($id)},60000);
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
        var $visible=0,$idsubasta=$(this).data('id');
        $('.AuctionListFilter').each(function() {
            var $field = $(this).data('field'), $val = $(this).data('value');
            if ($('#Auction_' + $idsubasta).data($field) == $val) {
                $visible++;
            }
            console.log('field: '+ $field+' value: '+$('#Auction_' + $idsubasta).data($field));
        });
        console.log($visible)
        if($visible==0){
            $(this).fadeOut();
        }
    });
}
$(document).ready(function(){
    $('.timerauction').each(function(){
        timer($(this).data('id'))
    });
    $('.auction').each(function(){
        getInfo($(this).data('id'))
    })
    auctionListFilter()
});function endAuction($id){
    document.getElementById('timer'+$id).innerHTML = "¡Finalizada!";
    $('#OpenerPopUpCompra'+$id).remove();
    $('#Auction_'+$id).addClass('bg-disabled');
    $('#ClosePrice'+$id).html('Precio Final');
    var $html='<div id="Auction_'+$id+'" class="task-listing auction bg-disabled" style="display:none"data-id="'+$id+'">'+$('#Auction_'+$id).html()+'</div>'+$('#FinishedAuctions').html();
    $('#Auction_'+$id).fadeOut();
    setTimeout(function(){$('#Auction_'+$id).remove();},400);
    $('#FinishedAuctions').html($html);
    $('#Auction_'+$id+' .pricing-plan-label .billed-monthly-label').removeClass('red');
    $('#Auction_'+$id+' .icon-material-outline-access-time').removeClass('primary');
    setTimeout(function(){$('#Auction_'+$id).fadeIn();},400);
    if($('#FinishedAuctions > .task-listing').length>3){
        $("#FinishedAuctions").children('.task-listing').last().remove();
    }
}
function timer($id) {
    window['dateend']= new Date($("#timer"+$id).attr('data-timefin'));
    var countDownDate = new Date($("#"+$id).attr('data-timefin')).getTime();
    var now = new Date().getTime();
    var distance = window['dateend']- now,string='';
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    if(days!=0){string+=days+'d ';}
    if(hours!=0 || days!=0){string+=hours+'h ';}
    if(minutes!=0 || hours!=0 || days!=0){string+=minutes+'m ';}
    string+=seconds+'s';
    document.getElementById('timer'+$id).innerHTML = string;
    if (distance < 0) {
        endAuction($id)
    }else{
        setTimeout(function(){timer($id);},1000);
    }
}
window['notificationCounter']=0;
function notifications($type,$product=null,$price=null,$quantity=null,$text=null){
    window['notificationCounter']++;
    if($type==1){
        $html='<div class="notificationauction success" id="notificationauction'+window['notificationCounter']+'" onclick="notificationClose('+window['notificationCounter']+')"><div class="notificationicon"><i class="icon-line-awesome-check"></i></div><div class="notificationcontent">' +
            '<div class="title">Su compra se ha realizado con exito</div>' +
            '<div class="fieldtitle">Producto</div><div class="fieldvalue">'+$product+'</div>'+
            '<div class="fieldtitle">Precio</div><div class="fieldvalue">'+$price+'</div>'+
            '<div class="fieldtitle">Cantidad</div><div class="fieldvalue">'+$quantity+'</div>'+
            '<div class="total">Total<div class="totalvalue">'+($price*$quantity)+'</div></div>'+
            '</div></div>'
    }else{
        $html='<div class="notificationauction error" id="notificationauction'+window['notificationCounter']+'" onclick="notificationClose('+window['notificationCounter']+')"><div class="notificationicon"><i class="icon-line-awesome-close"></i></div><div class="notificationcontent">' +
            '<div class="title">Ha ocurrido un error</div>' +$text+
            '</div></div>'
    }
    $('#notificationsauction').html($html+$('#notificationsauction').html());

    //window['timeoutNotification'+window['notificationCounter']]=setTimeout(function(){$('#notificationauction'+window['notificationCounter']).remove();},10000);
}
function notificationClose($id){
    $('#notificationauction'+$id).remove();
}
function makeBid($id){
    $.magnificPopup.close();
    $.get("/makeBid?auction_id="+$id + "&amount="+$('#cantidad-'+$id).val(),function(result){
        $result=JSON.parse(result);
        if($result['active']==0){
            notifications(0,null,null,null,'La subasta ha sido cancelada por el vendedor');
            return null;
        }
        if($result['isnotavailability']==0){
            var $availability=$result['availability'];
            $('#auctionAvailability'+$id).html('<small style="font-weight: 400">Disponibilidad:</small> '+$availability+' <small>de</small> '+$result['totalAmount']+' kg');
            notifications(1,$result['product'],$result['price'],$result['amount']);
            if($availability<=0){
                endAuction($id);
            }
        }else{
            notifications(0,null,null,null,'No hay suficiente disponibilidad del producto');
            $('#auctionAvailability'+$id).html('<small style="font-weight: 400">Disponibilidad:</small> '+$availability+' <small>de</small> '+$result['totalAmount']+' kg');
        }
    }).fail(function(){
        notifications(0,null,null,null,'No se pudo realizar la compra');
    });
}


function getInfo($id){
    $.get('calculateprice?i=c&auction_id='+$id,function(result){
        $result=JSON.parse(result);
        $('#Price'+$id).html("$"+$result['price']);
        $("#Auction_"+$id).attr('data-price',$result['price']);
        $("#Auction_"+$id).attr('data-closelimit',$result['close']);
        $('#PricePopUp'+$id).html("$"+$result['price']+" <small>x kg</small>");
        $('#timer'+$id).attr('data-timefin',$result['end']);
        if($result['close']==1){
            $('#ClosePrice'+$id).fadeIn();
        }else{
            $('#ClosePrice'+$id).fadeOut();
        }

    });
    setTimeout(function(){getInfo($id)},60000);
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
        var $visible=0,$check=0,$idsubasta=$(this).data('id');
        $('.AuctionListFilter').each(function() {
            var $field = $(this).data('field'), $val = $(this).data('value');

            if($(this).is(':checked')){
                if ($('#Auction_' + $idsubasta).data($field) == $val) {
                    $visible++;
                }
                $check++;
            }
        });
        if($visible<$check){
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
    })
    auctionListFilter()
});