<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\ProductMapping;
use App\CartDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->language  = \Request::header('language');
    }
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
        $msg            = __('words.no_data_available');
        $data           = array();
        if(!empty($productsid)) {
            $products = Product::where('id',$productsid)->first();
            if($products) {
                $StatusCode     = 200;
                $status         = 1;
                $msg            = __('words.retrieved_successfully');
                $data           = new ProductResource($products);
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);

    }

    public function listproductsbycategory(Request $request)
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();

        $RegisterData = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'non_login_token' => 'required',
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
            $category_id        = $requestData['category_id'];
            $non_login_token    = $requestData['non_login_token'];
            // $non_login_token    = $requestData['product_name'];

            $ArrProductID  = ProductMapping::_GetProductByCategoryID($category_id);
            if(!empty($ArrProductID)) {
                // \DB::enableQueryLog();
                $IS_OFFER_NO = CartDetail::$IS_OFFER_NO;
                $STATUS_ACTIVE = Product::$STATUS_ACTIVE;
                $modal = Product::selectRaw('products.*, cart_details.quantity, IF(cart_details.id, 1, 0) AS isAvailableInCart');
                $modal = $modal->leftJoin('cart_details', function($join) use ($non_login_token, $IS_OFFER_NO)
                {
                    $join->on('cart_details.product_id', '=', 'products.id');
                    $join->where('cart_details.non_login_token', '=', $non_login_token);
                    $join->where('cart_details.is_offer', '=', $IS_OFFER_NO);
                });
                $modal = $modal->join('product_translations','products.id','=','product_translations.product_id');
                $modal = $modal->where('products.status',$STATUS_ACTIVE);
                $modal = $modal->whereIn('products.id',$ArrProductID);
                if(isset($requestData['search_para']) && !empty(trim($requestData['search_para'])))
                {
                    $search_para   = trim($requestData['search_para']);
                    // $modal          = $modal->where("product_translations.product_name", 'LIKE', '%'.$product_name.'%');
                    $modal  = $modal->where(function ($query) use ($search_para){
                            $query->where("product_translations.product_name", 'LIKE', '%'.$search_para.'%')
                                ->orWhere("product_translations.description", 'LIKE', '%'.$search_para.'%');
                    });
                }
                $modal = $modal->groupBy('products.id');
                $modal = $modal->orderBy('product_translations.product_name');
                $modal = $modal->orderBy('product_translations.units_in_stock');
                $products = $modal->paginate($PAGINATION_VALUE);
                // prd(\DB::getQueryLog());
                // prd($products->toArray());
                if($products->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = __('words.retrieved_successfully');
                    foreach ($products as $K => $V) {
                        $products[$K]   = new ProductResource($V);
                    }
                    $data   = $products;
                    // $data   = ProductResource::collection($products);
                }
            }
            // if($requestData['category_id'] == 1) {
            //     $products = Product::where('status',1)->paginate($PAGINATION_VALUE);
            //     if($products->count()) {
            //         $status         = 1;
            //         $StatusCode     = 200;
            //         $msg   = 'Retrieved successfully';
            //         $data      = $products;
            //     }
            // } else {
            //     $products = Product::where('status',1)->where('category_id',$requestData['category_id'])->paginate($PAGINATION_VALUE);
            //     if($products->count()) {
            //         $status         = 1;
            //         $StatusCode     = 200;
            //         $msg   = 'Retrieved successfully';
            //         $data      = $products;
            //     }
            // }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

}
