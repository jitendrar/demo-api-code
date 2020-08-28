<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = 'The requested can not find the Product.';
        $data           = array();
        $products = Product::where('status',1)->paginate(5);
        if($products->count()) {
            $status         = 1;
            $StatusCode     = 200;
            $msg   = 'Retrieved successfully';
            $data      = $products;
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        return response($ArrReturn, $StatusCode);
        // return response([ 'products' => ProductResource::collection($products), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = 'The requested can not find the Product.';
        $data           = array();
        if($product) {
            $status         = 1;
            $StatusCode     = 200;
            $msg   = 'Retrieved successfully';
            $data      = new ProductResource($product);
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        return response($ArrReturn, $StatusCode);
        // return response([ 'product' => new ProductResource($product), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $products)
    {
        //
    }

    public function productdetails($productsid=0) {
        if(!empty($productsid)) {
            $products = Product::where('id',$productsid)->first();
            return response([ 'productdetails' => ProductResource::collection($products), 'message' => 'Retrieved successfully'], 200);
        }
    }
}
