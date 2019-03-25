<?php
namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Product;
use App\ProductDetail;
use App\Batch;
use Illuminate\Http\Request;
use App\Constants;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::withTrashed()->orderBy('name')->get();
        return view('products.index',compact('request','products'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $units = Product::units();
        $array_unit = [""=>"Seleccione..."];
        foreach ($units as $unit){
            $array_unit[$unit] = $unit;
        }
        $units = $array_unit;


        $SALE = Product::sale();
        $array_sele = [""=>"Seleccione..."];
        foreach ($SALE as $sale){
            $array_sele[$sale] = $sale;
        }
        $sale = $array_sele;

        $status = Product::status();

        foreach ($status as $statu){
            $array_status[$statu] = $statu;
        }
        $statusr = $array_status;
        return view('products.create',compact('request','units', 'sale', 'statusr'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
//        insertar el producto
        $fileName = $request->file(Constants::IMAGEN)->getClientOriginalName();
        if ( !empty('img/products/'.$fileName) ){
            $request->file(Constants::IMAGEN)->move( 'img/products',$fileName );
        }
        $prod = new Product();
        $prod->name = $request->name;
        $prod->fishing_code = $request->codigo;
        $prod->image_name = $fileName;
        $prod->save();
        $idpro=$prod->id;
        $status1=$request->statusp;
        $status2=$request->statusm;
        $status3=$request->statusg;
        if (count($prod) <> 0){
            $detail = new ProductDetail();
            $detail->product_id = $idpro;
            $detail->caliber = 'small';
            $detail->presentation_unit = $request->unidadp;
            $detail->sale_unit = $request->salep;
            $detail->weight = str_replace(",", ".", $request->weight_small);
            if ($status1 == 'Desactivado'){
                $detail->deleted_at = date('Y-m-d H:i:s');
            }
            $detail->save();

            $detail2 = new ProductDetail();
            $detail2->product_id = $idpro;
            $detail2->caliber = 'medium';
            $detail2->presentation_unit = $request->unidadm;
            $detail2->sale_unit = $request->salem;
            $detail2->weight = str_replace(",", ".", $request->weight_medium);
            if ($status2 == 'Desactivado'){
                $detail2->deleted_at = date('Y-m-d H:i:s');
            }
            $detail2->save();

            $detail3 = new ProductDetail();
            $detail3->product_id = $idpro;
            $detail3->caliber = 'big';
            $detail3->presentation_unit = $request->unidadg;
            $detail3->sale_unit = $request->saleg;
            $detail3->weight = str_replace(",", ".", $request->weight_big);
            if ($status3 == 'Desactivado'){
                $detail3->deleted_at = date('Y-m-d H:i:s');
            }
            $detail3->save();
        }
        return redirect(Constants::URL_PRODUCTS);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $ProductDetail1 = ProductDetail::withTrashed()->where('product_id','=',$id)->where('caliber','=','small')->limit(1)->orderby('id','DESC')->get()->toArray();
        $ProductDetail2 = ProductDetail::withTrashed()->where('product_id','=',$id)->where('caliber','=','medium')->limit(1)->orderby('id','DESC')->get()->toArray();
        $ProductDetail3 = ProductDetail::withTrashed()->where('product_id','=',$id)->where('caliber','=','big')->limit(1)->orderby('id','DESC')->get()->toArray();
        $datail= $ProductDetail1[0];
        $datail2 = $ProductDetail2[0];
        $datail3 = $ProductDetail3[0];
        $const_small = 0;
        $const_medium = 0;
        $const_big = 0;
        $exitente_lote = Batch::Select('id')->where('product_detail_id', '=', $datail['id'])->get()->toArray();
        if (count($exitente_lote)){
            $const_small = 1;
        }
        $exitente_lote1 = Batch::Select('id')->where('product_detail_id', '=', $datail2['id'])->get()->toArray();
        if (count($exitente_lote1)){
            $const_medium = 1;
        }
        $exitente_lote2 = Batch::Select('id')->where('product_detail_id', '=', $datail3['id'])->get()->toArray();
        if (count($exitente_lote2)){
            $const_big = 1;
        }
        return view('products.edit',compact('product','datail', 'datail2', 'datail3','const_small','const_medium','const_big'));

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditProductRequest $request, $id)
    {
        $product = Product::Select('deleted_at')->where('id', '=', $id)->get()->toArray();
        $registro= count($product);
        if ($registro <> 0)
            {
//        id de los calibre en especifico
        $pro_exitente1 = $request->input('id_small');
        $pro_exitente2 = $request->input('id_medium');
        $pro_exitente3 = $request->input('id_big');
        $uni_presen = $request->input('unidadp');
        $uni_sale = $request->input('salep');
        $uni_presen2 = $request->input('unidadm');
        $uni_sale2 = $request->input('salem');
        $uni_presen3 = $request->input('unidadg');
        $uni_sale3 = $request->input('saleg');
        $name_product = $request->input('name');
        $fishing_code = $request->input('codigo');

            $product_exit = Product::Select('id')->where('id', '<>', $id)->where('name', '=', $name_product)->get()->toArray();
            if (count($product_exit) > 0){
                // mostrar mensaje en la plantalla
                return redirect(Constants::URL_PRODUCTS)
                    ->withErrors(['El dato del campo  Nombre ya ha sido registrado']);
            }
            $product_exit = Product::Select('id')->where('id', '<>', $id)->where('fishing_code', '=', $fishing_code)->get()->toArray();
            if (count($product_exit) > 0){
                // mostrar mensaje en la plantalla
                return redirect(Constants::URL_PRODUCTS)
                    ->withErrors(['El dato del campo  Código Pequero ya ha sido registrado']);
            }
//        buscar relacion de los calibre
        $exitente1 = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente1)->get()->toArray();
        $relations1 =count($exitente1);
        $exitente2 = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente2)->get()->toArray();
        $relations2 =count($exitente2);
        $exitente3 = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente3)->get()->toArray();
        $relations3 =count($exitente3);

//        insertar el primer valor de producto

            $prod = Product::withTrashed()->findOrFail($id);
            if(!is_null($request->file(Constants::IMAGEN))){
                $fileName = $request->file(Constants::IMAGEN)->getClientOriginalName();
                if ( file_exists('img/products/'.$fileName) ){
                    $prod->image_name = $fileName;
                } else {
                    $request->file(Constants::IMAGEN)->move( 'img/products',$fileName );
                    $prod->image_name = $fileName;
                }
            }
            if (($relations1 == 0) && ($relations2 == 0) && ($relations3 == 0)){
            $prod->fishing_code = $request->input('codigo');
            $prod->name = $request->input('name');
            }
        $prod->save();
//            status de por calibre
        $status1=$request->statusp;
        $status2=$request->statusm;
        $status3=$request->statusg;
//        modificar el calibre chico
        $exitente_lote = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente1)->get()->toArray();
        $resul1= count($exitente_lote);
        if ($resul1 < 1){
            $detail = ProductDetail::withTrashed()->findOrFail($pro_exitente1);
            $detail->caliber = 'small';
            $detail->presentation_unit = $request->input('unidadp');
            $detail->sale_unit  = $request->input('salep');
            $detail->weight = str_replace(",", ".", $request->weight_small);
            if ($status1 == 'Desactivado'){
                $detail->status = 0;
            }else{
                $detail->status = 1;
            }
            $detail->save();
        }else{
            $same=ProductDetail::withTrashed()->where('product_id', '=', $id)
                ->where('caliber', '=', 'small')
                ->where('presentation_unit', '=', $uni_presen)
                ->where('sale_unit', '=', $uni_sale)->get()->toArray();
            if (count($same) > 0){
                $detail = ProductDetail::withTrashed()->findOrFail($pro_exitente1);
                $detail->weight = str_replace(",", ".", $request->weight_small);
                if ($status1 == 'Desactivado'){
                    $detail->status = 0;
                }else{
                    $detail->status = 1;
                }
                $detail->save();
            }else{
                //            modifico el status
                $detail = ProductDetail::withTrashed()->findOrFail($pro_exitente1);
                $detail->status = 0;
                $detail->save();

//            agrego el nuevo detalle si ya tiene una relacion
                $detailn = new ProductDetail();
                $detailn->product_id = $id;
                $detailn->caliber = 'small';
                $detailn->presentation_unit = $request->unidadp;
                $detailn->sale_unit  = $request->input('salep');
                $detailn->weight = str_replace(",", ".", $request->weight_small);
                if ($status1 == 'Desactivado'){
                    $detailn->status = 0;
                }else{
                    $detailn->status = 1;
                }
                $detailn->save();
            }
        }
//        modificar el calibre mediano
        $exitente_lote1 = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente2)->get()->toArray();
        $resul2= count($exitente_lote1);
        if ($resul2 < 1){
            $detail2 = ProductDetail::withTrashed()->findOrFail($pro_exitente2);
            $detail2->caliber = 'medium';
            $detail2->presentation_unit = $request->input('unidadm');
            $detail2->sale_unit  = $request->input('salem');
            $detail2->weight = str_replace(",", ".", $request->weight_medium);
            if ($status2 == 'Desactivado'){
                $detail2->status = 0;
            }else{
                $detail2->status = 1;
            }
            $detail2->save();
        }else{
            $same2=ProductDetail::withTrashed()->where('product_id', '=', $id)
                ->where('caliber', '=', 'medium')
                ->where('presentation_unit', '=', $uni_presen2)
                ->where('sale_unit', '=', $uni_sale2)->get()->toArray();
            if (count($same2 ) > 0){
                $detail2 = ProductDetail::withTrashed()->findOrFail($pro_exitente2);
                $detail2->weight = str_replace(",", ".", $request->weight_medium);
                if ($status2 == 'Desactivado'){
                    $detail2->status = 0;
                }else{
                    $detail2->status = 1;
                }
                $detail2->save();
            }else{
                $detail = ProductDetail::withTrashed()->findOrFail($pro_exitente2);
                $detail->status = 0;
                $detail->save();
//          agrego el nuevo detalle si ya tiene una relacion
                $detailn = new ProductDetail();
                $detailn->product_id = $id;
                $detailn->caliber = 'medium';
                $detailn->presentation_unit = $request->unidadm;
                $detailn->sale_unit  = $request->input('salem');
                $detailn->weight = str_replace(",", ".", $request->weight_medium);
                if ($status2 == 'Desactivado'){
                    $detailn->status = 0;
                }else{
                    $detailn->status = 1;
                }
                $detailn->save();
            }
        }
