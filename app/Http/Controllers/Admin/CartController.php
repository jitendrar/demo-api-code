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

        $modal = $modal->orderBy('cart_details.created_at','desc');
        return \DataTables::eloquent($modal)
        ->editColumn('userName',function($row){
           return $row->userName.' ('.$row->balance.') '.'<br/>'.$row->phone;
        })
        ->editColumn('totalPrice',function($row){
            return number_format((CartDetail::getCartTotalPrice($row->user_id)),2);
        })
        ->editColumn('created_at', function($row) {
            if(!empty($row->created_at))
                return date('Y-m-d h:i',strtotime($row->created_at));
            else
                return '';
        })
        ->editColumn('action', function($row) {
            return view("admin.cart.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isDelete' =>0,
                    'isView' =>1,
                    'isProductDetail' => 1,
                ]
            )->render();
        })->rawcolumns(['created_at','totalPrice','action','userName'])
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

    

}
