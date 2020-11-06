<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminAction;
use Illuminate\Support\Facades\Auth;

use Validator;
use DataTables;


class AdminActionController extends Controller
{
    public function __construct(){
        $this->moduleRouteText = "admin-action";
        $this->moduleViewName = "admin.admin_action";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Admin Action';
        $this->module = $module;

        $this->modelObj = new AdminAction();

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
        $data['module_title'] ='Admin Action'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
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
        $data["isEdit"] = 0;

        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request)
    {
        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        
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
            $title = $request->get('title');
            $remark = $request->get('remark');

            $obj = $this->modelObj;
            $obj->title = $title;
            $obj->remark = $remark;
            $obj->save();

            session()->flash('success_message', $msg);
        }

        return ['status' => $status, 'msg' => $msg, 'data' => $data]; 
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
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
        $data["isEdit"] = 1;
        return view($this->moduleViewName.'.add', $data);
    }

    public function update(Request $request, $id)
    {
        $model = $this->modelObj->find($id);

        $status = 1;
        $msg = $this->updateMsg;
        $data = array();
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2|unique:'.TBL_ACTIVITY_TYPES.',title,'.$id,
        ]);
        // check validations
        if(!$model)
        {
            $status = 0;
            $msg = 'Record not found!';
        }
        else if ($validator->fails()) 
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
            $title = $request->get('title');
            $remark = $request->get('remark');
             
            $model->title = $title;
            $model->remark = $remark;
            $model->save();

            session()->flash('success_message', $msg);
        }
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function destroy($id)
    {
        //
    }
    public function data(Request $request){
        $model = AdminAction::query();
        
        return \DataTables::eloquent($model)

        ->editColumn('action', function($row) {
            return view("admin.partials.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>0,
                    'isView' => 0,
                    'isDelete' =>0,
                ]
            )->render();
        })
        ->rawcolumns(['action'])
        ->filter(function ($query)
        {
            $title = request()->get("title");
            if(!empty($title))
            {
                $query = $query->where('admin_action.title','LIKE','%'.$title.'%');
            }
        })
        ->make(true);
    }
}
