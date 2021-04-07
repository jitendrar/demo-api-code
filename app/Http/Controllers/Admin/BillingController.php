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


class BillingController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();
        $this->moduleRouteText = "billings";
        $this->moduleViewName = "admin.billings";
        $this->list_url = route($this->moduleRouteText.".index");
        $module = 'Billing';
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
        $data['module_title'] 	='Billings'; 
        $data['add_url'] 		= route($this->moduleRouteText.'.create');
        $data['addBtnName'] 	= $this->module;
        $data['btnAdd'] 		= 1;
        $total_billing_amount = Billing::sum('total');
        $data['total_billing_amount'] = $total_billing_amount;

        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
        $data = array();
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['formObj'] = $this->modelObj;
        $data['module_title'] = $this->module;
        $data['buttonText'] = "<i class='fa fa-check'></i>Save";
        $data["method"] = "POST";
        $data["address"] = '';
        $data["isEdit"] = 0;
        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request) {
        $status = 1;
        $msg    = $this->addMsg;
        $data   = array();
        $validator = Validator::make($request->all(), [
            'total' 	=> 'required|numeric',
            'bill_date' => 'required',
            'description' => 'required',
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
            $picture 	= '';
            $model = $this->modelObj;
            $model->total 		= $request_data['total'];
            $model->bill_date 	= $request_data['bill_date'];
            $model->description = $request_data['description'];
            $model->picture 	= $picture;
            $model->save();
            if($request->file('picture')) {
            	$val = $request->file('picture');
            	$imgSize = $val->getSize();
            	if($imgSize > 10000000 || $imgSize == 0){
            		$msg = 'The image may not be greater than 10 MB';
            		return ['status' => 0, 'msg' => $msg, 'data' => $data];
            	}
            	$destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'bills';
            	if (!file_exists($destinationPath)) {
				    \File::makeDirectory($destinationPath, 0777, true, true);
				}
				$image_name =$val->getClientOriginalName();
                $extension =$val->getClientOriginalExtension();
                $image_name=md5($image_name);
                $product_image = $model->id.'_'.md5($request_data['bill_date']).'.'.$extension;
                $file =$val->move($destinationPath,$product_image);
            	$picture = '/uploads/bills/'.$product_image;
            	$model->picture 	= $picture;
            	$model->save();
            }
            $params=array();
            $params['user_id']          = $authUser->id;
            $params['action_id']        = $this->activityAction->CREATE_NEW_BILL_DETAILS;
            $params['remark']           = 'Added new bill details';
            ActivityLogs::storeActivityLog($params);
        }
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
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
        $user = \Auth::guard('admins')->user();
        $modelObj = $this->modelObj->find($id);

        if($modelObj) 
        {
            try 
            {
                $backUrl = Request()->server('HTTP_REFERER');
                $url = public_path().$modelObj->picture;
                if(file_exists($url)){
                    @unlink($url);
                }
                // Billing::where('id',$id)->delete();
                $modelObj->delete();

                 /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->DELETE_BILLS;
                $params['remark']   = 'Delete Billing, Bill ID :: '.$id;

                ActivityLogs::storeActivityLog($params);

                session()->flash('success_message', $this->deleteMsg);
                return redirect($this->list_url);
            }
            catch (Exception $e) 
            {
                session()->flash('error_message', $this->deleteErrorMsg);
                return redirect($this->list_url);
            }
        } 
        else 
        {
            session()->flash('error_message','Record Does Not Exists');
            return redirect($this->list_url);
        }
    }
    
    public function Data(Request $request)
    {
        $authUser = Auth::guard('admins')->user();
        $modal = Billing::select('billings.*');
        // $modal = $modal->orderBy('billings.bill_date','ASC');
        return \DataTables::eloquent($modal)
        ->editColumn('picture', function ($row) {
        	$profileImg = Billing::getAttachment($row->picture);
            return '<img src="'.$profileImg.'" border="2" width="50" height="50" class="img-rounded zoomimage" align="center" />';
        })
        ->editColumn('total',function($row){
            return number_format($row->total,2);
        })
        ->editColumn('created_at', function($row) {
            if(!empty($row->created_at))
                return date('Y-m-d h:i',strtotime($row->created_at));
            else
                return '';
        })
        ->editColumn('action', function($row) {
            return view("admin.billings.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isDelete' =>1,
                ]
            )->render();
        })->rawcolumns(['picture','action'])
        ->filter(function ($query) 
            {
                $search_start_date 	= request()->get("search_start_date");
                $search_end_date 	= request()->get("search_end_date"); 
                $searchData = array();

                if(!empty($search_start_date)) {
                    $query = $query->where("billings.bill_date", '>=', $search_start_date);
                    $searchData['search_start_date'] = $search_start_date;
                }
                if(!empty($search_end_date)) {
                    $query = $query->where("billings.bill_date", '<=', $search_end_date);
                    $searchData['search_end_date'] = $search_end_date;
                }

                $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
}
