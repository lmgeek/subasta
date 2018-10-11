<section id="contact" class=" gray-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center m-t-lg m-b-lg">
                <p style="color: #FFFFFF">
                    <img alt="image" height="20" src="{{ asset('/landing/img/logo_subastas_header.png') }}" />
                    <br>
                    <strong>
                    Copyright &copy; <? echo date('Y'); ?> </strong>
                     Todos los derechos reservados.<br>
                    Terminos y condiciones, Privacidad y Notificación sobre Cockies.
                </p>
                <font color="#ffffff">poweder by </font><img class="text-center" alt="image" width="80px" src="{{ asset('/img/netlabs-footer.png') }}" />
            </div>
        </div>
    </div>
</section>




<!--
<section id="contact" class="gray-section contact">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Contacto</h1>
                <p>Cualquier consulta o duda puede contactarse con nosotros</p>
            </div>
        </div>
        <div class="row m-b-lg">
            <div class="col-lg-3 col-lg-offset-3">
                <address>
                    <strong><span class="navy">Company name, Inc.</span></strong><br/>
                    795 Folsom Ave, Suite 600<br/>
                    San Francisco, CA 94107<br/>
                    <abbr title="Phone">P:</abbr> (123) 456-7890
                </address>
            </div>
            <div class="col-lg-4">
                <p class="text-color">
                    Consectetur adipisicing elit. Aut eaque, totam corporis laboriosam veritatis quis ad perspiciatis, totam corporis laboriosam veritatis, consectetur adipisicing elit quos non quis ad perspiciatis, totam corporis ea,
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="mailto:test@email.com" class="btn btn-primary">Send us mail</a>
                <p class="m-t-sm">
                    Or follow us on social platform
                </p>
                <ul class="list-inline social-icon">
                    <li><a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong>&copy; <? echo date('Y'); ?> Subastas Ya</strong><br/> consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
            </div>
        </div>
    </div>
</section>

-->




