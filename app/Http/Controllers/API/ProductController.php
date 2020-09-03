<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = 'The requested can not find the Product.';
        $data           = array();
        if(!empty($productsid)) {
            $products = Product::where('id',$productsid)->first();
            if($products) {
                $StatusCode     = 200;
                $status         = 1;
                $msg            = 'Retrieved successfully';
                $data           = new ProductResource($products);
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        return response($ArrReturn, $StatusCode);

    }

    public function listproductsbycategory(Request $request)
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = 'The requested can not find the Product.';
        $data           = array();

        $RegisterData = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $PAGINATION_VALUE = env('PAGINATION_VALUE');
            $requestData = $request->all();
            if($requestData['category_id'] == 1) {
                $products = Product::where('status',1)->paginate($PAGINATION_VALUE);
                if($products->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg   = 'Retrieved successfully';
                    $data      = $products;
                }
            } else {
                $products = Product::where('status',1)->where('category_id',$requestData['category_id'])->paginate($PAGINATION_VALUE);
                if($products->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg   = 'Retrieved successfully';
                    $data      = $products;
                }
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        return response($ArrReturn, $StatusCode);
    }

}
