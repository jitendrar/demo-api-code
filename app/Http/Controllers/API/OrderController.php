<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Product;
use App\OrderDetail;
use App\Order;
use App\User;
use App\WalletHistory;
use App\Config;
use App\Address;
use App\OfferDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\CartResource;
use App\Http\Resources\WalletHistoryResource;
use App\Http\Resources\OrderDetailResource;
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
    public function show(Order $order)
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        if($order) {
            $status         = 1;
            $StatusCode     = 200;
            $msg            = __('words.retrieved_successfully');
            $data      = new OrderResource($order);
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy()
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
            $PAGINATION_VALUE = env('PAGINATION_VALUE');
            $user_id    = $request->get('user_id');
            if(!empty($user_id)) {
                $Orderdata = Order::with('address', 'orderDetail.product')
                                    ->where('user_id',$user_id)
                                    ->orderBy('orders.id', 'desc')
                                    ->paginate($PAGINATION_VALUE);
                // $Address    = Address::_GetPrimaryAddressByUserID($user_id);
                if($Orderdata->count()) {
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = __('words.retrieved_successfully');
                    // $data['address'] = $Address;
                    // $data['payment_method']     = "Wallete";
                    // $data              = OrderResource::collection($Orderdata);
                    // foreach ($Orderdata as $K => $V) {
                    //     $Orderdata[$K]->delivery_date = date("Y-m-d", strtotime($V->delivery_date));
                    //     $Orderdata[$K]->order_status = _GetOrderStatus($V->order_status);
                    // }
                    // $data              = $Orderdata;
                    $data              = new OrderCollection($Orderdata);
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = __('words.no_data_available');
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
            'address_id' => 'required|numeric|not_in:0',
            // 'special_information' => 'required',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
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
            $delivery_date          = date("Y-m-d",strtotime("1 days"));
            $user_id                = $request->get('user_id');
            $address_id             = $request->get('address_id');
            $special_information    = $request->get('special_information');
            $delivery_date          = $request->get('delivery_date');
            $delivery_time          = $request->get('delivery_time');
            $payment_method         = $request->get('payment_method');
            $delivery_charge        = 0;
            $delivery_charge        = Config::GetConfigurationList(Config::$DELIVERY_CHARGE);
            $delivery_tax           = 0;
            if(!empty($user_id)) {
                $ArrUser = User::find($user_id);
                if($ArrUser) {
                    $cartdata       = CartDetail::where('user_id',$user_id)->get();
                    if($cartdata->count()) {
                        $totalOrderPrice = 0;
                        foreach ($cartdata as $key => $value) {
                            $totalOrderPrice = $totalOrderPrice+$value->price;
                        }
                        $totalOrderPrice = $totalOrderPrice+$delivery_charge;
                        $AvailableBalance = $ArrUser->balance-$totalOrderPrice;
                        $ArrOrder = array();
                        $ArrOrder['user_id']                = $user_id;
                        $ArrOrder['address_id']             = $address_id;
                        $ArrOrder['delivery_charge']        = $delivery_charge;
                        $ArrOrder['delivery_date']          = $delivery_date;
                        $ArrOrder['delivery_time']          = $delivery_time;
                        $ArrOrder['special_information']    = $special_information;
                        $ArrOrder['order_status']           = Order::$ORDER_STATUS_PENDING;
                        $ArrOrder['total_price']            = $totalOrderPrice;
                        $ArrOrder['payment_method']         = $payment_method;
                        $OrderCreate = Order::create($ArrOrder);
                        if($OrderCreate) {
                            $EmailData = array();
                            
                            $order_id       = $OrderCreate->id;
                            $order_number   = "ORD".$order_id;
                            Order::where('id',$order_id)->update(['order_number' => $order_number]);
                            $status         = 1;
                            $StatusCode     = 200;
                            $msg            = __('words.order_placed');
                            foreach ($cartdata as $key => $value) {
                                $arrOrderDetails = array();
                                $arrOrderDetails['order_id']    = $order_id;
                                $arrOrderDetails['product_id']  = $value->product_id;
                                $arrOrderDetails['quantity']    = $value->quantity;
                                $arrOrderDetails['price']       = $value->price;
                                $arrOrderDetails['discount']    = $value->discount;
                                $arrOrderDetails['is_offer']    = $value->is_offer;
                                if(OrderDetail::create($arrOrderDetails)){
                                    CartDetail::destroy($value->id);
                                }
                            }
                            $ArrWallete = array();
                            $ArrWallete['user_id']              = $user_id;
                            $ArrWallete['order_id']             = $order_id;
                            $ArrWallete['user_balance']         = $AvailableBalance;
                            $ArrWallete['transaction_amount']   = $totalOrderPrice;
                            $ArrWallete['transaction_type']= WalletHistory::$TRANSACTION_TYPE_DEBIT;
                            $ArrWallete['remark'] = "Deduct money for your order #".$order_number;
                            WalletHistory::create($ArrWallete);
                            User::where('id',$ArrUser->id)->update(['balance' => $AvailableBalance]);
                            $Orderdata  = Order::with('orderDetail')->where('id',$order_id)->get();
                            $data       = $Orderdata;
                            Address::where('user_id',"=",$user_id)->update(['is_select' => 0]);
                            $OtpMsg = "New Order Created On BopalDaily,";
                            $OtpMsg.="\r\nUser ID :: ".$user_id;
                            $OtpMsg.="\r\nUser Name :: ".$ArrUser->first_name.' '.$ArrUser->last_name;
                            $OtpMsg.="\r\nOrder ID :: ".$order_id;
                            $OtpMsg.="\r\nOrder Price :: ".$totalOrderPrice;
                            $OtpMsg = urlencode($OtpMsg);
                            SendSMSForAdmin($OtpMsg);
                            // $EmailData['order_id']  = $order_id;
                            // $EmailData['user_id']   = $user_id;
                            // $EmailData['phone']     = $ArrUser->phone;
                            // $EmailData['first_name'] = $ArrUser->first_name;
                            // $EmailData['last_name']  = $ArrUser->last_name;
                            // $EmailData['totalOrderPrice']  = $totalOrderPrice;
                            // $content = ['content' => $EmailData];
                            // EmailSendForAdmin('admin.emails.new_order_created', 'New Order Created On BopalDaily', $content);
                        }
                        // if($totalOrderPrice <= $ArrUser->balance) {
                        // } else {
                        //     $StatusCode     = 409;
                        //     $status         = 0;
                        //     $msg            = "Insufficient Wallete Balance";
                        // }
                    } else {
                        $StatusCode     = 204;
                        $status         = 0;
                        $msg            = __('words.no_cart_in_order_placed');
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
        $msg            = __('words.no_data_available');
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
                    $PAGINATION_VALUE = env('PAGINATION_VALUE');
                    $walletdata       = WalletHistory::where('user_id',$user_id)
                                                ->orderBy('wallet_history.id', 'desc')
                                                ->paginate($PAGINATION_VALUE);
                    if($walletdata->count()) {
                        $StatusCode     = 200;
                        $status         = 1;
                        $msg            = __('words.retrieved_successfully');
                        foreach ($walletdata as $K => $V) {
                            $walletdata[$K]   = new WalletHistoryResource($V);
                        }
                        $data           = $walletdata;
                        // $data           = WalletHistoryResource::collection($walletdata);
                    }
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = __('words.user_not_found');
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
                    $msg            = __('words.retrieved_successfully');
                    $data['my_balance']       = $ArrUser->balance;
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = __('words.user_not_found');
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

    public function listoftimeslot(Request $request)
    {
        $StatusCode     = 200;
        $status         = 1;
        $msg            = __('words.retrieved_successfully');

        $ORDER_TIME_SLOT_FILE   = env('ORDER_TIME_SLOT_FILE');
        $JsonFile               = storage_path($ORDER_TIME_SLOT_FILE);
        $fileContent            = file_get_contents($JsonFile);
        $data                   = json_decode($fileContent,true);

        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }


    public function repeatorder(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'id' => 'required|numeric',
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
            $order_id    = $request->get('id');
            if(!empty($order_id)) {
                $Orderdata = Order::with('orderDetail')->where('id',$order_id)->first();
                if($Orderdata) {
                    $OrderdataArr = $Orderdata->toArray();
                    $user_id = $Orderdata['user_id'];
                    $ArrUser = User::find($user_id);
                    foreach ($OrderdataArr['order_detail'] as $K => $V) {
                        if(Product::_CheckProductIsActve($V['product_id'])) {
                            if($V['is_offer'] == 1) {
                                if(!OfferDetail::_CheckProductIsInOfferOrNot($V['product_id'])) {
                                    continue;
                                }
                            }
                            $arrOrderDetails = array();
                            $arrOrderDetails['user_id']             = $Orderdata['user_id'];
                            $arrOrderDetails['non_login_token']     = $ArrUser->non_login_token;
                            $arrOrderDetails['product_id']          = $V['product_id'];
                            $arrOrderDetails['quantity']            = $V['quantity'];
                            $arrOrderDetails['price']               = $V['price'];
                            $arrOrderDetails['is_offer']            = $V['is_offer'];
                            CartDetail::_AddUpdateCartItems($arrOrderDetails);
                        }
                    }
                    $status         = 1;
                    $StatusCode     = 200;
                    $msg            = __('words.repeat_order');
                    $data           = array();
                } else {
                    $StatusCode     = 204;
                    $status         = 0;
                    $msg            = __('words.no_data_available');
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }
}
