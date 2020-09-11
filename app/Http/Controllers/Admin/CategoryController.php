<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use DataTables;
use Validator;

class CategoryController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "categories";
        $this->moduleViewName = "admin.categories";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Category';
        $this->module = $module;

        $this->modelObj = new Category();

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
        $data['module_title'] ='Category List'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;

        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
        $data = array();
        $data['formObj'] = $this->modelObj;
        $data['module_title'] = $this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "<i class='fa fa-check'></i>Add";
        $data["method"] = "POST";
        $data["isEdit"] = 0;

        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request)
    {
        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        
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
                                'category_name' => 'required',
                                'description' => 'required',
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
            $category_name = $request->get('category_name');
            $description = trim($request->get('description'));
            $avatar_id = $request->file('avatar_id');
            $status_val = $request->get('status');
            $model = $this->modelObj;
            $model->category_name = $category_name;
            $model->description = $description;
            $model->status = $status_val;
            $model->save();
            if(!empty($avatar_id))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$model->id;
                $image_name =$avatar_id->getClientOriginalName();
                $extension =$avatar_id->getClientOriginalExtension();
                $image_name=md5($image_name);
                $product_image= $image_name.'.'.$extension;
                $file =$avatar_id->move($destinationPath,$product_image);
                $model->picture = $product_image;
                $model->save();
            }
        }
          return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function show($id)
    {
        $authUser= \Auth::user();
        $data = array();
        $catObj = $this->modelObj->find($id);
        if(!$catObj)
        {
            return abort(404);
        }
        $data['category'] = $catObj;
        $data["catImg"] = Category::getAttachment($catObj->id);

        return view($this->moduleViewName.'.show', $data);
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
        $data['buttonText'] = " <i class='fa fa-check'></i> update";
        $data['action_url'] = $this->moduleRouteText.".update";
        $data['action_params'] = $formObj->id;
        $data['method'] = "PUT";
        $data["isEdit"] = 1;
        $data["catImg"] = Category::getAttachment($formObj->id);

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
                                'category_name' => 'required',
                                'description' => 'required',
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
            $category_name = $request->get('category_name');
            $description = trim($request->get('description'));
            $avatar_id = $request->file('avatar_id');
            $status_val = $request->get('status');
            $model->category_name = $category_name;
            $model->description = $description;
            $model->status = $status_val;
            $model->save();
            if(!empty($avatar_id))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'categories'.DIRECTORY_SEPARATOR.$model->id;
                $image_name =$avatar_id->getClientOriginalName();
                $extension =$avatar_id->getClientOriginalExtension();
                $image_name=md5($image_name);
                $product_image= $image_name.'.'.$extension;
                $file =$avatar_id->move($destinationPath,$product_image);
                $model->picture = $product_image;
                $model->save();
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
                $url = public_path().'/uploads/categories/$modelObj->id'.$modelObj->picture;
                if (file_exists($url)) {
                    @unlink($url);
                }
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
        $model = Category::query();
        return DataTables::eloquent($model)
         ->editColumn('picture', function ($row) {
            $catImg = Category::getAttachment($row->id); 
            if(isset($row->id) && $row->id != 0)
            {
               return '<img src="'.$catImg.'" border="2" width="50" height="50" class="img-rounded" align="center" />';
            }else{
                return '<img src="{{ asset("images/coming_soon.png")}}" border="0" width="40" class="img-rounded" align="center" />';
            }
        })
        ->editColumn('status', function($row) {
                if($row->status == 1)
                    return '<a class="btn btn-xs btn-success">Active</a>';                
                else
                    return '<a class="btn btn-xs btn-danger">Inactive</a>';
            })
        ->editColumn('action', function($row) {
            return view("admin.categories.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>1,
                    'isDelete' =>1,
                    'isView' =>1,
                ]
            )->render();
        })->rawcolumns(['picture','action','status'])
        ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");                                         
                $search_cnm = request()->get("search_cnm");                                         
                $search_status = request()->get("search_status");

                $searchData = array();


                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("categories.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_cnm))
                {
                    $query = $query->where("categories.category_name", 'LIKE', '%'.$search_cnm.'%');
                    $searchData['search_cnm'] = $search_cnm;
                }
                if($search_status == "1" || $search_status == "0" )
                {
                    $query = $query->where("categories.status", $search_status);
                }
                    $searchData['search_status'] = $search_status;
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
}
