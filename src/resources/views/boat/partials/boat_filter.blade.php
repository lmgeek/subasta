<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <form>
            <div class="ibox-content">
                <div class="row">
					<div class="col-md-6">
                        <div class="form-group">
                            <label class="font-noraml">Nombre</label>
                            <input type="text" name="name" class="form-control" id="name" value="@if(!is_null($request->get('name'))){{$request->get('name')}}@endif" />
                        </div>
                    </div>
                   
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-noraml">Estado</label>
                            <select data-placeholder="Seleccione una o varias opciones" name="status[]" class="chosen-select" multiple tabindex="4">
                                <option @if (!is_null($request->get('status')) and in_array(\App\Constants::PENDIENTE,$request->get('status'))) selected @endif value="{{ \App\Constants::PENDIENTE }}">{{ trans('general.status.'.\App\Constants::PENDIENTE) }}</option>
                                <option @if (!is_null($request->get('status')) and in_array(\App\Constants::APROBADO,$request->get('status'))) selected @endif value="{{ \App\Constants::APROBADO }}">{{ trans('general.status.'.\App\Constants::APROBADO) }}</option>
                                <option @if (!is_null($request->get('status')) and in_array(\App\Constants::RECHAZADO,$request->get('status'))) selected @endif value="{{ \App\Constants::RECHAZADO }}">{{ trans('general.status.'.\App\Constants::RECHAZADO) }}</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
				 <div class="row">
                   <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-noraml">Vendedor</label>
                            <select data-placeholder="Seleccione una o varias opciones" name="users[]" class="chosen-select" multiple tabindex="4">
                                @foreach($users as $u)
                                    <option @if(!is_null($request->get('users')) and in_array($u->id,$request->get('users'))) selected @endif value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        </div>
                    </div>
                    
                </div>
				<div class="row">
					<div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
				</div>
            </div>
        </form>
    </div>
</div>