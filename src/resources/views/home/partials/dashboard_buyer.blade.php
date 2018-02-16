@if (Session::has('register_message'))
    <br>
    <div class="alert alert-info">{{trans('register.confirm_register')}}</div>
@endif
<div class="row">
    {!! $widgets['NextsArrives'] !!}
    {!! $widgets['BuyResumen'] !!}
</div>
<div class="row">
    {!! $widgets['AuctionProducts'] !!}
    {!! $widgets['AveragePrices'] !!}
</div>




