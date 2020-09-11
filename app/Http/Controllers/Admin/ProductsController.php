<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Category;
use App\ProductsImages;
use DataTables;
use Validator;
use File;
use DB;

class ProductsController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "products";
        $this->moduleViewName = "admin.products";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Product';
        $this->module = $module;

        $this->modelObj = new Product();

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
        $data['module_title'] ='Products'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['categories'] = Category::pluck('category_name','id')->all();


        return view($this->moduleViewName.'.index', $data);
    }

    public function create()
    {
        $authUser = \Auth::user();
        $data = array();
        $categories = Category::where('status', 1)
            ->orderBy('category_name', 'asc')
            ->pluck('category_name', 'id')
            ->all();
        $data['formObj'] = $this->modelObj;
        $data['module_title'] = $this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "<i class='fa fa-check'></i>Add";
        $data["method"] = "POST";
        $data["authUser"] = $authUser;
        $data["categories"] = $categories;
        $data['images'] = '';
        $data["isEdit"] = 0;

        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request)
    {
        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        $authUser = \Auth::User();
        
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
                                'product_name' => 'required',
                                'description' => 'required',
                                'units_stock_type' => 'required',
                                'units_in_stock' => 'required',
                                'unity_price' => 'required',
                                'category_id' => 'required|exists:categories,id',
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
            $product_name = $request->get('product_name');
            $units_stock_type = $request->get('units_stock_type');
            $description = trim($request->get('description'));
            $units_in_stock = $request->get('units_in_stock');
            $category_id = $request->get('category_id');
            $unity_price = $request->get('unity_price');
            $avatar_id = $request->file('avatar_id');
            $status_val = $request->get('status');
            $model = $this->modelObj;
            $model->product_name = $product_name;
            $model->units_stock_type   = $units_stock_type;
            $model->description = $description;
            $model->units_in_stock = $units_in_stock;
            $model->category_id = $category_id;
            $model->unity_price = $unity_price;
            $model->status = $status_val;
            $model->save();
            if(!empty($avatar_id))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.$model->id;
                $image_name =$avatar_id->getClientOriginalName();
                $extension =$avatar_id->getClientOriginalExtension();
                $image_name=md5($image_name);
                $product_image= $image_name.'.'.$extension;
                $file =$avatar_id->move($destinationPath,$product_image);
                $model->picture = $product_image;
                $model->save();
            }
            $primary = $request->input('is_primary');
            if($request->file('multi_img')){
                foreach($request->file('multi_img',[]) as $tempId => $val){
                    $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.$model->id;
                    $image_name =$val->getClientOriginalName();
                    $extension =$val->getClientOriginalExtension();
                    $image_name=md5($image_name);
                    $product_image= $image_name.'.'.$extension;
                    $file =$val->move($destinationPath,$product_image);
                    $obj = new ProductsImages();
                    if($tempId == $primary){
                        $model->picture = $product_image;
                        $obj->product_id = $model->id;
                        $obj->src = $product_image;
                        $obj->is_primary = 1;
                        $obj->save();
                    }
                    else{
                        $obj->product_id = $model->id;
                        $obj->src = $product_image;
                        $obj->is_primary = 0;
                        $obj->save();
                    }
                    $model->save();
                    echo($tempId);
                }
            }
            dd($request->input('is_primary'));
        }
          return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function show($id)
    {
        $authUser= \Auth::user();
        $data = array();
        $Productmodel = $this->modelObj->find($id);
        if(!$Productmodel)
        {
            return abort(404);
        }
        $data["productImg"] = Product::getAttachment($Productmodel->id);
        $data['category'] = Category::getCategory($Productmodel->category_id);
        $data['product'] = $Productmodel;
        return view($this->moduleViewName.'.show', $data);
    }

    public function edit($id)
    {
        $authUser = \Auth::user();
        $formObj  = $this->modelObj->find($id);

        if(!$formObj)
        {
            return abort(404);
        }

        $category = Category::getCategory($formObj->category_id);
        $data = array();
        $data['formObj'] = $formObj;
        $data['module_title'] ='edit'.$this->module;
        $data['buttonText'] = " <i class='fa fa-check'></i> update";
        $data['action_url'] = $this->moduleRouteText.".update";
        $data['action_params'] = $formObj->id;
        $data['method'] = "PUT";
        $data["authUser"] = \Auth::user();
        $data["isEdit"] = 1;
        $data['categories'] = Category::pluck('category_name','id')->all();
        $data["productImg"] = ProductsImages::select('*')->where('product_id',$formObj->id)->get();
        //dd($data);
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
        $avatar_id = $request->file('avatar_id');
        if($avatar_id){
            $imgSize = $avatar_id->getSize();
            if($imgSize > 4000000 || $imgSize == 0){
                $msg = 'The image may not be greater than 4 MB';
                return ['status' => 0, 'msg' => $msg, 'data' => $data];
            }
        }

        $validationArr =    [
                                'product_name' => 'required',
                                'description' => 'required',
                                'units_stock_type' => 'required',
                                'units_in_stock' => 'required',
                                'unity_price' => 'required',
                                'category_id' => 'required|exists:categories,id',
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
            $product_name = $request->get('product_name');
            $units_stock_type = $request->get('units_stock_type');
            $description = trim($request->get('description'));
            $units_in_stock = $request->get('units_in_stock');
            $category_id = $request->get('category_id');
            $unity_price = $request->get('unity_price');
            $avatar_id = $request->file('avatar_id');
            $status_val = $request->get('status');
            $model->product_name = $product_name;
            $model->units_stock_type   = $units_stock_type;
            $model->description = $description;
            $model->units_in_stock = $units_in_stock;
            $model->category_id = $category_id;
            $model->unity_price = $unity_price;
            $model->status = $status_val;
            $model->save();
            if(!empty($avatar_id))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.$model->id;
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
                $url = public_path().'/uploads/products/$modelObj->id'.$modelObj->picture;
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
    public function deleteImage(Request $request,$id)
    {
        $modelObj = productsImages::where('product_id',$id)->first();
        if($modelObj) 
        {
            try 
            {
                $backUrl = $request->server('HTTP_REFERER');
                $url = public_path().'/uploads/products/'.$modelObj->product_id.'/'.$modelObj->src;
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
        $model = Product::select('products.*','categories.category_name as catName')
        ->leftJoin('categories','products.category_id','=','categories.id');
        return DataTables::eloquent($model)
         ->editColumn('picture', function ($row) {
            $profileImg = Product::getAttachment($row->id); 
            if(isset($row->id) && $row->id != 0)
            {
               return '<img src="'.$profileImg.'" border="2" width="50" height="50" class="img-rounded" align="center" />';
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
            return view("admin.products.action",
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
                $search_pnm = request()->get("search_pnm");                                         
                $search_ut = request()->get("search_ut");                                         
                $category = request()->get("category");                                         
                $search_status = request()->get("search_status");

                $searchData = array();


                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("products.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_pnm))
                {
                    $query = $query->where("products.product_name", 'LIKE', '%'.$search_pnm.'%');
                    $searchData['search_pnm'] = $search_pnm;
                } 
                if(!empty($search_ut))
                {
                    $query = $query->where("products.units_stock_type", 'LIKE', '%'.$search_ut.'%');
                    $searchData['search_ut'] = $search_ut;
                } 
                if(!empty($category))
                {
                    $query = $query->where("products.category_id", 'LIKE', '%'.$category.'%');
                    $searchData['category'] = $category;
                }
                if($search_status == "1" || $search_status == "0" )
                {
                    $query = $query->where("products.status", $search_status);
                    $searchData['search_status'] = $search_status;
                }
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
    public function storeMedia(Request $request)
    {
        $status = 1;
        $msg = "File uploaded successfully!";
        $returnID = 0;
        $path = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file = $request->file('file');     
        if($file)
        {
            $name = $file->getClientOriginalName();
            
            $tempName = md5(uniqid() . '_' . trim($file->getClientOriginalName()));
            $extension =$file->getClientOriginalExtension();
            $full_name= $tempName.'.'.$extension;
            $file->move($path, $full_name);
          
            $returnID = \DB::table("products_images")
            ->insertGetId([
                "id" => $returnID,
                "product_id" => $returnID,
                "src" => $full_name,
                "created_at" => date("Y-m-d H:i:s")
            ]);
        }
        else
        {
            $status = 0;
            $msg = "Please upload valid file.";
        }
        
        return 
        [
            "status" => $status,
            "msg" => $msg,
            "id" => $returnID
        ];
    }

    public function deleteMedia(Request $request)
    {
        dd(123);
        $status = 1;
        $msg = "File Deleted!";

        $tempId = $request->get('tempId'); 

        $tempData = DB::table('products_images')->where('id', $tempId)->get(); 
        $oldpath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products';         
        foreach ($tempData as $tempValue) {
            $tempFilename =  $tempValue->temp_name;            
            if(file_exists($oldpath.DIRECTORY_SEPARATOR.$tempFilename)) {
                unlink($oldpath.DIRECTORY_SEPARATOR.$tempFilename);              
            } 

        }
        
        DB::table('temp_attachment')->where('id', $tempId)->delete();

        return ['status' => $status, 'msg' => $msg];
    }

}
