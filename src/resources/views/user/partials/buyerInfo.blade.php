<dt>{{ trans('users.buyer.dni') }}</dt>
<dd>{{ $info->dni}}</dd>
<br>
<form action="{{ url('user/setbidlimit') }}" method="POST">
                        {{ csrf_field() }}
<label>Límite de compra</label>
<div class="input-group">
	<input placeholder="Límite de compra " name="bid_limit" value="{{ $info->bid_limit}}" type="text" class="form-control number" id="bid_limit">
	<span class="input-group-btn">
	<input type="hidden" name="user_buyer_id" value="{{ $user->id }}" />
	<button type="submit" class="btn btn-primary">Guardar</button> 
	</span>
</div>

	<small><p id="error" style="color: gray; font-style: italic;">   Sólo permite 2 decimales</p></small>
</form>
<script>


    $(document).ready(function () {

        $(document).on('keydown keyup',".number",function(e){
            var evt = e || window.event;
            var x = evt.key;
            var str = this.value;
            var index = str.indexOf(',');
            var check = x == 0 ? 0: (parseInt(x) || -1);
            console.log(x);
            if (index == 0){
                str = "";
            }
            if ( index > -1) {
                str = str.substr( 0, index + 1 ) +
                    str.slice( index ).replace( /,/g, '' );
            }

            str = str.replace(/[^\d|\,]/g,"");

            $(this).val(str);

            if (check === -1 && x != "Backspace" && x != ','){
                return false;
            }

        });

        $("[type='text']").on("blur click change",function(){
            var insert = $(this).val().replace(',', '.');
            var num = parseFloat(insert);
            var cleanNum = num.toFixed(2).replace(".", ",");
            $(this).val(cleanNum);
            console.log(typeof cleanNum);
            if(cleanNum == "NaN"){
                $(this).val('');
//                $('#error').text('Por favor solo ingrese números y son permitidos 2 decimales');
            }
//            if(num/cleanNum < 1){
//                $('#error').text('Por favor solo ingrese números y son permitidos 2 decimales');
//            }
        });
    });
</script>