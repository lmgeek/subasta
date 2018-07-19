<dt>{{ trans('users.buyer.dni') }}</dt>
<dd>{{ $info->dni}}</dd>
<br>
<form action="{{ url('user/setbidlimit') }}" method="POST">
                        {{ csrf_field() }}
<label>Limite de compra</label>
<div class="input-group">
	<input placeholder="Limite de compra " name="bid_limit" value="{{ $info->bid_limit}}" type="text" class="form-control"> 
	<span class="input-group-btn"> 
	<input type="hidden" name="user_buyer_id" value="{{ $user->id }}" />
	<button type="submit" class="btn btn-primary">Guardar</button> 
	</span>
</div>
</form>