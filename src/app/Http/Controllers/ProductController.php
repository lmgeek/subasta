<?php
namespace App\Http\Controllers;

use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Product;
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
        return view('products.create',compact('request','units', 'sale'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $fileName = $request->file(Constants::IMAGEN)->getClientOriginalName();
        if ( file_exists('img/products/'.$fileName) ){
//            $fileExt = $request->file(Constants::IMAGEN)->getClientOriginalExtension();
//            $fileName =  $request->input('nombre') . "-" . $request->input('unidad') . "." . $fileExt;
//            $request->file(Constants::IMAGEN)->move( 'img/products',$fileName );
//            $fileName = $request->file(Constants::IMAGEN)->getClientOriginalName();
        } else {
            $request->file(Constants::IMAGEN)->move( 'img/products',$fileName );
        }
        $prod = new Product();
        $prod->name = $request->input('nombre');
        $prod->fishing_code = $request->input('codigo');
        $prod->unit = $request->input('unidad');
        $prod->presentation_unit = $request->input('presen');
        $prod->weigth_small = str_replace(",", ".", $request->input('weight_small') );
        $prod->weigth_medium = str_replace(",", ".", $request->input('weight_medium') );
        $prod->weigth_big = str_replace(",", ".", $request->input('weight_big') );
        $prod->image_name = $fileName;
        $prod->save();
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
        return view('products.edit',compact('product'));
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
        $prod = Product::withTrashed()->findOrFail($id);
        if(!is_null($request->file(Constants::IMAGEN))){
//            if (!is_null($prod->image_name) and file_exists('img/products/'.$prod->image_name)){
//                unlink('img/products/'.$prod->image_name);
//            }
            $fileName = $request->file(Constants::IMAGEN)->getClientOriginalName();
            if ( file_exists('img/products/'.$fileName) ){
                $prod->image_name = $fileName;
            } else {
                $request->file(Constants::IMAGEN)->move( 'img/products',$fileName );
                $prod->image_name = $fileName;
            }
        }
        $prod->fishing_code = $request->input('codigo');
        $prod->name = $request->input('nombre');
        $prod->unit = $request->input('unidad');
        $prod->presentation_unit = $request->input('presen');
        $prod->weigth_small = str_replace(",", ".", $request->input('weight_small') );
        $prod->weigth_medium = str_replace(",", ".", $request->input('weight_medium') );
        $prod->weigth_big = str_replace(",", ".", $request->input('weight_big') );
        $prod->save();
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
}
