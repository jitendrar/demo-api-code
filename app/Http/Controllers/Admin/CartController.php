<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
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
use App\CartDetail;
use App\Order;

class CartController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();
        $this->moduleRouteText = "cart";
        $this->moduleViewName = "admin.cart";
        $this->list_url = route($this->moduleRouteText.".index");

        $ORDER_TIME_SLOT_FILE   = env('ORDER_TIME_SLOT_FILE');
        $JsonFile               = storage_path($ORDER_TIME_SLOT_FILE);
        $fileContent            = '';
        $this->Delivery_Timeslot = json_decode($fileContent,true);

        $module = 'Cart';
        $this->module = $module;

        $this->modelObj = new CartDetail();
        $this->addMsg = "order has been placed successfully!";
        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);
    }
    public function index()
    {
        $data = array();
        $data['module_title'] ='Cart Data'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;

        $data['summary'] = route($this->moduleRouteText.'.summary');
        $data['summaryBtnName'] = "Pending Order Summary";

        $data['btnAdd'] = 1;
        $data['users'] = User::getUserList();
        $data['deliveryUsers'] = DeliveryMaster::getActiveDeliveryUsers();
        $data['allDeliveryUser'] = DeliveryMaster::getDeliveryUsers();
        $data['categories'] = Category::categoryList();
        $data['products'] = Product::productList();
        return view($this->moduleViewName.'.index', $data);
    }



    public function cartDetail($id){
        $data = array();
        $msg = '';
        $html = '';
        $status = 1;
        $order = CartDetail::where('user_id',$id)->get();
        $orderDetail = CartDetail::where('user_id',$id)->get();
        $totalPrice = CartDetail::getCartTotalPrice($id);
        if(!$orderDetail)
        {
            return ['status' => 0, 'msg'=>$msg, 'html'=>$html];
        }
        $data["address"] = Address::where('user_id',$id)->first();
        
        foreach ($orderDetail as $key => $value) {
          
            $units_in_stock_in_GM           = $value->product->units_in_stock;
            $details_units_in_stock_total   = $units_in_stock_in_GM*$value->quantity;
            $details_units_stock_type       = $value->product->units_stock_type;
            if(strtoupper($details_units_stock_type) == 'G') {
                if($details_units_in_stock_total >= 1000) {
                    $details_units_stock_type = 'KG';
                    $details_units_in_stock_total = $details_units_in_stock_total/1000;
                }
            }
            $orderDetail[$key]['details_units_stock_type']      = $details_units_stock_type;
            $orderDetail[$key]['details_units_in_stock_total']  = $details_units_in_stock_total;
            // if(strtoupper($value->product->units_stock_type) == 'KG') {
            // }
            // pr($orderDetail[$key]);
            // prd($value->quantity);
        }
        $data['orderDetail'] = $orderDetail;
        $data['totalPrice'] = $totalPrice;
        $data['order'] = $order;
        $html =  view($this->moduleViewName.'.order_detail', $data)->render();
        return ['status' => $status, 'msg'=>$msg, 'html'=>$html];
    }

   


    public function Data(Request $request)
    {

        $authUser = Auth::guard('admins')->user();
        $modal = CartDetail::select('cart_details.*','users.phone', 'users.balance','addresses.address_line_1',\DB::raw('CONCAT(users.first_name," ",users.last_name) as userName'))
            ->leftJoin('users','cart_details.user_id','=','users.id')
            ->leftJoin('addresses','users.id','=','addresses.user_id')
            ->where('cart_details.user_id','>',0)
            ->groupBy('cart_details.user_id');

        $modal = $modal->orderBy('cart_details.updated_at','desc');
        return \DataTables::eloquent($modal)
        ->editColumn('userName',function($row){
           return $row->userName.' ('.$row->balance.') '.'<br/>'.$row->phone;
        })
        ->editColumn('totalPrice',function($row){
            return number_format((CartDetail::getCartTotalPrice($row->user_id)),2);
        })
        ->editColumn('updated_at', function($row) {
            if(!empty($row->updated_at))
                return date('Y-m-d h:i',strtotime($row->updated_at));
            else
                return '';
        })
        ->editColumn('action', function($row) {
            return view("admin.cart.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isDelete' =>0,
                    'isAssignOrder'=>1,
                    'isView' =>1,
                    'isProductDetail' => 1,
                ]
            )->render();
        })->rawcolumns(['updated_at','totalPrice','action','userName'])
        ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");
                $search_fnm = request()->get("search_fnm"); 
                $searchData = array();


                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);      
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("cart_details.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_fnm))
                {
                    $query = $query->where("users.id", 'LIKE', '%'.$search_fnm.'%');
                    $searchData['search_fnm'] = $search_fnm;
                }    
          
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
    public function placeOrder(Request $request, $id='')
    {
      
        $status         = 1;
        $msg            = "";
        $data           = array();
        $RegisterData = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'id' => 'required|numeric',
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                 $status = 0;
                break;
            }
        } else {
            $delivery_date          = date("Y-m-d",strtotime("1 days"));
            $user_id                = $request->get('user_id');
            $address_id             = Address::select('id')->where('user_id',$user_id)->orderBy('primary_address','DESC')->first();
            if($address_id){

            $delivery_time          = '07:00 AM - 09:00 AM';
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
                        $ArrOrder['address_id']             = $address_id->id;
                        $ArrOrder['delivery_charge']        = $delivery_charge;
                        $ArrOrder['delivery_date']          = $delivery_date;
                        $ArrOrder['delivery_time']          = $delivery_time;
                        $ArrOrder['order_status']           = Order::$ORDER_STATUS_PENDING;
                        $ArrOrder['total_price']            = $totalOrderPrice;
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
                            $OtpMsg = "New Order Created On BopalDaily From Admin,";
                            $OtpMsg.="\r\nUser ID :: ".$user_id;
                            $OtpMsg.="\r\nUser Name :: ".$ArrUser->first_name.' '.$ArrUser->last_name;
                            $OtpMsg.="\r\nOrder ID :: ".$order_id;
                            $OtpMsg.="\r\nOrder Price :: ".$totalOrderPrice;
                            $OtpMsg.="\r\n";
                            $OtpMsg.="\r\nBopalDaily ";
                            $OtpMsg = urlencode($OtpMsg);
                            $TemplateIDBopalDailyNewOrder = env('TemplateIDBopalDailyNewOrder');
                            SendSMSForAdmin($OtpMsg, $TemplateIDBopalDailyNewOrder);
                           
                        }
                    } else {
                        $status         = 0;
                        $msg            = __('words.no_cart_in_order_placed');
                    }
                }else{
            $status         = 0;
            $msg            = __('words.user_not_available');
            }
            }else{
            $status         = 0;
            $msg            = __('words.user_not_available');
            }
        }else{
            $status         = 0;
            $msg            = __('words.no_address_available');
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);

    }

    

}
