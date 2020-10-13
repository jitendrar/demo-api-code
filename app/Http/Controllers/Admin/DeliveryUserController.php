<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\AdminAction;
use App\ActivityLogs;
use App\User;
use App\DeliveryMaster;
use App\Order;
use Validator;
use DataTables;

class DeliveryUserController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();
        $this->moduleRouteText = "delivery-users";
        $this->moduleViewName = "admin.delivery_users";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Delivery User';
        $this->module = $module;

        $this->modelObj = new DeliveryMaster();

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
        $data['module_title'] ='Delivery User'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['users'] = DeliveryMaster::getDeliveryUsers();
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
        $avatar_id = $request->file('avatar_id');
        if($avatar_id){
            $imgSize = $avatar_id->getSize();
            if($imgSize > 4000000 || $imgSize == 0){
                $msg = 'The image may not be greater than 4 MB';
                return ['status' => 0, 'msg' => $msg, 'data' => $data];
            }
        }

        $validationArr =    [
                                'first_name' => 'required',
                                'last_name' => 'required',
                                'phone' => 'required|max:10',
                                'avatar_id' => 'required',
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
            $phone = $request->get('phone');
            $status_val = $request->get('status');
            $model = $this->modelObj;
            $model->first_name = $first_name;
            $model->last_name   = $last_name;
            $model->phone = $phone;
            $model->status = $status_val;
            $model->save();
            if(!empty($avatar_id))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'delivery_users'.DIRECTORY_SEPARATOR.$model->id;
                $image_name =$avatar_id->getClientOriginalName();
                $extension =$avatar_id->getClientOriginalExtension();
                $image_name=md5($image_name);
                $product_image= $image_name.'.'.$extension;
                $file =$avatar_id->move($destinationPath,$product_image);
                $model->picture = '/uploads/delivery_users/'.$model->id.'/'.$product_image;
                $model->save();
            }
                /* store log */
                $params=array();
                $params['activity_type_id'] = $this->activityAction->ADD_DELIVERY_USER;
                $params['user_id']  = $authUser->id;
                $params['action_id']  = $this->activityAction->ADD_DELIVERY_USER;
                $params['remark']   = 'Add Delivery User';
                ActivityLogs::storeActivityLog($params);
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
        $data["deliveryUserImg"] = DeliveryMaster::getAttachment($userObj->id);
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
        $data["deliveryUserImg"] = DeliveryMaster::getAttachment($formObj->id);
        $data["isEdit"] = 1;
        return view($this->moduleViewName.'.add', $data);
    }

    public function update(Request $request, $id)
    {
        $authUser = \Auth::guard('admins')->user();
        $model = $this->modelObj->find($id);
        $status = 1;
        $msg = $this->updateMsg;
        $data = array();
        if(!$model)
        {
            $status = 0;
            $msg = 'Record not found!';
        }
        $requestData = $request->all();
         
        $avatar_id = $request->file('avatar_id');
        if($avatar_id){
            $imgSize = $avatar_id->getSize();
            if($imgSize > 4000000 || $imgSize == 0){
                $msg = 'The image may not be greater than 4 MB';
                return ['status' => 0, 'msg' => $msg, 'data' => $data];
            }
        }

        $validationArr =    [
                                'first_name' => 'required',
                                'last_name' => 'required',
                                'phone' =>'required',
                                'avatar_id' => 'image|max:4000',
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
            $phone = $request->get('phone');
            $avatar_id = $request->file('avatar_id');
            $status_val = $request->get('status');
            $model->first_name = $first_name;
            $model->last_name = $last_name;
            $model->phone = $phone;
            $model->status = $status_val;
            $model->save();
            if(!empty($avatar_id))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'delivery_users'.DIRECTORY_SEPARATOR.$model->id;
                $image_name =$avatar_id->getClientOriginalName();
                $extension =$avatar_id->getClientOriginalExtension();
                $image_name=md5($image_name);
                $product_image= $image_name.'.'.$extension;
                $file =$avatar_id->move($destinationPath,$product_image);
                $model->picture = '/uploads/delivery_users/'.$model->id.'/'.$product_image;
                $model->save();
            }
                /* store log */
                $params=array();
                $params['activity_type_id'] = $this->activityAction->EDIT_DELIVERY_USER;
                $params['user_id']  = $authUser->id;
                $params['action_id']  = $this->activityAction->EDIT_DELIVERY_USER;
                $params['remark']   = 'Edit Delivery User';
                ActivityLogs::storeActivityLog($params);
        }
          return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function destroy($id)
    {
        //
    }

    public function data(Request $request)
    {
        $modelObj = Auth::guard('admins')->user();
        $model = DeliveryMaster::query();
        $model = $model->orderBy('delivery_master.created_at','desc');
        return DataTables::eloquent($model)
        ->editColumn('picture', function ($row) {
            $deliveryUserImg = DeliveryMaster::getAttachment($row->id); 
            if(isset($row->id) && $row->id != 0)
            {
               return '<img src="'.$deliveryUserImg.'" border="2" width="50" height="50" class="img-rounded thumbnail zoom" align="center" />';
            }else{
                return '<img src="{{ asset("images/coming_soon.png")}}" border="0" width="40" class="img-rounded thumbnail zoom" align="center" />';
            }
        })
        ->editColumn('status', function($row) {
                if($row->status == 1)
                    return '<a class="btn btn-xs btn-success">Active</a>';                
                else
                    return '<a class="btn btn-xs btn-danger">Inactive</a>';
            })
        ->editColumn('action', function($row) {
            return view("admin.delivery_users.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>1,
                    'isDelete' =>0,
                    'isView' =>1
                ]
            )->render();
        })->rawcolumns(['action','status','picture'])
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
                        $query = $query->whereIn("delivery_master.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_fnm))
                {
                    $query = $query->where("delivery_master.id", 'LIKE', '%'.$search_fnm.'%');
                    $searchData['search_fnm'] = $search_fnm;
                }
                if(!empty($search_pno))
                {
                    $query = $query->where("delivery_master.phone", 'LIKE', '%'.$search_pno.'%');
                    $searchData['search_pno'] = $search_pno;
                }
                if($search_status == "1" || $search_status == "0" )
                {
                    $query = $query->where("delivery_master.status", $search_status);
                }
                    $searchData['search_status'] = $search_status;
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
}