//        modificar el calibre grande
        $exitente_lote2 = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente3)->get()->toArray();
        $resul3= count($exitente_lote2);
        if ($resul3 < 1){
            $detail3 = ProductDetail::withTrashed()->findOrFail($pro_exitente3);
            $detail3->caliber = 'big';
            $detail3->presentation_unit = $request->input('unidadg');
            $detail3->sale_unit  = $request->input('saleg');
            $detail3->weight = str_replace(",", ".", $request->weight_big);
            if ($status3 == 'Desactivado'){
                $detail3->status = 0;
            }else{
                $detail3->status = 1;
            }
            $detail3->save();
        }else{
            $same3=ProductDetail::withTrashed()->where('product_id', '=', $id)
                ->where('caliber', '=', 'big')
                ->where('presentation_unit', '=', $uni_presen3)
                ->where('sale_unit', '=', $uni_sale3)->get()->toArray();
            if (count($same3) > 0){
                $detail3 = ProductDetail::withTrashed()->findOrFail($pro_exitente3);
                $detail3->weight = str_replace(",", ".", $request->weight_big);
                if ($status3 == 'Desactivado'){
                    $detail3->status = 0;
                }else{
                    $detail3->status = 1;
                }
                $detail3->save();
            }else{
                $detail = ProductDetail::withTrashed()->findOrFail($pro_exitente3);
                $detail->status = 0;
                $detail->save();
//          agrego el nuevo detalle si ya tiene una relacion
                $detailn = new ProductDetail();
                $detailn->product_id = $id;
                $detailn->caliber = 'big';
                $detailn->presentation_unit = $request->unidadg;
                $detailn->sale_unit  = $request->input('saleg');
                $detailn->weight = str_replace(",", ".", $request->weight_big);
                if ($status3 == 'Desactivado'){
                    $detailn->status = 0;
                }else{
                    $detailn->status = 1;
                }
                $detailn->save();
            }
        }
        return redirect(Constants::URL_PRODUCTS);
    }else{
            // mostrar mensaje en la plantalla
            return redirect(Constants::URL_PRODUCTS)
                ->withErrors(['El producto no se puede modificar esta desactivado ']);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteProductRequest $request, $id)
    {
        $prod = Product::findOrFail($id);
        $prod->forceDelete();
        return redirect(Constants::URL_PRODUCTS);
    }
    public function trash(DeleteProductRequest $request, $id)
    {
        $prod = Product::findOrFail($id);
        $ProductDetail1 = ProductDetail::withTrashed()->where('product_id', '=', $id)->get()->toArray();

        $registro1 = $ProductDetail1[0]['id'];
        $registro2 = $ProductDetail1[1]['id'];
        $registro3 = $ProductDetail1[2]['id'];
        $exitente1 = Batch::Select('id')->where('product_detail_id', '=', $registro1)->get()->toArray();
        $relations1 = count($exitente1);
        $exitente2 = Batch::Select('id')->where('product_detail_id', '=', $registro2)->get()->toArray();
        $relations2 = count($exitente2);
        $exitente3 = Batch::Select('id')->where('product_detail_id', '=', $registro3)->get()->toArray();
        $relations3 = count($exitente3);
        if (($relations1 < 1) && ($relations2 < 1) && ($relations3 < 1))
        {
            $prod->delete();
            return redirect(Constants::URL_PRODUCTS);
        }else{
                // mostrar mensaje en la plantalla
                return redirect(Constants::URL_PRODUCTS)
                    ->withErrors(['El producto ya tiene venta o subastas asociada ']);

        }
    }
    public function restore($id)
    {
        $prod = Product::withTrashed()->findOrFail($id);
        $prod->restore();
        return redirect(Constants::URL_PRODUCTS);
    }
    public static function getCalibersFromProductId(Request $request){
        $details=ProductDetail::select('caliber')->where('product_id',Constants::EQUAL,$request->id)->get();
        $return=array();
        foreach($details as $prod){
            $return['natural'][]=$prod->caliber;
            $return['translated'][]=trans('general.product_caliber.'.$prod->caliber);
        }
        return json_encode($return);
    }
    public static function getUnitsFromProductIdCaliber(Request $request){
        $details=ProductDetail::select('presentation_unit','sale_unit','id')
                ->where('product_id',Constants::EQUAL,$request->idproduct)
                ->where('caliber',Constants::EQUAL,$request->caliber)
                ->get()[0];
        return json_encode(array(
            'presentation'=>$details->presentation_unit,
            'sale'=>$details->sale_unit,
            'id'=>$details->id
        ));
    }
}
