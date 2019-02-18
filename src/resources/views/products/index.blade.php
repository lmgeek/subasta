@extends('admin')

<?
//namespace App;
//use Illuminate\Database\Eloquent\Model;
//use DB;
?>

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('products.title') }}</h2>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            @if ($request->session()->has('confirm_msg'))
                <div class="alert alert-success">
                    {{ $request->session()->get('confirm_msg') }}
                </div>
            @endif
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('products.title') }}</h5>

                        <div class="ibox-tools">
                            <a href="{{ route('products.create') }}" class="btn-action">
                                <em class="fa fa-plus text-success"></em> {{ trans('products.new') }}
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Unidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $p)
                                    <tr>
                                        <td style="width: 160px;" class="text-center">
                                            @if (!is_null($p->image_name) and file_exists('img/products/'.$p->image_name) )
                                                <img src="/img/products/{{$p->image_name}}" style="max-width: 150px;max-height: 150px" alt="">
                                            @endif
                                        </td>
                                        <td>{{ $p->fishing_code }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ trans('general.product_units.'.$p->unit) }}</td>
                                        <td>
                                            @if($p->trashed())

                                                <span class="label label-default">Inactivo</span>
                                            @else
                                                <span class="label label-primary">Activo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit',$p) }}" class="btn btn-success" @if(!$p->canBeDeactivate() || $p->trashed()) disabled="true" @endif ><em class="fa fa-edit"></em> Editar</a>
                                            <form action="{{ route('products.restore',$p) }}" class="restoreForm_{{ $p->id }}"  method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                <a href="#" class="btn btn-primary restoreItem" data-id="{{ $p->id }}" @if(!$p->trashed() ) disabled="true" @endif ><em class="fa fa-eye"></em> Activar</a>
                                            </form>

                                            <form action="{{ route('products.trash',$p) }}" class="trashForm_{{ $p->id }}"  method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                <a href="#" class="btn btn-warning trashItem" data-id="{{ $p->id }}" @if(!$p->canBeDeactivate() || $p->trashed()) disabled="true" @endif ><em class="fa fa-eye-slash"></em> Desactivar</a>

                                            </form>
                                            @if($p->canBeDeleted())
                                                <form action="{{ route('products.destroy',$p) }}" class="deleteForm_{{ $p->id }}"  method="post" style="display: inline-block">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <a href="#" class="btn btn-danger deleteItem" data-id="{{ $p->id }}"><em class="fa fa-trash"></em> Borrar</a>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
           $(".deleteItem").click(function(){
                if(confirm('¿Está seguro que desea borrar el producto?')){
                    id = $(this).data('id');
                    $(".deleteForm_"+id).submit();
                }
               return false;
           });
            $(".trashItem").click(function(){
                if(confirm('¿Está seguro que desea desactivar el producto?')){
                    id = $(this).data('id');
                    $(".trashForm_"+id).submit();
                }
               return false;
           });
            $(".restoreItem").click(function(){
                if(confirm('¿Está seguro que desea activar el producto?')){
                    id = $(this).data('id');
                    $(".restoreForm_"+id).submit();
                }
               return false;
           });
        });
    </script>

@endsection

@section('stylesheets')
    <link href="/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    {{--<link href="/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">--}}
    <link href="/css/plugins/chosen/chosen.css" rel="stylesheet">
@endsection