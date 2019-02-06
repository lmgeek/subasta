<form method="get">
    {{ csrf_field() }}
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-noraml">Compradores</label>
                        <select data-placeholder="Seleccione una o varias opciones" name="buyer[]" class="chosen-select" multiple tabindex="4">
                            @foreach($buyers as $b)
                                <option @if (!is_null($request->get('buyer')) and in_array($b->user->id,$request->get('buyer'))) selected @endif value="{{ $b->user->id }}">{{ $b->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-noraml">Estado</label>
                        <select data-placeholder="Seleccione una o varias opciones" name="status[]" class="chosen-select" multiple tabindex="4">
                            <option @if (!is_null($request->get('status')) and in_array(\App\Constants::PENDIENTE,$request->get('status'))) selected @endif value="{{ \App\Constants::PENDIENTE }}"    >{{ trans('general.bid_status.'.\App\Constants::PENDIENTE) }}</option>
                            <option @if (!is_null($request->get('status')) and in_array(\App\Constants::CONCRETADA,$request->get('status'))) selected @endif value="{{ \App\Constants::CONCRETADA }}"   >{{ trans('general.bid_status.'.\App\Constants::CONCRETADA) }}</option>
                            <option @if (!is_null($request->get('status')) and in_array(\App\Constants::NO_CONCRETADA,$request->get('status'))) selected @endif value="{{ \App\Constants::NO_CONCRETADA }}">{{ trans('general.bid_status.'.\App\Constants::NO_CONCRETADA) }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </div>
    </div>
</form>