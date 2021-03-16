<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Category;
use App\CategoryTranslation;
use App\Custom;
use App\ProductsImages;
use App\AdminAction;
use App\ActivityLogs;
use App\ProductMapping;
use DataTables;
use Validator;
use File;
use DB;

class ProductsController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();

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
        $data['categories'] = category::categoryList();

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
        $data["categories"] = category::categoryList();
        $data["defaultCategories"] = [];
        $data['languages'] = Custom::__masterLocals();
        $data['images'] = '';
        $data["isEdit"] = 0;

        return view($this->moduleViewName.'.add', $data);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('admins')->user();
        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        $requestData = $request->all();
        $validationArr =    [
                                'product_name.*' => 'required',
                                'description.*' =>'required',
                                'units_stock_type.*' => 'required',
                                'units_in_stock.*' => 'required',
                                'unity_price.*' => 'required',
                                'category_id' => 'required|exists:categories,id',
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
            $description = $request->get('description');
            $units_stock_type = $request->get('units_stock_type');
            $units_in_stock = $request->get('units_in_stock');
            $unity_price = $request->get('unity_price');
            $status_val = $request->get('status');
            $primary = $request->input('is_primary');
            $images = $request->file('multi_img');
            if(is_null($images)){
                $status = 0;
                $msg = "Please add one image!";
                return ['status' => $status, 'msg' => $msg, 'data' => $data];
            }
            if(is_null($primary)){
                $status = 0;
                $msg = "Please add one primary image!";
                return ['status' => $status, 'msg' => $msg, 'data' => $data];
            }
            $model = $this->modelObj;
            $model->status = $status_val;
            $model->save();
            $categories = $request->category_id;
             if (!empty($categories)) {
                foreach ($categories as $category) {
                    $row = [
                        'category_id' => $category,
                        'product_id' => $model->id,
                    ];
                    $model1 = new ProductMapping($row);
                    $model1->save();
                }
            }
            $languages= Custom::__masterLocals();

            foreach ($languages as $locale => $val)
            { 
                $obj = new \App\ProductTranslation();
                if(is_array($product_name) && !empty($product_name))
                {
                    $obj->product_name = $product_name[$val];
                }
                if(is_array($description) && !empty($description))
                {
                    $obj->description = $description[$val];
                } 
                if(is_array($units_stock_type) && !empty($units_stock_type))
                {
                    $obj->units_stock_type = $units_stock_type['en'];
                } 
                if(is_array($units_in_stock) && !empty($units_in_stock))
                {
                    $obj->units_in_stock = $units_in_stock['en'];
                }  
                if(is_array($unity_price) && !empty($unity_price))
                {
                    $obj->unity_price = $unity_price['en'];
                }
                $obj->locale = $val;
                $obj->product_id = $model->id;
                $obj->save();
            }
            if($request->file('multi_img')){
                    foreach($request->file('multi_img',[]) as $tempId => $val){
                        $imgSize = $val->getSize();
                        if($imgSize > 4000000 || $imgSize == 0){
                            $msg = 'The image may not be greater than 4 MB';
                            return ['status' => 0, 'msg' => $msg, 'data' => $data];
                        }
                        $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.$model->id;
                        $image_name =$val->getClientOriginalName();
                        $extension =$val->getClientOriginalExtension();
                        $image_name=md5($image_name);
                        $product_image= $image_name.'.'.$extension;
                        $file =$val->move($destinationPath,$product_image);
                        $obj = new ProductsImages();
                        if($tempId == $primary){
                            $model->picture = '/uploads/products/'.$model->id.'/'.$product_image;
                            $obj->product_id = $model->id;
                            $obj->src = '/uploads/products/'.$model->id.'/'.$product_image;
                            $obj->file_name = $product_image;
                            $obj->is_primary = 1;
                            $obj->save();
                        }
                        else{
                            $obj->product_id = $model->id;
                            $obj->src = '/uploads/products/'.$model->id.'/'.$product_image;
                            $obj->file_name = $product_image;
                            $obj->is_primary = 0;
                            $obj->save();
                        }
                        $model->save();
                    }
            }
               /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->ADD_PRODUCT;
                $params['remark']   = 'Add Product';
                ActivityLogs::storeActivityLog($params);
        }
          return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function show($id)
    {
        $data = array();
        $productmodel = $this->modelObj->find($id);
        $categories = ProductMapping::where('product_id',$productmodel->id)->get();
        if(!$productmodel)
        {
            return abort(404);
        }
        $data["primaryImg"] = Product::getAttachment($productmodel->id);
        $data["productImg"] = ProductsImages::getProductImages($productmodel->id);
        $data['category'] = Category::getCategories($productmodel->id);
        $data['product'] = $productmodel;
        return view($this->moduleViewName.'.show', $data);
    }

    public function edit($id)
    {
        $formObj  = $this->modelObj->find($id);
        if(!$formObj) {
            return abort(404);
        }
        $data = array();
        $data['formObj'] = $formObj;
        $data['module_title'] ='edit'.$this->module;
        $data['buttonText'] = " <i class='fa fa-check'></i> Update";
        $data['action_url'] = $this->moduleRouteText.".update";
        $data['action_params'] = $formObj->id;
        $data['method'] = "PUT";
        $data["isEdit"] = 1;
        $data['languages']= Custom::__masterLocals();
        $data['defaultCategories'] = category::getCategories($formObj->id);
        $data['categories'] = category::categoryList();
        $data["productImg"] = ProductsImages::select('*')->where('product_id',$formObj->id)->get();
        return view($this->moduleViewName.'.add', $data);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('admins')->user();
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
                                'product_name.*' => 'required',
                                'description.*' =>'required',
                                'units_stock_type.*' => 'required',
                                'units_in_stock.*' => 'required',
                                'unity_price.*' => 'required',
                                'category_id' => 'required|exists:categories,id',
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
            $description = $request->get('description');
            $units_in_stock = $request->get('units_in_stock');
            $category_id = $request->get('category_id');
            $unity_price = $request->get('unity_price');
            $avatar_id = $request->file('avatar_id');
            $status_val = $request->get('status');
            $primary = $request->input('is_primary');
            $categories = $request->category_id;
            if(is_null($primary)){
                $status = 0;
                $msg = "Please add one primary image!";
                return ['status' => $status, 'msg' => $msg, 'data' => $data];
            }
            //dd($primary);
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $row = [
                        'category_id' => $category,
                        'product_id' => $model->id,
                    ];
                    $model1 = ProductMapping::where($row)->first();
                    if (empty($model1)) {
                        $model1 = new ProductMapping($row);
                    } else {
                        $model1->fill($row);
                    }
                    $model1->save();
                }
            }
            ProductMapping::where(['product_id' => $model->id])->whereNotIn('category_id', $categories ?? [])->delete();

            $model->status = $status_val;
            $model->save();
            $primaryRm=productsImages::where('product_id',$model->id)->get();
                foreach($primaryRm as $primaryRmVal){
                    $primaryRmVal->is_primary = 0;
                    $primaryRmVal->save();
                }
            $image = productsImages::where('id',$primary)->first();
                if($image){
                    $image->is_primary = 1;
                    $image->save();
                    $model->picture = $image->src;
                    $model->save();
                } 
            $languages= Custom::__masterLocals();
            foreach ($languages as $locale => $val)
            {   
                $obj = \App\ProductTranslation::where('locale',$val)->where('product_id',$model->id)->first();
                if(is_array($product_name) && !empty($product_name))
                {
                    $obj->product_name = $product_name[$val];
                }
                if(is_array($description) && !empty($description))
                {
                    $obj->description = $description[$val];
                }
                if(is_array($units_stock_type) && !empty($units_stock_type))
                {
                    $obj->units_stock_type = $units_stock_type['en'];
                } 
                if(is_array($units_in_stock) && !empty($units_in_stock))
                {
                    $obj->units_in_stock = $units_in_stock['en'];
                }  
                if(is_array($unity_price) && !empty($unity_price))
                {
                    $obj->unity_price = $unity_price['en'];
                }
                $obj->save();
            }
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

                        $model->picture = '/uploads/products/'.$model->id.'/'.$product_image;
                        $obj->product_id = $model->id;
                        $obj->src = '/uploads/products/'.$model->id.'/'.$product_image;
                        $obj->file_name = $product_image;
                        $obj->is_primary = 1;
                        $obj->save();
                    }
                    else{
                        $obj->product_id = $model->id;
                        $obj->src ='/uploads/products/'.$model->id.'/'.$product_image;
                        $obj->file_name = $product_image;
                        $obj->is_primary = 0;
                        $obj->save();
                    }
                    $model->save();
                }

            }
            /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->EDIT_PRODUCT;
                $params['remark']   = 'Edit Product';
                ActivityLogs::storeActivityLog($params);
        }
          return ['status' => $status, 'msg' => $msg, 'data' => $data];

    }

    public function destroy(Request $request,$id)
    {
        $modelObj = $this->modelObj->find($id);
        $productImages = ProductsImages::where('product_id',$modelObj->id)->get();
        if($modelObj) 
        {
            try 
            {
                $backUrl = $request->server('HTTP_REFERER');
                foreach($productImages as $image){
                    $url = public_path().'/uploads/products/'.$modelObj->id.'/'.$image->file_name;
                    if(file_exists($url)){
                        @unlink($url);
                    }
                    $image->delete();
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
        $msg = 'Product Image deleted sucessfully';
        $modelObj = productsImages::where('id',$id)->first();
        if($modelObj) 
        {
            if($modelObj->is_primary == 1){
                session()->flash('error_message','Plz select another image as a primary than you can delete!');
                return redirect()->back()->withInput();
            }else{
                try 
                {
                    $backUrl = $request->server('HTTP_REFERER');
                    $url = public_path().'/uploads/products/'.$modelObj->product_id.'/'.$modelObj->src;
                    if (file_exists($url)) {
                        @unlink($url);
                    }
                    $modelObj->delete();
                    session()->flash('success_message', $msg);
                    return redirect()->back()->withInput();
                }
                catch (\Exception $e) 
                {
                    session()->flash('error_message', $this->deleteErrorMsg);
                    return redirect()->back()->withInput();
                }
            }
        } 
        else 
        {
            session()->flash('error_message','Record Does Not Exists');
            return redirect($this->list_url);
        }
    }

    public function changeStatus($id,$status){
        $product = Product::find($id);
        if(!$product){
            return abort(404);
        }
        if($status == 1){
            $product->status = 0;
            $product->save();
            Product::find($id)->update(['status'=>0]);
            session()->flash('success_message','status has been changed Inactive sucessfully');
        }else{
            $product->status = 1;
            $product->save();
            Product::find($id)->update(['status'=>1]);
            session()->flash('success_message','status has been changed active sucessfully');
        }
        return redirect()->route('products.index');
    }

    public function data(Request $request)
    {
        $model = Product::select('products.*','product_translations.product_id')
                ->join('product_translations','products.id','=','product_translations.product_id')
                ->join('product_mappings','product_mappings.product_id','=','product_translations.product_id')
                ->where('product_translations.locale','=','en')
                ->groupBy('products.id');
        $model = $model->orderBy('products.created_at','desc');
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
        ->editColumn('catName', function($row) {
                $cat = Category::getCategories($row->id);
                foreach ($cat as $cat) {
                    $category_name[] = $cat;
                }
                return $category_name;
            })
        ->editColumn('action', function($row) {
            return view("admin.products.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>1,
                    'isDelete' =>0,
                    'isView' =>1,
                    'isStatus' => 1,
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
                    $query = $query->where("product_translations.product_name", 'LIKE', '%'.$search_pnm.'%');
                    $searchData['search_pnm'] = $search_pnm;
                } 
                if(!empty($search_ut))
                {
                    $query = $query->where("product_translations.units_stock_type", 'LIKE', '%'.$search_ut.'%');
                    $searchData['search_ut'] = $search_ut;
                } 
                if(!empty($category))
                {
                    $query = $query->where("product_mappings.category_id", 'LIKE', '%'.$category.'%');
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

    public function getproductlist(Request $request) {
        
        $status = 0;
        $msg    = "No Products available";
        $data   = array();
        $category_id        = $request->get('id');
        if(!empty($category_id)) {
            $ArrProductID       = ProductMapping::_GetProductByCategoryID($category_id);
            if(!empty($ArrProductID)) {
                $status = 1;
                $msg    = "Products available";
                $data  = Product::productList($ArrProductID);
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        echo json_encode($ArrReturn);
        exit();
    }

    public function getproductdetails(Request $request) {
        
        $status = 0;
        $msg    = "No Products available";
        $data   = array();
        $product_id        = $request->get('id');
        if(!empty($product_id)) {
            // $ArrProduct = Product::find($product_id);
            $status = 1;
            $msg    = "Products available";
            $data   = Product::where('id', $product_id)->first();
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        echo json_encode($ArrReturn);
        exit();
    }

    public function sorting() {
        $data = array();
        $data['module_title'] ='Products Ordering'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['categories'] = category::categoryList();
        return view($this->moduleViewName.'.sorting', $data);
    }

    public function getsortdata(Request $request)
    {
        $model = Product::select('products.*','product_translations.product_id')
                ->join('product_translations','products.id','=','product_translations.product_id')
                ->join('product_mappings','product_mappings.product_id','=','product_translations.product_id')
                ->where('product_translations.locale','=','en')
                ->groupBy('products.id');
        
        $model = $model->orderBy('products.display_order','ASC');
        return DataTables::eloquent($model)
        //  ->orderColumn('name', function ($query, $order) {
        //     $query->orderBy('status', $order);\
        // })
        ->addColumn('display_order', function($row) {
            // return $row->display_order;
            return '<td class="pl-3"><i class="fa fa-sort"></i> '.$row->display_order.' </td>';
        })
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
        ->editColumn('catName', function($row) {
                $cat = Category::getCategories($row->id);
                foreach ($cat as $cat) {
                    $category_name[] = $cat;
                }
                return $category_name;
            })
        ->editColumn('action', function($row) {
            return view("admin.products.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>1,
                    'isDelete' =>0,
                    'isView' =>1,
                    'isStatus' => 1,
                ]
            )->render();
        })->rawcolumns(['display_order', 'picture','action','status'])
        ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");
                $search_pnm = request()->get("search_pnm");
                $search_ut = request()->get("search_ut");
                $category = request()->get("category");
                $search_status = request()->get("search_status");
                $searchData = array();
                if(!empty($search_id)){
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("products.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                }

                if(!empty($search_pnm)) {
                    $query = $query->where("product_translations.product_name", 'LIKE', '%'.$search_pnm.'%');
                    $searchData['search_pnm'] = $search_pnm;
                }

                if(!empty($search_ut)) {
                    $query = $query->where("product_translations.units_stock_type", 'LIKE', '%'.$search_ut.'%');
                    $searchData['search_ut'] = $search_ut;
                }

                if(!empty($category)) {
                    $query = $query->where("product_mappings.category_id", 'LIKE', '%'.$category.'%');
                    $searchData['category'] = $category;
                }

                if($search_status == "1" || $search_status == "0" ) {
                    $query = $query->where("products.status", $search_status);
                    $searchData['search_status'] = $search_status;
                }
                $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }

    public function sortingupdate(Request $request) {
        $Products = Product::all();
        foreach ($Products as $Product) {
            foreach ($request->productorderarr as $order) {
                if ($order['id'] == $Product->id) {
                    $Product->update(['display_order' => $order['position']]);
                }
            }
        }
        $status = 1;
        $msg = "Update Successfully!";
        return ['status' => $status, 'msg' => $msg];
        // return response()->json(['success'=>'Update Successfully']);
        // return response('Update Successfully.', 200);
    }

}
