<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Product;
use App\OrderDetail;
use App\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Validator;

class CartDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
    public function show($address_id=0)
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
    public function update(Request $request, CartDetail $cart_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartDetail $cartDetail)
    {
        //
    }

    public function listcartitem(Request $request)
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = 'The requested can not find the Address.';
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
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
            $user_id = $request->get('user_id');
            if(!empty($user_id)) {
                $cartdata       = CartDetail::where('user_id',$user_id)->get();
                if($cartdata->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = 'Retrieved successfully';
                    foreach ($cartdata as $key => $value) {
                        $data[] = new CartResource($value);
                    }
                }
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    public function addcartitem(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
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
            $requestData = $request->all();
            $ArrProduct = Product::_GetProductByID($requestData['product_id']);
            if($ArrProduct) {
                $unity_price            = $ArrProduct->unity_price;
                $requestData['price']   = $unity_price*$requestData['quantity'];
            }
            $CartDetail = CartDetail::create($requestData);
            if($CartDetail) {
                $StatusCode     = 200;
                $status         = 1;
                $msg            = 'Cart successfully added.';
                $data           = new CartResource($CartDetail);
            } else {
                $StatusCode     = 403;
                $status         = 0;
                $msg            = "Something wrong. Please try again.";
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    public function updatecartitem(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
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
            $requestData = $request->all();
            $CartDetail = CartDetail::where('id',$requestData['id'])->first();
            if($CartDetail) {
                if(isset($requestData['quantity']) && $requestData['quantity'] > 0) {
                    $ArrProduct = Product::_GetProductByID($requestData['product_id']);
                    if($ArrProduct) {
                        $unity_price            = $ArrProduct->unity_price;
                        $requestData['price']   = $unity_price*$requestData['quantity'];
                    }
                    $requestData['status'] = 1;
                    $CartDetail->update($requestData);
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = 'Cart successfully updated.';
                    $data           = new CartResource($CartDetail);
                } else {
                    if($CartDetail->delete()) {
                        $StatusCode     = 200;
                        $status         = 1;
                        $msg            = 'Cart successfully deleted.';
                    }
                }
            } else {
                $StatusCode     = 204;
                $status         = 0;
                $msg            = 'No cart found.';
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

}
