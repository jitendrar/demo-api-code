<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Billing;
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


class ReportController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();
        $this->moduleRouteText = "dailyreports";
        $this->moduleViewName = "admin.dailyreports";
        $this->list_url = route($this->moduleRouteText.".index");
        $module = 'Daily Profit Loss Report';
        $this->module = $module;
        $this->modelObj = new Billing();
        $this->addMsg = $module ."has been added successfully!";
        $this->updateMsg = $module ." has been updated successfully!";
        $this->deleteMsg = $module ." has been deleted successfully!";
        $this->deleteErrorMsg = $module . " can not deleted!";
        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);
    }

    public function index() {
        $data = array();
        $data['module_title'] 	='dailyreports'; 
        $data['add_url'] 		= route($this->moduleRouteText.'.create');
        $data['addBtnName'] 	= $this->module;
        $data['btnAdd'] 		= 1;
        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
    
    }

    public function store(Request $request) {
      }

    public function show($id) {
        //
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id)
    {
       
    }
    
    public function Data(Request $request)
    {
     

        $authUser = Auth::guard('admins')->user();
        $query = "SELECT MainWH.*, IFNULL(SUM(`wallet_history2`.`transaction_amount`),0) AS refund_amount,
        MainWH.collection_amount - IFNULL(SUM(`wallet_history2`.`transaction_amount`),0) AS `total_amount`
        FROM (
        SELECT DATE (`wallet_history`.`created_at`) AS bill_date,
        IFNULL(SUM(`wallet_history`.`transaction_amount`),0) AS collection_amount
        FROM `wallet_history`
        WHERE wallet_history.`transaction_type` = 'CR'
        AND `wallet_history`.`transaction_method` IN(0,2)
        GROUP BY DATE (`wallet_history`.`created_at`)
        ) AS MainWH
        LEFT JOIN `wallet_history` AS `wallet_history2` ON DATE (`wallet_history2`.`created_at`) = MainWH.bill_date AND `wallet_history2`.`transaction_method` = 2
        GROUP BY MainWH.bill_date
        ORDER BY MainWH.bill_date DESC";

        $modal = \DB::select($query);

        return \DataTables::of($modal)
        ->editColumn('refund_amount',function($row){
            return number_format($row->refund_amount,2);
        })
        ->editColumn('collection_amount',function($row){
            return number_format($row->collection_amount,2);
        })
        ->editColumn('total_amount',function($row){
            return number_format($row->total_amount,2);
        })
        ->editColumn('bill_date', function($row) {
            if(!empty($row->bill_date))
                return ($row->bill_date);
            else
                return '';
        })
        ->make(true);
    }
}