<!-- Scripts -->
<!-- Landing -->
<script src="https://unpkg.com/currency.js@1.1.4/dist/currency.min.js"></script>
<script src="{{ asset('/landing/js/jquery-2.1.1.js') }}"></script>
<script src="{{ asset('/landing/js/pace.min.js') }}"></script>
<script src="{{ asset('/landing/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/landing/js/classie.js') }}"></script>
<script src="{{ asset('/landing/js/cbpAnimatedHeader.js') }}"></script>
<script src="{{ asset('/landing/js/wow.min.js') }}"></script>
<script src="{{ asset('/landing/js/inspinia.js') }}"></script>

    <script src="{{ asset('/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    {{--<script src="{{ asset('/js/plugins/toastr/toastr.min.js') }}"></script>--}}
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>


    <script src="{{ asset('/js/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/js/plugins/datetimepicker/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('/js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/star_rating/jquery.raty.js') }}"></script>
    <script src="{{ asset('/js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/plugins/jsKnob/jquery.knob2.js') }}"></script>
    <script src="{{ asset('/js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script>

    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $(document.body).on('hidden.bs.modal', function () {
                $(".amount-bid-modal").val('');
                $(".modal-price").html('');
                $(".content-danger").html('');
        });
    
        $(".amount-bid-modal").on("keyup",validateAvailability);

    });

    function validateAvailability(){
        var auctionId = $(this).attr('auctionId');
        var price = $(".hid-currentPrice-"+auctionId).val();
        var value = $(this).val();
        var disponible = parseInt($(".target-total").attr("target"));
        if (disponible < parseInt(value)){
            $(this).val(disponible);
            value = disponible;
        }

        // var total =   price..replace(/[aiou]/gi,'e')
        var total =   currency(price).multiply(value);
        $(".modal-total-"+auctionId).html('Total $' + currency(total,{ separator: ".", decimal: ","}).format() );
    }

    function calculatePrice(auctionId)
    {
        $.ajax({
          method: "GET",
          url: "/calculateprice?auction_id="+auctionId,
          success: function(data)
          {
              var price = currency(parseFloat(data),{ separator: ".",decimal: ","}).format();
            $(".currentPrice-"+auctionId).html('<font size="4px">$</font>' + price + ' xKg');
            $(".hid-currentPrice-"+auctionId).val(data);

                if($('#bid-Modal-'+auctionId).is(':visible'))
                {
                    var total = ( $("#amount-bid-" + auctionId ).val()  * data);
                    $(".modal-total-"+auctionId).html('Total $' + currency(total.toFixed(2),{ separator: ".", decimal: ","}).format());
                }

          }
        });
    }

    function showBill(text){
                toastr.remove()
                var options = {
                  "closeButton": true,
                  "positionClass": "toast-top-right",
                  "showDuration": "99999",
                  "debug": false,
                  "onclick": null,
                  "hideDuration": "99999",
                  "extendedTimeOut":"999999",
                  "timeOut": "99999",
                  "onHidden":function(){
                        location.reload();
                  }
                };

            toastr.success(text, '', options);

    }

    function showBillError(text){
                toastr.remove()
                var options = {
                  "closeButton": true,
                  "positionClass": "toast-top-right",
                  "showDuration": "99999",
                  "debug": false,
                  "onclick": null,
                  "hideDuration": "99999",
                  "extendedTimeOut":"999999",
                  "timeOut": "99999",
                  "onHidden":function(){
                        location.reload();
                  }
                };

            toastr.error(text, '', options);

    }


    $(document).ready(function(){
        $(".amount-bid-modal").on("focusout",function (e) {
            console.log(this.value);
        });

        $('.chosen-select').chosen({width:"100%"});



        $(".cancelAuction").click(function(){
            if(!confirm('Esta seguro que quiere cancelar la subasta?')){
                return false;
            }
        });


        $(".auctionIds").each(function(){
            calculatePrice($(this).val());
        });

        $(".dialInterval").knob({
            'format' : function (value) {
                var m = Math.floor(value/60);
                var s = value%60;
                if (s<10)
                    s = "0"+s;

                if (m<10)
                    m = "0"+m;

                return m+":"+s;

            }
        });

        setInterval(function(){
            $('.dialInterval').each(function(k,v){
                valores = $(v).val().split(':');
                if (valores.length == 2){
                    nuevoValor = (valores[0]*60)+(valores[1]-1);
                }else{
                    nuevoValor = valores[0]-1;
                }

                if (nuevoValor == 0){
                    nuevoValor = parseInt($(v).data('max'))-1;
                    var auctionId = $(v).attr('auctionId');
                    var active = $(v).attr('active');
                    if (active==1){
                        calculatePrice(auctionId);
                    }
                }
                $(v).val(nuevoValor).trigger("change");
            });
        }, 1000);

        setInterval(function(){
            $('.dialLeft').each(function(k,v){
                valores = $(v).val().split(':');
                if (valores.length == 3){
                    nuevoValor = (valores[0]*3600)+(valores[1]*60)+(valores[2]-1);
                }else{
                    nuevoValor = valores[0]-1;
                }
                $(v).val(nuevoValor).trigger("change");
            });
        }, 1000);

        $(".dialLeft").knob({
            'format' : function (value) {
                 var h = Math.floor(value/3600);
                 if (h<10)
                    h = "0"+h;
                 var m = Math.floor((value-(h*3600))/60);
                 if (m<10)
                    m = "0"+m;
                 var s = value%60;
                return h+":"+m+":"+s;

            }
        });

        $(".make-bid").click(function(){
            var auctionId = $(this).attr('auctionId');
            var amount = $("#amount-bid-"+auctionId).val();
            makeBid(auctionId,amount);
        });

        $(".amount-bid").keypress(function(e) {
            if(e.which == 13) {
                var auctionId = $(this).attr('auctionId');
                var amount = $("#amount-bid-"+auctionId).val();
                makeBid(auctionId,amount);
                return false;
            }
        });

        $('.quality').each(function(k,v){
            sc = $(v).data('score');
            $(v).raty({
                readOnly: true,
                score: sc,
                starType: 'i',
                hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
            });
        });

    });

    $(document).on("keypress",".amount-bid-modal",function(e){
        var x = e.keyCode || e.which;
        console.log(x);
        if (x == 44 || x == 45 || x == 46 || x == 101){
            return false;
        }

    });

    function makeBid(auctionId,amount)
    {
        var cDispo =  parseInt($(".s-disponible-" +auctionId).html());

        if ( amount <= cDispo  ){
            $.ajax({
                          method: "GET",
                          dataType:"json",
                          url: "/makeBid?auction_id="+auctionId + "&amount="+amount,
                          success: function(data)
                          {
                            if (data.active == 0)
                            {
                                $(".modal").modal('hide');
                                        var note = '';
                                            note+= '<table>';
                                                note+= '<tr>';
                                                    note+= '<td colspan="2"><strong>La subasta ha sido cancelada por el vendedor</strong></td>';
                                                note+= '</tr>';
                                            note+= '</table>';
                                    showBillError(note);
                            }else {
                                if (data.isnotavailability == 0)
                                {
                                    $(".modal").modal('hide');
                                        var note = '<table>';
                                            note+= '<tr>';
                                                note+= '<td colspan="2"><strong>Su compra se ha realizado con éxito</strong></td>';
                                                note+= '</tr>';
                                                note+= '<tr><td colspan="2" style="border-bottom:1px solid"></td>';
                                                note+= '</tr>';
                                                note+= '<tr>';
                                                note+= '<td>Producto</td>';
                                                note+= '<td>'+data.product+'</td>';
                                                note+= '</tr>';
                                                note+= '<tr>';
                                                note+= '<td>Precio</td>';
                                                note+= '<td>$ '+data.price+'</td>';
                                                note+= '</tr>';
                                                note+= '<tr>';
                                                note+= '<td>Cantidad</td>';
                                                note+= '<td>'+data.amount+ ' ' + data.unit  + '</td>';
                                                note+= '</tr>';
                                                note+= '<tr><td colspan="2" style="border-bottom:1px solid"></td>';
                                                note+= '</tr>';
                                                note+= '<tr>';
                                                note+= '<td><strong>Total</strong></td>';
                                                note+= '<td><strong>$ '+ currency( currency(data.price).multiply(data.amount) ).format() +'</strong></td>';
                                                note+= '</tr>';
                                            note+= '</tr>';
                                        note+='<table>';
                                        $(".bid-button-act").attr("disabled",true);
                                        showBill(note);
                                }else{
                                    var note = '';

                                        note+= '<div class="alert alert-danger alert-dismissible" role="alert">';
                                                    note+= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                                                    note+= 'Sólo quedan disponibles ' + data.availability + ' ' + data.unit + ' de ' + data.product ;
                                        note+= '</div>';

                                        $(".content-danger-" +auctionId ).html(note);
                                        $("#amount-bid-" +auctionId).val(data.availability);
                                        $("#amount-bid-" +auctionId).attr('max',data.availability);
                                        $(".s-disponible-" +auctionId).html(data.availability);

                                        var price = $(".hid-currentPrice-"+auctionId).val();
                                        var total = price * data.availability;
                                        $(".modal-total-"+auctionId).html('Total $' + currency(total).format() );

                                        if (data.availability < 0)
                                        {
                                            $("#amount-bid-" +auctionId).attr('disabled',true);
                                            $(".mak-bid-"+auctionId).hide();
                                        }

                                }
                            }
                          }
                });
        }else{

            var note = '';
            note+= '<div class="alert alert-danger alert-dismissible" role="alert">';
            note+= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            note+= 'Sólo quedan disponibles ' + cDispo + ' ' + $(".modal-unit-"+auctionId).html() ;
            note+= '</div>';
            $(".content-danger-" +auctionId ).html(note);
            $("#amount-bid-" +auctionId).val(cDispo);
            $("#amount-bid-" +auctionId).attr('max',cDispo);


            var price1 = $(".hid-currentPrice-"+auctionId).val();
            var total1 = currency(price1).multiply(cDispo);
            $(".modal-total-"+auctionId).html('Total $' + currency(total1).format() );


            if (cDispo == 0)
            {
                $("#amount-bid-" +auctionId).attr('disabled',true);
                $(".mak-bid-"+auctionId).hide();
            }


        }
    }

    </script>

</body>
</html>
