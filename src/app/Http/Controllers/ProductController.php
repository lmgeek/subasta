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
        $unidadess1 =$request->unidadp;
        $unidadess2 =$request->unidadm;
        $unidadess3 =$request->unidadg;
        $names =$request->nombre;
//        existencia del produto y su alarma
        $productos  = Product::Select('id')->where('name','=',$names)->where('deleted_at','=',null)->limit(1)->orderby('id','DESC')->get()->toArray();
        if (count($productos) <> 0) {
            $productos0 = ProductDetail::Select('id')->where('product_id', '=', $productos[0])->where('presentation_unit', '=', $unidadess1)->where('caliber', '=', 'small')->get()->toArray();
            if ($productos0 <> null) {
                return redirect("/products/create")
                    ->withInput($request->only('name'))
                    ->withErrors(["El producto: $names calibre chico ya existe",
                    ]);
            }
            $productos1 = ProductDetail::Select('id')->where('product_id', '=', $productos[0])->where('presentation_unit', '=', $unidadess2)->where('caliber', '=', 'small')->get()->toArray();
            if ($productos1 <> null) {
                return redirect("/products/create")
                    ->withInput($request->only('name'))
                    ->withErrors(["El producto: $names calibre mediano ya existe",
                    ]);
            }
            $productos2 = ProductDetail::Select('id')->where('product_id', '=', $productos[0])->where('presentation_unit', '=', $unidadess3)->where('caliber', '=', 'small')->get()->toArray();
            if ($productos2 <> null) {
                return redirect("/products/create")
                    ->withInput($request->only('name'))
                    ->withErrors(["El producto: $names calibre ya existe",
                    ]);
            }
        }
//        insertar el producto
        $fileName = $request->file(Constants::IMAGEN)->getClientOriginalName();
        if ( !empty('img/products/'.$fileName) ){
            $request->file(Constants::IMAGEN)->move( 'img/products',$fileName );
        }
        $prod = new Product();
        $prod->name = $request->nombre;
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
        $produ_id = $product->id;
        $ProductDetail1 = ProductDetail::withTrashed()->where('product_id','=',$produ_id)->where('caliber','=','small')->limit(3)->orderby('id','DESC')->get()->toArray();
        $ProductDetail2 = ProductDetail::withTrashed()->where('product_id','=',$produ_id)->where('caliber','=','medium')->limit(3)->orderby('id','DESC')->get()->toArray();
        $ProductDetail3 = ProductDetail::withTrashed()->where('product_id','=',$produ_id)->where('caliber','=','big')->limit(3)->orderby('id','DESC')->get()->toArray();
        $datail= $ProductDetail1[0];
        $datail2 = $ProductDetail2[0];
        $datail3 = $ProductDetail3[0];

        $exitente_lote = Batch::Select('id')->where('product_detail_id', '=', $datail['id'])->get()->toArray();
        if (count($exitente_lote)){
            $const = 1;
        }else{
            $exitente_lote1 = Batch::Select('id')->where('product_detail_id', '=', $datail2['id'])->get()->toArray();
                if (count($exitente_lote1)){
                    $const = 1;
                }else{
                    $exitente_lote2 = Batch::Select('id')->where('product_detail_id', '=', $datail3['id'])->get()->toArray();
                    if (count($exitente_lote2)){
                        $const = 1;
                    }else{
                        $const = 0;
                    }
        }}

        return view('products.edit',compact('product','datail', 'datail2', 'datail3','const'));
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
        $pro_exitente = ProductDetail::Select('id')->where('product_id', '=', $id)->where('deleted_at','=',null)->limit(3)->orderby('id','DESC')->get()->toArray();

        $exitente = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente[2])->get()->toArray();
        if (count($exitente) == 0){
            $exitente = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente[1])->get()->toArray();
        }
        if (count($exitente) == 0){
            $exitente = Batch::Select('id')->where('product_detail_id', '=', $pro_exitente[0])->get()->toArray();
        }
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
            if (count($exitente) == 0){
            $prod->fishing_code = $request->input('codigo');
            $prod->name = $request->nombre;
            }
        $prod->save();
        $status1=$request->statusp;
        $status2=$request->statusm;
        $status3=$request->statusg;
