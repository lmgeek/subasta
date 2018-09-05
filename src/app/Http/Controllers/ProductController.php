<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DeleteProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Product;
use Illuminate\Http\Request;

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
        $products = Product::withTrashed()->get();
        return view('products.index',compact('request','products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('products.create',compact('request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $fileName = $request->file('imagen')->getClientOriginalName();

        if ( file_exists('img/products/'.$fileName) ){

//            $fileExt = $request->file('imagen')->getClientOriginalExtension();
//            $fileName =  $request->input('nombre') . "-" . $request->input('unidad') . "." . $fileExt;
//            $request->file('imagen')->move( 'img/products',$fileName );
//            $fileName = $request->file('imagen')->getClientOriginalName();

        } else {

            $request->file('imagen')->move( 'img/products',$fileName );

        }


        $prod = new Product();
        $prod->name = $request->input('nombre');
        $prod->unit = $request->input('unidad');
        $prod->weigth = $request->input('weigth');
        $prod->image_name = $fileName;
        $prod->save();

        return redirect('/products');
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

        if(!is_null($request->file('imagen'))){
//            if (!is_null($prod->image_name) and file_exists('img/products/'.$prod->image_name)){
//                unlink('img/products/'.$prod->image_name);
//            }

            $fileName = $request->file('imagen')->getClientOriginalName();
            $request->file('imagen')->move( 'img/products',$fileName );
            $prod->image_name = $fileName;
        }

        $prod->name = $request->input('nombre');
        $prod->unit = $request->input('unidad');
        $prod->weigth = $request->input('weigth');
        $prod->save();

        return redirect('/products');
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

        return redirect('/products');
    }

    public function trash(DeleteProductRequest $request, $id)
    {
        $prod = Product::findOrFail($id);
        $prod->delete();

        return redirect('/products');
    }

    public function restore($id)
    {
        $prod = Product::withTrashed()->findOrFail($id);
        $prod->restore();

        return redirect('/products');
    }
}
