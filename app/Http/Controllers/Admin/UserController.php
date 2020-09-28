<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Order;
use App\Address;
use App\WalletHistory;
use Validator;
use DataTables;

class UserController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "users";
        $this->moduleViewName = "admin.users";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Add User';
        $this->module = $module;

        $this->modelObj = new User();

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
        $data['module_title'] ='User'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['users'] = User::getUserList();
        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
        $authUser = Auth::guard('admins')->user();
        $data = array();
        $data['formObj'] = $this->modelObj;
        $data['module_title'] = $this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "<i class='fa fa-check'></i>Add";
        $data["method"] = "POST";
        $data["authUser"] = $authUser;
        $data["address"] = '';
        $data["isEdit"] = 0;

        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request)
    {

        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        $authUser = \Auth::guard('admins')->user();
        
        $requestData = $request->all();

        $validationArr =    [
                                'first_name' => 'required',
                                'last_name' => 'required',
                                'phone' => 'required|max:10',
                                'balance' => 'required',
                                'password' => 'required',
                                'status' => 'required',
                            ];
        $validator = Validator::make($requestData,$validationArr);
        // check validations
        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }
        else
        {
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $address_line_1 = trim($request->get('address_line_1'));
            $address_line_2 = trim($request->get('address_line_2'));
            $city = trim($request->get('city'));
            $zipcode = trim($request->get('zipcode'));
            $address_status = trim($request->get('address_status'));
            $prim_address = trim($request->get('prim_address'));
            $phone = $request->get('phone');
            $balance = $request->get('balance');
            $password = $request->get('password');
            $status_val = $request->get('status');
            $note = $request->get('note');
            $model = $this->modelObj;
            $model->first_name = $first_name;
            $model->last_name   = $last_name;
            $model->phone = $phone;
            $model->balance = $balance;
            $model->password = $password;
            $model->status = $status_val;
            $model->save();
            $obj = new Address();
            $obj->primary_address = 1;
            $obj->address_line_1 = $address_line_1;
            $obj->address_line_2 = $address_line_2;
            $obj->city = $city;
            $obj->zipcode = $zipcode;
            $obj->primary_address = $prim_address;
            $obj->status = $address_status;
            $obj->user_id =$model->id;
            $obj->save();
        }
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function show($id)
    {
        $authUser= Auth::guard('admins')->user();
        $data = array();
        $userObj = $this->modelObj->find($id);
        if(!$userObj)
        {
            return abort(404);
        }
        $data['user'] = $userObj;
        $data["address"] = Address::where('user_id',$userObj->id)->first();
        return view($this->moduleViewName.'.show', $data);
    }

    public function edit($id)
    {
        $authUser = Auth::guard('admins')->user();
        $formObj = $this->modelObj->find($id);

        if(!$formObj)
        {
            return abort(404);
        }

        $data = array();
        $data['formObj'] = $formObj;
        $data['module_title'] ='edit'.$this->module;
        $data['buttonText'] = "<i class='fa fa-check'></i> Update";
        $data['action_url'] = $this->moduleRouteText.".update";
        $data['action_params'] = $formObj->id;
        $data['method'] = "PUT";
        $data["authUser"] = $authUser;
        $data['address'] = Address::where('user_id',$formObj->id)->first();
        $data["isEdit"] = 1;
        return view($this->moduleViewName.'.add', $data);
    }

    public function update(Request $request, $id)
    {
        $model = $this->modelObj->find($id);
        $status = 1;
        $msg = $this->updateMsg;
        $data = array();
        if(!$model)
        {
            $status = 0;
            $msg = 'Record not found!';
        }
        $rulesArr = array();
        $requestData = $request->all();
        
        $validationArr =    [
                                'first_name' => 'required',
                                'last_name' => 'required',
                                'phone' => 'required|max:10',
                                'balance' => 'required',
                                'status' => 'required',
                            ];
        $validator = Validator::make($requestData,$validationArr);
        // check validations
        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }
        else
        {
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $address_line_1 = trim($request->get('address_line_1'));
            $address_line_2 = trim($request->get('address_line_2'));
            $city = trim($request->get('city'));
            $zipcode = trim($request->get('zipcode'));
            $address_status = trim($request->get('address_status'));
            $prim_address = trim($request->get('prim_address'));
            $phone = $request->get('phone');
            $balance = $request->get('balance');
            $addBalance = $request->get('add_balance');
            $password = $request->get('password');
            $status_val = $request->get('status');
            $note = $request->get('note');
            $model->first_name = $first_name;
            $model->last_name   = $last_name;
            $model->phone = $phone;
            $model->balance = $balance;
            if($addBalance){
                $model->balance = $balance + $addBalance;
            }
            if($password){
                $model->password = $password;
            }
            $model->status = $status_val;
            $model->save();
            $obj = Address::where('user_id',$model->id)->first();
            if($obj){
                $obj->address_line_1 = $address_line_1;
                $obj->address_line_2 = $address_line_2;
                $obj->city = $city;
                $obj->zipcode = $zipcode;
                $obj->status = $address_status;
                $obj->primary_address = $prim_address;
                $obj->save();
            }else{
                $obj = new Address();
                $obj->address_line_1 = $address_line_1;
                $obj->address_line_2 = $address_line_2;
                $obj->city = $city;
                $obj->zipcode = $zipcode;
                $obj->status = $address_status;
                $obj->primary_address = $prim_address;
                $obj->user_id = $model->id;
                $obj->save();
            }
        }
          return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function destroy(Request $request,$id)
    {
        $modelObj = $this->modelObj->find($id);
        if($modelObj) 
        {
            try 
            {
                $backUrl = $request->server('HTTP_REFERER');
                $modelObj->delete();
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
    public function data(Request $request)
    {
        $modelObj = Auth::guard('admins')->user();
        $model = User::query();
        $model = $model->orderBy('users.created_at','desc');
        return DataTables::eloquent($model)
        ->editColumn('status', function($row) {
                if($row->status == 1)
                    return '<a class="btn btn-xs btn-success">Active</a>';                
                else
                    return '<a class="btn btn-xs btn-danger">Inactive</a>';
            })
        ->editColumn('action', function($row) {
            return view("admin.users.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>1,
                    'isDelete' =>0,
                    'isView' =>1,
                    'ispay' =>1,
                    'isshowhistory' => 1,
                ]
            )->render();
        })->rawcolumns(['action','status'])
           ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");                                         
                $search_fnm = request()->get("search_fnm");                                         
                $search_pno = request()->get("search_pno");                                         
                $search_status = request()->get("search_status");
                $searchData = array();

                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("users.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_fnm))
                {
                    $query = $query->where("users.id", 'LIKE', '%'.$search_fnm.'%');
                    $searchData['search_fnm'] = $search_fnm;
                }
                if(!empty($search_pno))
                {
                    $query = $query->where("users.phone", 'LIKE', '%'.$search_pno.'%');
                    $searchData['search_pno'] = $search_pno;
                }
                if($search_status == "1" || $search_status == "0" )
                {
                    $query = $query->where("users.status", $search_status);
                }
                    $searchData['search_status'] = $search_status;
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
    public function addMoney(Request $request,$id){
        $model = $this->modelObj->find($id);
        $status = 1;
        $msg ='Add Money successfully';
        $data = array();
        if(!$model)
        {
            $status = 0;
            $msg = 'Record not found!';
        }
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) 
        {
            $msg = "";
            $messages = $validator->messages();   
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
            return ['status' => 0, 'msg' => $msg, 'data' => $data];
        }
        else
        {
            $amount = $request->get('amount');
            $description = trim($request->get('description'));
            $model->balance +=$amount;
            $model->save();
            $obj = new WalletHistory();
            $obj->order_id = -1; 
            $obj->user_id = $model->id;
            $obj->user_balance = $model->balance;
            $obj->transaction_amount = $amount;
            $obj->transaction_type = WalletHistory::$TRANSACTION_TYPE_CREDIT;;
            $obj->remark = $description;
            $obj->save();
        }
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function wallethistory(Request $request,$id){
        $data = array();
        $msg = '';
        $html = '';
        $status = 1;
        $wallethistory = WalletHistory::where('user_id',$id)->orderBy('created_at','desc')->get();
        if(!$wallethistory)
        {
            return ['status' => 0, 'msg'=>$msg, 'html'=>$html];
        }
        $data['wallethistory'] = $wallethistory;
        $html =  view($this->moduleViewName.'.wallet_history', $data)->render();
        return ['status' => $status, 'msg'=>$msg, 'html'=>$html];
    }
}
