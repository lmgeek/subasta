<form method="get">
    {{ csrf_field() }}
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                         <div class="form-group">
                                        <label for="comprador">Raz&oacute;n social del comprador</label>
                                        <input type="text" class="form-control" id="comprador" name="comprador" value="{{ $request->get('comprador') }}" autocomplete="off">
                                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                       <br>
					   <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                </div>
            </div>
        </div>
    </div>
</form>