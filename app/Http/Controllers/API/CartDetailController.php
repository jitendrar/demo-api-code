<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Product;
use App\OrderDetail;
use App\Order;
use App\Config;
use App\Address;
use App\User;
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
        $msg            = __('words.no_data_available');
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            // 'user_id' => 'required|numeric',
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
            $non_login_token = $request->get('non_login_token');
            if(!empty($non_login_token)) {
                $gst_charge         = 0;
                $delivery_charge    = 0;
                $gst_charge         = (int)Config::GetConfigurationList(Config::$GST_CHARGE);
                $delivery_charge    = (int)Config::GetConfigurationList(Config::$DELIVERY_CHARGE);
                $cartdata   = CartDetail::with('product')
                                        ->where('non_login_token',$non_login_token)->get();
                if($cartdata->count()) {
                    $CartArr = $cartdata->toArray();
                    $Address = array();
                    if(isset($CartArr[0]['user_id']) && !empty($CartArr[0]['user_id'])) {
                        $Address    = Address::_SelectAddressForCartByUserID($CartArr[0]['user_id']);
                    }
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = __('words.retrieved_successfully');
                    $data['gst_charge'] = $gst_charge;
                    $data['delivery_charge'] = $delivery_charge;
                    $data['address'] = $Address;
                    $data['cart_data'] = CartResource::collection($cartdata);
                    $ORDER_TIME_SLOT_FILE   = env('ORDER_TIME_SLOT_FILE');
                    $JsonFile               = storage_path($ORDER_TIME_SLOT_FILE);
                    $fileContent            = file_get_contents($JsonFile);
                    $data['time_slot']      = json_decode($fileContent,true);
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
        $msg            = __('words.no_data_available');
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
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
            $requestData = $request->all();
            if(isset($requestData['non_login_token']) && !empty($requestData['non_login_token'])) {
                $ArrUSer = User::where('non_login_token',$requestData['non_login_token'])->first();
                if($ArrUSer){
                    $requestData['user_id'] = $ArrUSer->id;
                }
            }
            $CartDetail  = CartDetail::where('non_login_token',$requestData['non_login_token'])
                                     ->where('product_id',$requestData['product_id'])
                                     ->where('is_offer',CartDetail::$IS_OFFER_NO)
                                     ->first();
            if($CartDetail) {
                $requestData['quantity'] = $requestData['quantity']+$CartDetail->quantity;
                if(isset($requestData['quantity']) && $requestData['quantity'] > 0) {
                    $ArrProduct = Product::_GetProductByID($requestData['product_id']);
                    if($ArrProduct) {
                        $unity_price            = $ArrProduct->unity_price;
                        $requestData['price']   = $unity_price*$requestData['quantity'];
                    }
                    $requestData['status'] = 1;
                    $CartDetail->update($requestData);
                    $cartdata   = CartDetail::with('product')->where('id',$CartDetail->id)->first();
                    $cartdata->product->isAvailableInCart = 1;
                    $cartdata->product->quantity = $cartdata->quantity;
                    CartDetail::_AddRemoveOfferItemsInCart($CartDetail->id,$requestData);
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = __('words.cart_update');
                    $data           = new CartResource($cartdata);
                } else {
                    $StatusCode     = 403;
                    $status         = 0;
                    $msg            = "Something wrong. Please try again.";
                }
            } else {
                $ArrProduct = Product::_GetProductByID($requestData['product_id']);
                if($ArrProduct) {
                    $unity_price            = $ArrProduct->unity_price;
                    $requestData['price']   = $unity_price*$requestData['quantity'];
                }
                $CartDetail = CartDetail::create($requestData);
                if($CartDetail) {
                    CartDetail::_AddRemoveOfferItemsInCart($CartDetail->id,$requestData);
                    $cartdata   = CartDetail::with('product')->where('id',$CartDetail->id)->first();
                    $cartdata->product->isAvailableInCart = 1;
                    $cartdata->product->quantity = $cartdata->quantity;
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = __('words.cart_added');
                    $data           = new CartResource($cartdata);
                } else {
                    $StatusCode     = 403;
                    $status         = 0;
                    $msg            = "Something wrong. Please try again.";
                }
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
        $msg            = __('words.no_data_available');
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            // 'id' => 'required|numeric',
            // 'user_id' => 'required|numeric',
            'non_login_token' => 'required',
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
            $CartDetail  = CartDetail::where('non_login_token',$requestData['non_login_token'])
                                     ->where('product_id',$requestData['product_id'])
                                     ->where('is_offer',CartDetail::$IS_OFFER_NO)
                                     ->first();
            if($CartDetail) {
                if(isset($requestData['quantity']) && $requestData['quantity'] > 0) {
                    $ArrProduct = Product::_GetProductByID($requestData['product_id']);
                    if($ArrProduct) {
                        $unity_price            = $ArrProduct->unity_price;
                        $requestData['price']   = $unity_price*$requestData['quantity'];
                    }
                    $requestData['status'] = 1;
                    $CartDetail->update($requestData);
                    CartDetail::_AddRemoveOfferItemsInCart($CartDetail->id,$requestData);
                    $cartdata   = CartDetail::with('product')->where('id',$CartDetail->id)->first();
                    $cartdata->product->isAvailableInCart = 1;
                    $cartdata->product->quantity = $cartdata->quantity;
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = __('words.cart_update');
                    $data           = new CartResource($cartdata);
                } else {
                    if($CartDetail->delete()) {
                        $StatusCode     = 200;
                        $status         = 1;
                        $msg            = __('words.cart_delete');
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


    public function generatetimeslot()
    {
        $intervelHour = 2;
        $MainstartTime  = date("07:00:00");
        $startTime      = date("07:00")." AM";
        $ArrTimeSlot = array();
        $i=1;
        while (true) {
          $EndTime  =  date('h:i A',strtotime("$intervelHour hour",strtotime($startTime)));
          $MainEndTime =  date('h:i:s',strtotime("$intervelHour hour",strtotime($startTime)));
          $hour24 =  date('H',strtotime($startTime));
          $hour24 =  intval(date('H',strtotime($startTime)));
          $ArrTimeSlot[$hour24] = $startTime." - ".$EndTime;
          $startTime = $EndTime;
          $i++;
          if($MainstartTime == $MainEndTime) {
            break;
          }
        }
        // echo json_encode($ArrTimeSlot);
        $ORDER_TIME_SLOT_FILE   = env('ORDER_TIME_SLOT_FILE');
        $JsonFile               = storage_path($ORDER_TIME_SLOT_FILE);
        $myfile                 = fopen($JsonFile, "w");
        $txt                    = json_encode($ArrTimeSlot);
        fwrite($myfile, $txt);
        fclose($myfile);
        exit();
    }

    public function cartitemcount(Request $request) {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $RegisterData = Validator::make($request->all(), [ 'non_login_token' => 'required', ]);
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
            $non_login_token = $request->get('non_login_token');
            if(!empty($non_login_token)) {
                $gst_charge         = 0;
                $delivery_charge    = 0;
                $gst_charge         = (int)Config::GetConfigurationList(Config::$GST_CHARGE);
                $delivery_charge    = (int)Config::GetConfigurationList(Config::$DELIVERY_CHARGE);
                $cartdata           = CartDetail::where('non_login_token',$non_login_token)->count();
                $status         = 1;
                $StatusCode     = 200;
                $msg            = __('words.retrieved_successfully');
                $data['cartcount'] = $cartdata;
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

}
