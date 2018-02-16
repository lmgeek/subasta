@extends('admin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('sellerBoats.batch_register', ['boatName' => $boat->name])}}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Error</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('sellerBoats.batch_info') }}</h5>
                        <div class="ibox-tools">
                            <a class="btn-action" data-toggle="modal" data-target="#addBatchModal">
                                <i class="fa fa-plus text-success"></i> {{ trans('sellerBoats.add_batch') }}
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('sellerboat.batch') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $arrive_id }}">
                        <div class="ibox-content">
                            <div id="products">
                                <div class="row text-center initialText">
                                    {{ trans('sellerBoats.batch_no_result') }}
                                </div>
                                <div class="row border-bottom titlesDiv" style="display: none">
                                    <div class="col-md-3">
                                        <strong>{{ trans('sellerBoats.product') }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>{{ trans('sellerBoats.caliber') }}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>{{ trans('sellerBoats.quality') }}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>{{ trans('sellerBoats.inity') }}</strong>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-footer text-right">

                            <button type="submit" class="btn-save-batch btn btn-primary noDblClick" data-loading-text="Guardando...">{{ trans('sellerBoats.save_batch') }}</button>

                            <a href="{{ url('home') }}" class="btn btn-danger">{{ trans('sellerBoats.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="addBatchModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{ trans('sellerBoats.new_batch') }}</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="product">Producto</label>
                            <select  class="form-control" name="product" id="product">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-unity="{{ trans('general.product_units.'.$p->unit) }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="caliber">{{ trans('sellerBoats.caliber') }}</label>
                            <select  class="form-control" name="caliber" id="caliber">
                                @foreach($calibers as $c)
                                    <option value="{{ $c }}">{{ trans('general.product_caliber.'.$c) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quality">{{ trans('sellerBoats.quality') }}</label>
                            <div id="quality" class="text-warning"></div>
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ trans('sellerBoats.amount') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="amount">
                                <span class="input-group-addon" id="unity"></span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add_batch">{{ trans('sellerBoats.add_batch') }}</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('sellerBoats.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('/js/plugins/star_rating/jquery.raty.js') }}"></script>
    <script>
        var c=0;
        $(document).ready(function(){
		
			$(".btn-save-batch").attr('disabled',true);

            $("#product").change(function(){
                $("#unity").html( $(this).find('option:selected').data('unity') );
            }).triggerHandler('change');

            $("#quality").raty({
                starType : 'i',
                hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
            });

            $("#add_batch").click(function(){
                if(checkForm()) {
                    var $productsDiv = $("#products");
                    var $divRow = $("<div>", {class: "row border-bottom"});
                    var $divHiddenInputDiv = $("<div>", {class: "col-md-8 contentInput", style: "display: none"});
                    var $divName = $("<div>", {class: "col-md-3"}).html($("#product option:selected").text());
                    var $divCalibre = $("<div>", {class: "col-md-3"}).html($("#caliber option:selected").text());

                    var $divCalidad = $("<div>", {class: "col-md-2 text-warning", style: "font-size:6px"}).append(
                            $("<div>", {id: "q-" + c})
                    );
                    var $divCantidad = $("<div>", {class: "col-md-3"}).html($("#amount").val() + " " + $("#unity").html());

                    var $divDelete = $("<div>", {class: "col-md-1 text-danger", style: "cursor:pointer",title:"{{ trans('sellerBoats.batch_delete') }}"}).append(
                            $("<i>", {class: "fa fa-trash"})
                    );

                    $divDelete.click(function () {
                        if (confirm('{{ trans('sellerBoats.delete_batch_confirm') }}')) {
                            $divRow.remove();
							if ($productsDiv.children().length == 2)
							{
								$('.btn-save-batch').attr('disabled',true);
							}
                        }
                    });

                    $divRow.append($divName);
                    $divRow.append($divCalibre);
                    $divRow.append($divCalidad);
                    $divRow.append($divCantidad);
                    $divRow.append($divDelete);

                    var $productInput = $("<input>", {type: "text", name: "product[]"}).val($("#product").val());
                    var $caliberInput = $("<input>", {type: "text", name: "caliber[]"}).val($("#caliber").val());
                    var $qualityInput = $("<input>", {
                        type: "text",
                        name: "quality[]"
                    }).val($('#quality').raty('score'));
                    var $amountInput = $("<input>", {type: "text", name: "amount[]"}).val($("#amount").val());

                    $divHiddenInputDiv.append($productInput);
                    $divHiddenInputDiv.append($caliberInput);
                    $divHiddenInputDiv.append($qualityInput);
                    $divHiddenInputDiv.append($amountInput);

                    $divRow.append($divHiddenInputDiv);

                    $productsDiv.append($divRow);

                    $(".initialText").hide();
                    $(".titlesDiv").show();

                    $('#q-' + c).raty({
                        readOnly: true,
                        score: $('#quality').raty('score'),
                        starType: 'i',
                        hints: ['1 Estrella', '2 Estrellas', '3 Estrellas', '4 Estrellas', '5 Estrellas']
                    });
                    c++;

                    $("#product").val("");
                    $("#caliber").val("");
                    $("#amount").val("");
                    $('#quality').raty('score', 0);
					$('.btn-save-batch').attr('disabled',false);
                    $('#addBatchModal').modal('hide');
                }
            });
        });

        function checkForm(){
            var isOK = true;
            var msg = "";

            if($("#amount").val() <= 0){
                isOK = false;
                msg = '{{ trans('sellerBoats.batch_must_amount_positive') }}';
            }

            if($('#quality').raty('score') == 0){
                isOK = false;
                msg = '{{ trans('sellerBoats.batch_must_select_quality') }}';
            }

            if(!isOK){
                alert(msg);
            }

            return isOK;
        }
    </script>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/star_rating/jquery.raty.css') }}">
    <style>
        .border-bottom{
            margin-bottom: 5px;
        }
    </style>
@endsection