//        modificar el calibre chico
        $product_exitente = ProductDetail::Select()->where('product_id', '=', $id)->where('caliber','=','small')->where('deleted_at','=',null)->limit(1)->orderby('id','DESC')->get()->toArray();
        $data1= $product_exitente[0];
        $exitente_lote = Batch::Select('id')->where('product_detail_id', '=', $data1['id'])->get()->toArray();
        if (count($exitente_lote) == 0){
            $detail = ProductDetail::withTrashed()->findOrFail($data1['id']);
            $detail->caliber = 'small';
            $detail->presentation_unit = $request->unidadp;
            $detail->sale_unit  = $request->input('salep');
            $detail->weight = str_replace(",", ".", $request->weight_small);
            if ($status1 == 'Desactivado'){
                $detail->deleted_at = date('Y-m-d H:i:s');
            }else{
                $detail->deleted_at = null;
            }
            $detail->save();
        }else{
//            modifico el status
            $detail = ProductDetail::withTrashed()->findOrFail($data1['id']);
            $detail->deleted_at = date('Y-m-d H:i:s');
            $detail->save();

//            agrego el nuevo detalle
            $detailn = new ProductDetail();
            $detailn->product_id = $id;
            $detailn->caliber = 'small';
            $detailn->presentation_unit = $request->unidadp;
            $detailn->sale_unit  = $request->input('salep');
            $detailn->weight = str_replace(",", ".", $request->weight_small);
            if ($status1 == 'Desactivado'){
                $detailn->deleted_at = date('Y-m-d H:i:s');
            }else{
                $detailn->deleted_at = null;
            }
            $detailn->save();
        }

//        modificar el calibre mediano
        $product_exitente1 = ProductDetail::Select('id')->where('product_id', '=', $id)->where('caliber','=','medium')->where('deleted_at','=',null)->limit(1)->orderby('id','DESC')->get()->toArray();
        $data2= $product_exitente1[0];
        $exitente_lote1 = Batch::Select('id')->where('product_detail_id', '=', $data2['id'])->get()->toArray();
        if (count($exitente_lote1) == 0){
            $detail2 = ProductDetail::withTrashed()->findOrFail($data2['id']);
            $detail2->caliber = 'medium';
            $detail->presentation_unit = $request->unidadm;
            $detail2->sale_unit  = $request->input('salem');
            $detail2->weight = str_replace(",", ".", $request->weight_medium);
            if ($status2 == 'Desactivado'){
                $detail2->deleted_at = date('Y-m-d H:i:s');
            }else{
                $detail2->deleted_at = null;
            }
            $detail2->save();
        }else{
            $detail = ProductDetail::withTrashed()->findOrFail($data2['id']);
            $detail->deleted_at = date('Y-m-d H:i:s');
            $detail->save();
//          agrego el nuevo detalle
            $detailn = new ProductDetail();
            $detailn->product_id = $id;
            $detailn->caliber = 'medium';
            $detailn->presentation_unit = $request->unidadm;
            $detailn->sale_unit  = $request->input('salem');
            $detailn->weight = str_replace(",", ".", $request->weight_medium);
            if ($status2 == 'Desactivado'){
                $detailn->deleted_at = date('Y-m-d H:i:s');
            }else{
                $detailn->deleted_at = null;
            }
            $detailn->save();
        }
//        modificar el calibre grande
        $product_exitente2 = ProductDetail::Select('id')->where('product_id', '=', $id)->where('caliber','=','big')->where('deleted_at','=',null)->limit(1)->orderby('id','DESC')->get()->toArray();
        $data3= $product_exitente2[0];
        $exitente_lote2 = Batch::Select('id')->where('product_detail_id', '=', $data3['id'])->get()->toArray();
        if (count($exitente_lote2) == 0){
            $detail3 = ProductDetail::withTrashed()->findOrFail($data3['id']);
            $detail3->caliber = 'big';
            $detail->presentation_unit = $request->unidadg;
            $detail3->sale_unit  = $request->input('saleg');
            $detail3->weight = str_replace(",", ".", $request->weight_big);
            if ($status3 == 'Desactivado'){
                $detail3->deleted_at = date('Y-m-d H:i:s');
            }else{
                $detail3->deleted_at = null;
            }
            $detail3->save();
        }else{
            $detail = ProductDetail::withTrashed()->findOrFail($data3['id']);
            $detail->deleted_at = date('Y-m-d H:i:s');
            $detail->save();
//          agrego el nuevo detalle
            $detailn = new ProductDetail();
            $detailn->product_id = $id;
            $detailn->caliber = 'big';
            $detailn->presentation_unit = $request->unidadg;
            $detailn->sale_unit  = $request->input('saleg');
            $detailn->weight = str_replace(",", ".", $request->weight_big);
            if ($status3 == 'Desactivado'){
                $detailn->deleted_at = date('Y-m-d H:i:s');
            }else{
                $detailn->deleted_at = null;
            }
            $detailn->save();
        }
        return redirect(Constants::URL_PRODUCTS);
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
        $prod->delete();
        return redirect(Constants::URL_PRODUCTS);
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
