<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Product;
use App\OrderDetail;
use App\Order;
use App\User;
use App\WalletHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Validator;


class OrderController extends Controller
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
    public function update(Request $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        //
    }

    public function getorderbyuser(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
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
            $user_id    = $request->get('user_id');
            if(!empty($user_id)) {
                $Orderdata = Order::with('orderDetail')->where('user_id',$user_id)->paginate(2);
                if($Orderdata->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = 'Retrive successfully';
                    $data            = $Orderdata;
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = "No order found";
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

    public function createorder(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'address_id' => 'required|numeric',
            'special_information' => 'required',
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
            $user_id                = $request->get('user_id');
            $address_id             = $request->get('address_id');
            $special_information    = $request->get('special_information');
            if(!empty($user_id)) {
                $ArrUser = User::find($user_id);
                if($ArrUser) {
                    $cartdata       = CartDetail::where('user_id',$user_id)->get();
                    if($cartdata->count()) {
                        $totalOrderPrice = 0;
                        foreach ($cartdata as $key => $value) {
                            $totalOrderPrice = $totalOrderPrice+$value->price;
                        }
                        if($totalOrderPrice <= $ArrUser->balance) {
                            $AvailableBalance = $ArrUser->balance-$totalOrderPrice;
                            $ArrOrder = array();
                            $ArrOrder['user_id']                = $user_id;
                            $ArrOrder['address_id']             = $address_id;
                            $ArrOrder['delivery_charge']        = '25.55';
                            $ArrOrder['delivery_date']          = date("Y-m-d");
                            $ArrOrder['special_information']    = $special_information;
                            $ArrOrder['order_number']           = 1;
                            $ArrOrder['actual_delivery_date']   = date("Y-m-d");
                            $ArrOrder['order_status']           = Order::$ORDER_STATUS_PENDING;
                            $ArrOrder['total_price']            = $totalOrderPrice;
                            $OrderCreate = Order::create($ArrOrder);
                            if($OrderCreate) {
                                $order_id       = $OrderCreate->id;
                                $status         = 1;
                                $StatusCode     = 200;
                                $msg            = 'Order created successfully';
                                foreach ($cartdata as $key => $value) {
                                    $arrOrderDetails = array();
                                    $arrOrderDetails['order_id']    = $order_id;
                                    $arrOrderDetails['product_id']  = $value->product_id;
                                    $arrOrderDetails['quantity']    = $value->quantity;
                                    $arrOrderDetails['price']       = $value->price;
                                    $arrOrderDetails['discount']    = $value->discount;
                                    if(OrderDetail::create($arrOrderDetails)){
                                        CartDetail::destroy($value->id);
                                    }
                                }
                                $ArrWallete['user_id']              = $user_id;
                                $ArrWallete['user_balance']         = $AvailableBalance;
                                $ArrWallete['transaction_amount']   = $totalOrderPrice;
                                $ArrWallete['transaction_type']= WalletHistory::$TRANSACTION_TYPE_DEBIT;
                                $ArrWallete['remark']               = "Deduct money for your order";
                                WalletHistory::create($ArrWallete);
                                User::where('id',$ArrUser->id)->update(['balance' => $AvailableBalance]);
                                $ArrWallete = array();
                                $Orderdata  = Order::with('orderDetail')->where('id',$order_id)->get();
                                $data       = $Orderdata;
                            }
                        } else {
                            $StatusCode     = 409;
                            $status         = 0;
                            $msg            = "Insufficient Wallete Balance";
                        }
                    } else {
                        $StatusCode     = 204;
                        $status         = 0;
                        $msg            = "No cart item found for the create order";
                    }
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

    public function transactionwallethistory(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
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
            $user_id                = $request->get('user_id');
            if(!empty($user_id)) {
                $ArrUser = User::find($user_id);
                if($ArrUser) {
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = 'Retrieved successfully';
                    $data['my_balance']       = $ArrUser->balance;
                    $PAGINATION_VALUE = env('PAGINATION_VALUE');
                    $walletdata = WalletHistory::where('user_id',$user_id)->paginate($PAGINATION_VALUE);
                    if($walletdata->count()) {
                        $data       = $walletdata;
                    }
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = "No user found";
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

    public function mywalletbalance(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
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
            $user_id                = $request->get('user_id');
            if(!empty($user_id)) {
                $ArrUser = User::find($user_id);
                if($ArrUser) {
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = 'Retrieved successfully';
                    $data['my_balance']       = $ArrUser->balance;
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = "No user found";
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }
}
