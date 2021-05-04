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
        $data['module_title'] 	='Daily Profit Loss Report'; 
        $data['add_url'] 		= route($this->moduleRouteText.'.create');
        $data['addBtnName'] 	= $this->module;
        $data['btnAdd'] 		= 1;

    

        $total_billing_amount = Billing::sum('total');

        $total_collection_amount = WalletHistory::where('transaction_type','CR')->where('transaction_method','0')->sum(\DB::raw('IFNULL((transaction_amount),0)'));
        $total_pl_amount = $total_collection_amount -  $total_billing_amount;
        if($total_pl_amount>0){
            $total_profit_loss_amount = 'Profit :: '.$total_pl_amount;
        }else if($total_pl_amount<0){
             $total_profit_loss_amount = 'Loss :: '.abs($total_pl_amount);
        }
        $data['total_billing_amount'] = $total_billing_amount;
        $data['total_collection_amount'] = $total_collection_amount;
        $data['total_profit_loss_amount'] = $total_profit_loss_amount ;


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
            \DB::enableQueryLog();
        $modal = WalletHistory::from(function ($query) {
                $query->select(\DB::raw('DATE(wallet_history.created_at) as bill_date'),\DB::raw('IFNULL(SUM( wallet_history.transaction_amount),0) as collection_amount'))
                ->from('wallet_history')
                ->where(\DB::raw('wallet_history.transaction_type'), 'CR')
                ->whereIn(\DB::raw('wallet_history.transaction_method'), [0,2])
                ->groupby(\DB::raw('DATE(wallet_history.created_at)'));
                },'MainWH')->select('MainWH.*',
                \DB::raw('IFNULL(SUM(billings.total),0) as purchase_bill_amount'),
                \DB::raw('IFNULL(SUM(`wallet_history2`.`transaction_amount`),0) as refund_amount'),
                \DB::raw('MainWH.collection_amount - IFNULL(SUM(`wallet_history2`.`transaction_amount`),0) as total_amount'),
                \DB::raw('((MainWH.collection_amount - IFNULL(SUM(`wallet_history2`.`transaction_amount`),0)) - IFNULL(SUM(billings.total),0)) as profit_loss')
            )->leftJoin('wallet_history as wallet_history2', function($join)
            {
               $join->on(\DB::raw('DATE(wallet_history2.created_at)'), '=', \DB::raw('DATE(MainWH.bill_date)'));
               $join->where('wallet_history2.transaction_method','=','2');
            })
            ->leftJoin('billings as billings',\DB::raw('DATE(billings.bill_date)'),\DB::raw('DATE(MainWH.bill_date)'))
            ->groupby(\DB::raw('DATE(MainWH.bill_date)'))
            ->orderby(\DB::raw('DATE(MainWH.bill_date)'),'desc');


        
                        

        return \DataTables::eloquent($modal)
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
        ->editColumn('profit_loss', function($row) {
            if(!empty($row->profit_loss)){
               if($row->profit_loss>0){
                $total_profit_loss_amount = 'Profit :: '.$row->profit_loss;
            }else if($row->profit_loss<0){
                 $total_profit_loss_amount = 'Loss :: '.abs($row->profit_loss);
             }
             return $total_profit_loss_amount;
            }else{
                return '';
            }
        })
        ->filter(function ($query) 
            {
                $search_start_date  = request()->get("search_start_date");
                $search_end_date    = request()->get("search_end_date"); 
                $searchData = array();

                if(!empty($search_start_date)) {
                    $query = $query->where("MainWH.bill_date", '>=', $search_start_date);
                    $searchData['search_start_date'] = $search_start_date;
                }
                if(!empty($search_end_date)) {
                    $query = $query->where("MainWH.bill_date", '<=', $search_end_date);
                    $searchData['search_end_date'] = $search_end_date;
                }

                $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
}


