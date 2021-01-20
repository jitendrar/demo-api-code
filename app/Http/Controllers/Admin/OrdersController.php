<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Order;
use App\DeliveryMaster;
use App\AdminAction;
use App\ActivityLogs;
use App\WalletHistory;
use App\ProductTranslation;
use Validator;
use App\User;
use App\Category;
use App\Product;
use App\Address;
use App\OrderDetail;
use App\Config;

class OrdersController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();
        $this->moduleRouteText = "orders";
        $this->moduleViewName = "admin.orders";
        $this->list_url = route($this->moduleRouteText.".index");

        $ORDER_TIME_SLOT_FILE   = env('ORDER_TIME_SLOT_FILE');
        $JsonFile               = storage_path($ORDER_TIME_SLOT_FILE);
        $fileContent            = file_get_contents($JsonFile);
        $this->Delivery_Timeslot = json_decode($fileContent,true);

        $module = 'Order';
        $this->module = $module;

        $this->modelObj = new Order();

        $this->addMsg = $module ."has been added successfully!";
        $this->updateMsg = $module ." has been updated successfully!";
        $this->deleteMsg = $module ." has been deleted successfully!";
        $this->deleteErrorMsg = $module . " can not deleted!";

        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);
    }
    public function index()
    {
        $data = array();
        $data['module_title'] ='Orders'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['users'] = User::getUserList();
        $data['deliveryUsers'] = DeliveryMaster::getActiveDeliveryUsers();
        $data['allDeliveryUser'] = DeliveryMaster::getDeliveryUsers();
        $data['categories'] = Category::categoryList();
        $data['products'] = Product::productList();
        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
        $data = array();
        $data['user_add_url'] = route('users.create');
        $data['userAddBtnName'] = "Add User";
        $data['formObj'] = $this->modelObj;
        $data['module_title'] = $this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "<i class='fa fa-check'></i>Create";
        $data["method"] = "POST";
        $data["address"] = '';
        $data["isEdit"] = 0;
        $data['users']      = User::getUserList();
        $data['categories'] = Category::categoryList();
        // $data['products']   = Product::productList();
        $delivery_charge         = 0;
        $delivery_charge         = Config::GetConfigurationList(Config::$DELIVERY_CHARGE);
        $data['delivery_charge'] = $delivery_charge;

        $data['timeslot']       = $this->Delivery_Timeslot;
        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request)
    {
        $status = 1;
        $msg    = $this->addMsg;
        $data   = array();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'address_id' => 'required|numeric',
            'product' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message){
                $msg .= $message . "<br />";
            }
        } else {
            $authUser = Auth::guard('admins')->user();
            $request_data   = $request->all();
            $ArrProductIDs = array();
            foreach ($request_data['product'] as $K => $Pid) {
                $P_quantity = $request_data['quantity'][$K];
                if(isset($ArrProductIDs[$Pid])) {
                    $request_data['quantity'][$ArrProductIDs[$Pid]] = $request_data['quantity'][$ArrProductIDs[$Pid]]+$P_quantity;
                    unset($request_data['product'][$K]);
                    unset($request_data['quantity'][$K]);
                } else {
                    $ArrProductIDs[$Pid] = $K;
                }
            }
            $user_id        = $request_data['user_id'];
            $ArrUser = User::find($user_id);
            if($ArrUser) {
                $request_data['delivery_date'] = date("Y-m-d", strtotime($request_data['delivery_date']));
                $request_data['delivery_time'] = isset($this->Delivery_Timeslot[$request_data['delivery_time']])?$this->Delivery_Timeslot[$request_data['delivery_time']]:"";
                
                if(isset($request_data['product']) && !empty($request_data['product'])) {
                    $totalOrderPrice        = 0;
                    // $delivery_charge        = Config::GetConfigurationList(Config::$DELIVERY_CHARGE);
                    $delivery_charge        = $request_data['delivery_charge'];
                    $special_information    = '';
                    $payment_method         = '';
                    $totalOrderPrice        = $totalOrderPrice+$delivery_charge;
                    $user_id                = $request_data['user_id'];
                    $ArrOrder = array();
                    $ArrOrder['user_id']                = $user_id;
                    $ArrOrder['address_id']             = $request_data['address_id'];
                    $ArrOrder['delivery_charge']        = $delivery_charge;
                    $ArrOrder['delivery_date']          = $request_data['delivery_date'];
                    $ArrOrder['delivery_time']          = $request_data['delivery_time'];
                    $ArrOrder['special_information']    = $special_information;
                    $ArrOrder['order_status']           = Order::$ORDER_STATUS_PENDING;
                    $ArrOrder['total_price']            = $totalOrderPrice;
                    $ArrOrder['payment_method']         = $payment_method;
                    $OrderCreate = Order::create($ArrOrder);
                    if($OrderCreate) {
                        $order_id       = $OrderCreate->id;
                        foreach ($request_data['product'] as $K => $Pid) {
                            $P_quantity = $request_data['quantity'][$K];
                            $ProductD   = Product::_GetProductByID($Pid);
                            if($ProductD) {
                                $P_price         = $ProductD->unity_price*$P_quantity;
                                $totalOrderPrice = $totalOrderPrice+$P_price;

                                $arrOrderDetails = array();
                                $arrOrderDetails['order_id']    = $order_id;
                                $arrOrderDetails['product_id']  = $Pid;
                                $arrOrderDetails['quantity']    = $P_quantity;
                                $arrOrderDetails['price']       = $P_price;
                                OrderDetail::create($arrOrderDetails);
                            }
                        }
                        $AvailableBalance = $ArrUser->balance-$totalOrderPrice;
                        $order_number   = "ORD".$order_id;
                        Order::where('id',$order_id)->update(['order_number' => $order_number, 'total_price' => $totalOrderPrice]);
                        $ArrWallete = array();
                        $ArrWallete['user_id']              = $user_id;
                        $ArrWallete['order_id']             = $order_id;
                        $ArrWallete['user_balance']         = $AvailableBalance;
                        $ArrWallete['transaction_amount']   = $totalOrderPrice;
                        $ArrWallete['transaction_type']     = WalletHistory::$TRANSACTION_TYPE_DEBIT;
                        $ArrWallete['remark']               = "Deduct money for your order";
                        WalletHistory::create($ArrWallete);
                        User::where('id',$ArrUser->id)->update(['balance' => $AvailableBalance]);
                        $params=array();
                        $params['user_id']          = $authUser->id;
                        $params['action_id']        = $this->activityAction->ADD_ORDER;
                        $params['remark']           = 'Create new order for User ID :: '.$ArrUser->id.' , Order ID :: '.$order_id;
                        ActivityLogs::storeActivityLog($params);
                    }
                }
            }
        }
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function show($id)
    {
        $authUser= Auth::guard('admins')->user();
        $data = array();
        $orderModel = $this->modelObj->find($id);
        if(!$orderModel)
        {
            return abort(404);
        }
        $statusName = _GetOrderStatus($orderModel->order_status);
        $data['order'] = $orderModel;
        $data['user'] = User::find($orderModel->user_id);
        $data['statusName'] = $statusName;
        if(!empty($orderModel->address_id)){
            $data['address'] = Address::find($orderModel->address_id);
        }

        return view($this->moduleViewName.'.show', $data);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $modelObj = $this->modelObj->find($id);
        if($modelObj) 
        {
            $modelObj->order_status = 'delete';
            $modelObj->save();
            session()->flash('success_message', $this->deleteMsg);
            return redirect($this->list_url);
        }else 
        {
            session()->flash('error_message','Record Does Not Exists');
            return redirect($this->list_url);
        }
    }

    public function orderDetail($id){
        $data = array();
        $msg = '';
        $html = '';
        $status = 1;
        $order = Order::find($id);
        $orderDetail = orderDetail::where('order_id',$id)->get();
        $deliveryUser = DeliveryMaster::find($order->delivery_master_id);
        $totalPrice = OrderDetail::getProductTotalPrice($id);
        if(!$orderDetail)
        {
            return ['status' => 0, 'msg'=>$msg, 'html'=>$html];
        }
        $data["address"] = Address::where('id',$order->address_id)->first();
        $data['orderDetail'] = $orderDetail;
        $data['totalPrice'] = $totalPrice;
        $data['deliveryUser'] = $deliveryUser;
        $data['order'] = $order;
        $html =  view($this->moduleViewName.'.order_detail', $data)->render();
        return ['status' => $status, 'msg'=>$msg, 'html'=>$html];
    }

    public function changeOrderStatus(Request $request,$id){
        $user = Auth::guard('admins')->user();
        $inputStatusName = $request->status_name;
        $button_html = '';
        $data= '';
        if ($request->get('_token') != '') {

            $model = Order::find($id);
            if (!$model){
                return response()->json(['status' => "", 'message' => "Order not found!"]);
            } else {
                $status =  _GetOrderStatus($model->order_status);
                if($inputStatusName == "cancel"){
                     if ($status = "Pending"){
                        $data = 'cancel';
                        $model->order_status = 'C';
                        $model->save();
                        /* store log */
                        $params=array();
                        $params['user_id']  = $user->id;
                        $params['action_id']  = $this->activityAction->ORDER_STATUS;
                        $params['remark']   = 'Order Status Cancel successfully';
                        ActivityLogs::storeActivityLog($params);
                        return response()->json(['status' => true, 'message' => "Order status updated successfully.", 'html' => $button_html,'data' =>$data]);
                    }else{

                        return response()->json(['status' => true, 'message' => "Order status is not updated, please try again!", 'html' => $button_html]);
                    }
                }
                else if($inputStatusName == "delivered"){
                    if ($status = "Pending"){
                        $data = 'delivered';
                        $model->order_status = 'D';
                        $model->delivery_date = date('Y-m-d');
                        $model->delivery_time = date('H:i:s');
                        $model->actual_delivery_date = date('Y-m-d');
                        $model->actual_delivery_time = date('Y-m-d H:i:s');
                        $model->save();
                        /* store log */
                        $params=array();
                        $params['user_id']  = $user->id;
                        $params['action_id']  = $this->activityAction->ORDER_STATUS;
                        $params['remark']   = 'Order Status Delivered successfully';
                        ActivityLogs::storeActivityLog($params);
                        return response()->json(['status' => true, 'message' => "Order status updated successfully.", 'html' => $button_html,'data' => $data]);
                    }else{

                        return response()->json(['status' => true, 'message' => "Order status is not updated, please try again!", 'html' => $button_html]);
                    }
                }
            }
        } else
            return response()->json(['status' => false, 'message' => "Something went wrong, Please try again later!"]);
    }

    public function assignDeliveryBoy(Request $request,$id){
        $user = Auth::guard('admins')->user();
        $status = 1;
        $msg = "Delivery Boy has been assigned succussfully.";
        $redirectUrl = $this->list_url;

        $id = $request->get("id");
        $model = Order::find($id);
        if ($model) {
            $rules = [
                        'delivery_boy_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->messages();

                $status = 0;
                $msg = "";

                foreach ($messages->all() as $message) {
                    $msg .= $message . "<br />";
                }
            } else {
                $delivery_boy_id = $request->get("delivery_boy_id");
                $model->delivery_master_id = $delivery_boy_id;
                $model->save(); 
                 /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->ASSIGN_DELIVERY_USER;
                $params['remark']   = 'Assign Delivery Boy for  order '.$model->id.' successfully';
                ActivityLogs::storeActivityLog($params); 
            }
        }else {
                $status = 0;
                $msg = "Order not found!";
            }

            return ['status' => $status, 'msg' => $msg,'redirect_url' => $redirectUrl];                             

    }
    public function changeQtyData(Request $request){
        $status = 0;
        $totalPrice = 0;
        $total_price = 0;
        $totalDelPrice = 0;
        $data = 0;
        $message ='';
        $authUser = Auth::guard('admins')->user();
        $orderDetail = OrderDetail::find($request->id);
        $order = Order::find($request->order_id);
        $user = User::find($order->user_id);
        $wallethistory = WalletHistory::where('order_id',$order->id)->first();
        if($orderDetail)
        {
            $req_qtn = $request->qty;
            $req_date_type = $request->data_type;
             $req_qtn = ($req_date_type == 'dec')?$req_qtn-1:$req_qtn+1;

            if($req_qtn <= 0){
                $oldPrice = $orderDetail->price;
                $oldQty = $orderDetail->quantity;
                $orderDetail->delete();
                $totalPrice = OrderDetail::getProductTotalPrice($request->order_id);
                $totalPrice = $totalPrice;
                $totalDelPrice = OrderDetail::getOrderTotalPrice($request->order_id);
                $order->total_price = $totalDelPrice;
                $order->save();
                $wallethistory->transaction_amount = $totalDelPrice;
                $wallethistory->user_balance = $user->balance + $oldPrice;
                $wallethistory->save();
                $user->balance = $wallethistory->user_balance;
                $user->save();
                $total_price = $totalPrice;
                $totalDelPrice = $totalDelPrice;
                 //old order value
                $stroeData = array();
                $stroeData['id'] = isset($orderDetail->id)?$orderDetail->id:'';
                $stroeData['order_id'] = isset($orderDetail->order_id)?$orderDetail->order_id:'';
                $stroeData['product_id'] = isset($orderDetail->product_id)?$orderDetail->product_id:'';
                $stroeData['old_quantity'] = isset($oldQty)?$oldQty:'';
                $stroeData['old_price'] = isset($oldPrice)?$oldPrice:'';
                $stroeData['old_discount'] = isset($orderDetail->discount)?$orderDetail->discount:'';
                $stroeData['old_updated_at'] = isset($orderDetail->updated_at)?$orderDetail->updated_at:'';
                    /* store log */
                $params=array();
                $params['user_id']  = $authUser->id;
                $params['action_id']  = $this->activityAction->DELETE_ORDER_PRODUCT;
                $params['remark']   = 'Delete the Product of order '.$orderDetail->order_id;
                $params['data']   = json_encode($stroeData);
                ActivityLogs::storeActivityLog($params);
                $message ="Product has been deleted successfully";
                $status = 2;
            }else{
                $oldPrice = $orderDetail->price;
                $oldQuantity = $orderDetail->quantity;
                $unitprice = $orderDetail->price / $orderDetail->quantity;
                $totalPrice = OrderDetail::getProductTotalPrice($request->order_id);
                $totalPrice = $totalPrice - $orderDetail->price;
                $new_price = ($unitprice * $req_qtn);
                $orderDetail->quantity = $req_qtn;
                $orderDetail->price = $new_price;
                $orderDetail->save();
                $totalPrice = $totalPrice +  $new_price;
                $data= $new_price;
                $total_price =  $totalPrice;
                $totalDelPrice = OrderDetail::getOrderTotalPrice($request->order_id);
                $order->total_price = $totalDelPrice;
                $order->save();
                $wallethistory->transaction_amount = $totalDelPrice;
                if($req_date_type == 'dec'){
                     $diffPrice = $oldPrice - $new_price;
                     $wallethistory->user_balance = $user->balance + $diffPrice;
                }else{
                     $diffPrice = $new_price - $oldPrice;
                     $wallethistory->user_balance = $user->balance - $diffPrice;
                }
                $wallethistory->save();
                $user->balance = $wallethistory->user_balance;
                $user->save();

                $status = 1;
                $message = 'Quantity updated successfully';
                //old order value
                $stroeData = array();
                $stroeData['id'] = isset($orderDetail->id)?$orderDetail->id:'';
                $stroeData['order_id'] = isset($orderDetail->order_id)?$orderDetail->order_id:'';
                $stroeData['product_id'] = isset($orderDetail->product_id)?$orderDetail->product_id:'';
                $stroeData['old_quantity'] = isset($oldQuantity)?$oldQuantity:'';
                $stroeData['old_price'] = isset($oldPrice)?$oldPrice:'';
                $stroeData['old_discount'] = isset($orderDetail->discount)?$orderDetail->discount:'';
                $stroeData['old_updated_at'] = isset($orderDetail->updated_at)?$orderDetail->updated_at:'';
                //new order value
                $stroeData['new_quantity'] = isset($req_qtn)?$req_qtn:'';
                $stroeData['new_price'] = isset($new_price)?$new_price:'';
                $stroeData['new_discount'] = '-';
                $stroeData['new_updated_at'] = date('y-m-d h:i:s');
               
                /* store log */
                $params=array();
                $params['user_id']  = $authUser->id;
                $params['action_id']  = $this->activityAction->EDIT_ORDER;
                $params['remark']   = 'Edit the Order '.$request->order_id;
                $params['data']   = json_encode($stroeData);
                ActivityLogs::storeActivityLog($params);
            }
        }
            return ['status' => $status, 'message' => $message,'data' =>$data,'total_price' => $total_price,'req_qtn'=>$req_qtn,'price_del_charge' => $totalDelPrice];
       

    }
    public function deleteProduct(Request $request){
        $authUser = Auth::guard('admins')->user();
        $orderDetail = OrderDetail::find($request->id);
        $order = Order::find($orderDetail->order_id);
        $user = User::find($order->user_id);
        $wallethistory = WalletHistory::where('order_id',$order->id)->first();
        if($orderDetail) 
        {
            try 
            {
                $oldQty = $orderDetail->quantity;
                $oldPrice = $orderDetail->price;
                $orderDetail->delete();
                $totalPrice = OrderDetail::getProductTotalPrice($orderDetail->order_id);
                $totalDelPrice = OrderDetail::getOrderTotalPrice($orderDetail->order_id);
                $order->total_price = $totalDelPrice;
                $order->save(); 
                $wallethistory->transaction_amount = $totalDelPrice;
                $wallethistory->user_balance = $user->balance + $oldPrice;
                $wallethistory->save();
                $user->balance = $wallethistory->user_balance;
                $user->save();
                 //old order value
                $stroeData = array();
                $stroeData['id'] = isset($orderDetail->id)?$orderDetail->id:'';
                $stroeData['order_id'] = isset($orderDetail->order_id)?$orderDetail->order_id:'';
                $stroeData['product_id'] = isset($orderDetail->product_id)?$orderDetail->product_id:'';
                $stroeData['old_quantity'] = isset($oldQty)?$oldQty:'';
                $stroeData['old_price'] = isset($oldPrice)?$oldPrice:'';
                $stroeData['old_discount'] = isset($orderDetail->discount)?$orderDetail->discount:'';
                $stroeData['old_updated_at'] = isset($orderDetail->updated_at)?$orderDetail->updated_at:'';
                  /* store log */
                $params=array();
                $params['user_id']  = $authUser->id;
                $params['action_id']  = $this->activityAction->DELETE_ORDER_PRODUCT;
                $params['remark']   = 'Delete the Product of order '.$orderDetail->order_id;
                $params['data']   = json_encode($stroeData);
                ActivityLogs::storeActivityLog($params);
                $msg ="Product has been deleted successfully";
                //session()->flash('success_message', $msg);
                return ['status' => 1, 'msg' => $msg,'total_price' => $totalPrice,'price_del_charge' => $totalDelPrice];
            }
            catch (Exception $e) 
            {
                session()->flash('error_message', $this->deleteErrorMsg);
                return redirect()->back()->withInput();
            }
        } 
        else 
        {
           session()->flash('error_message','Record Does Not Exists');
            return redirect()->route('orders.index');
        }
    }

    public function addProduct(Request $request){
        $authUser = Auth::guard('admins')->user();
        $status = 1;
        $orderId = $request->get("id");
        $msg = "Add new product of order ".$orderId. "successfully.";
        $productId = $request->get("product_id");
        $quantity = $request->get('quantity');
        $product = Product::find($productId);
        $ProductDetail  =  ProductTranslation::where('product_id',$productId)->first();
        $model          = Order::find($orderId);
        $user           = User::find($model->user_id);
        $wallethistory  = WalletHistory::where('order_id',$orderId)->first();
        
        if ($model) {
            $rules = [
                        'product_id' => 'required',
                        'quantity' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->messages();

                $status = 0;
                $msg = "";

                foreach ($messages->all() as $message) {
                    $msg .= $message . "<br />";
                }
            } else {
                $orderDetail = New OrderDetail();
                $orderDetail->order_id = $orderId;
                $orderDetail->product_id = $productId;
                $orderDetail->quantity = $quantity;
                $new_quantity = $orderDetail->quantity;
                $orderDetail->price = $ProductDetail->unity_price * $quantity;
                $add_new_product_price =  $orderDetail->price;
                $orderDetail->save(); 
                $totalPrice = OrderDetail::getProductTotalPrice($orderId);
                $totalDelPrice = OrderDetail::getOrderTotalPrice($orderId);
                $model->total_price = $totalDelPrice;
                $model->save();
                $wallethistory->transaction_amount = $totalDelPrice;
                $wallethistory->user_balance = $user->balance - $add_new_product_price;
                $wallethistory->save();
                $user->balance = $wallethistory->user_balance;
                $user->save();
                  //old order value
                $stroeData = array();
                $stroeData['id'] = isset($orderDetail->id)?$orderDetail->id:'';
                $stroeData['order_id'] = isset($orderDetail->order_id)?$orderDetail->order_id:'';
                $stroeData['product_id'] = isset($orderDetail->product_id)?$orderDetail->product_id:'';
                $stroeData['old_quantity'] = isset($new_quantity)?$new_quantity:'';
                $stroeData['old_price'] = isset($add_new_product_price)?$add_new_product_price:'';
                $stroeData['old_discount'] = '';
                $stroeData['old_updated_at'] = isset($orderDetail->updated_at)?$orderDetail->updated_at:'';
                 /* store log */
                $params=array();
                $params['user_id']  = $authUser->id;
                $params['action_id']  = $this->activityAction->ADD_ORDER_PRODUCT;
                $params['remark']   = 'Add Product of '.$model->id.' successfully';
                $params['data']   = json_encode($stroeData);
                ActivityLogs::storeActivityLog($params); 
            }
        }else {
                $status = 0;
                $msg = "Order not found!";
            }

            return ['status' => $status, 'msg' => $msg,'total_price' => $totalPrice,'price_del_charge' => $totalDelPrice];                  

    }

    public function Data(Request $request)
    {
        $authUser = Auth::guard('admins')->user();
        $modal = Order::select('orders.*','users.phone','addresses.address_line_1',\DB::raw('CONCAT(delivery_master.first_name," ",delivery_master.last_name) as deliveryUser'),\DB::raw('CONCAT(users.first_name," ",users.last_name) as userName'),'delivery_master.picture as deliveryUserImage')
            ->leftJoin('users','orders.user_id','=','users.id')
            ->leftJoin('addresses','orders.address_id','=','addresses.id')
            ->leftJoin('delivery_master','delivery_master.id','=','orders.delivery_master_id')
            ->groupBy('orders.id');
        if($request->name == 'dashboard'){
            $modal = $modal->where('orders.order_status','=','P');
        }
        $modal = $modal->orderBy('orders.created_at','desc');
        return \DataTables::eloquent($modal)
        ->editColumn('delivery_date', function($row) {
            if(!empty($row->delivery_date))
                return date('Y-m-d h:i',strtotime($row->delivery_date));
            else
                return '';
        })
        ->editColumn('userName',function($row){
           return $row->userName.'<br/>'.$row->phone;
        })
        ->editColumn('deliveryUser',function($row){
            $deliveryUserImg = DeliveryMaster::getAttachment($row->delivery_master_id); 
            $path = url("admin/delivery-users/".$row->delivery_master_id);
            if(isset($row->id) && $row->id != 0)
            {
               return ''.$row->deliveryUser.'<a href="'.$path.'"><img src="'.$deliveryUserImg.'" border="2" width="50" height="50" class="img-rounded thumbnail zoom" align="center" /></a>';
            }else{
                return '<img src="{{ asset("images/coming_soon.png")}}" border="0" width="40" class="img-rounded thumbnail zoom" align="center" />'.$row->deliveryUser.'';
            }

        })
        ->editColumn('totalPrice',function($row){
            return number_format((OrderDetail::getOrderTotalPrice($row->id)),2);
        })
        ->editColumn('created_at', function($row) {
            if(!empty($row->created_at))
                return date('Y-m-d h:i',strtotime($row->created_at));
            else
                return '';
        })
        ->editColumn('order_status', function($row) {
            $crrSts = $row->order_status;
            $status = _GetOrderStatus($crrSts);
                if($status == 'Delivered') 
                    return '<span class="label label-sm label-success">Delivered</sapn>';
                else if($status == 'Pending') 
                    return '<span class="label label-sm label-info">Pending</sapn>';  
                else if($status == 'Cancel') 
                    return '<span class="label label-sm label-default">Cancel</sapn>';          
                else
                    return '';
        })
        ->editColumn('action', function($row) {
            $isStatus = ($row->order_status != 'D')?1:0;
            return view("admin.orders.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isDelete' =>0,
                    'isView' =>1,
                    'isProductDetail' => 1,
                    'isStatus' =>  $isStatus,
                ]
            )->render();
        })->rawcolumns(['created_at','delivery_date','totalPrice','order_status','action','userName','deliveryUser'])
        ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");                                         
                $search_fnm = request()->get("search_fnm"); 
                $search_pnm = request()->get("search_pnm");                                         
                $search_oid = request()->get("search_oid");                                         
                $search_status = request()->get("search_status");
                $search_delivery_user = request()->get("search_delivery_user");

                $searchData = array();


                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);      
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("orders.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_fnm))
                {
                    $query = $query->where("users.id", 'LIKE', '%'.$search_fnm.'%');
                    $searchData['search_fnm'] = $search_fnm;
                }    
                if(!empty($search_oid))
                {
                    $query = $query->where("orders.order_number", 'LIKE', '%'.$search_oid.'%');
                    $searchData['search_oid'] = $search_oid;
                } 
                if(!empty($search_delivery_user)){
                    $query = $query->where('orders.delivery_master_id','LIKE','%'.$search_delivery_user.'%');
                    $searchData['search_delivery_user'] = $search_delivery_user;
                }
                if($search_status == "P" || $search_status == "D" || $search_status == "C")
                {
                    $query = $query->where("orders.order_status", $search_status);
                    $searchData['search_status'] = $search_status;
                }
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    } 
}
