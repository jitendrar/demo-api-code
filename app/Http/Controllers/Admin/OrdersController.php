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
use Validator;
use App\User;
use App\Address;
use App\OrderDetail;

class OrdersController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();
        $this->moduleRouteText = "orders";
        $this->moduleViewName = "admin.orders";
        $this->list_url = route($this->moduleRouteText.".index");

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

        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
        $data['order'] = $orderModel;
        $data['user'] = User::find($orderModel->user_id);
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
                        $params['activity_type_id'] = $this->activityAction->ORDER_STATUS;
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
                        $params['activity_type_id'] = $this->activityAction->ORDER_STATUS;
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
                $params['activity_type_id'] = $this->activityAction->ASSIGN_DELIVERY_USER;
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
    public function Data(Request $request)
    {
        $authUser = Auth::guard('admins')->user();
        $modal = Order::select('orders.*','users.first_name','addresses.address_line_1')
            ->leftJoin('users','orders.user_id','=','users.id')
            ->leftJoin('addresses','orders.address_id','=','addresses.id')
            ->groupBy('orders.id');
        $modal = $modal->orderBy('orders.created_at','desc');
        return \DataTables::eloquent($modal)
        ->editColumn('delivery_date', function($row) {
            if(!empty($row->delivery_date))
                return date('Y-m-d h:i',strtotime($row->delivery_date));
            else
                return '';
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
        })->rawcolumns(['created_at','delivery_date','totalPrice','order_status','action'])
        ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");                                         
                $search_fnm = request()->get("search_fnm"); 
                $search_pnm = request()->get("search_pnm");                                         
                $search_oid = request()->get("search_oid");                                         
                $search_status = request()->get("search_status");

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
                if($search_status == "P" || $search_status == "D")
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
