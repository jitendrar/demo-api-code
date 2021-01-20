<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\AdminAction;
use App\OfferMaster;
use App\OfferDetail;
use App\ActivityLogs;
use Validator;
use DataTables;

class OffersController extends Controller
{
    public function __construct() {
        $this->activityAction = new AdminAction();

        $this->moduleRouteText = "offers";
        $this->moduleViewName = "admin.offers";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = 'Offers';
        $this->module = $module;

        $this->modelObj = new OfferMaster();

        $this->addMsg = $module ."has been added successfully!";
        $this->updateMsg = $module ." has been updated successfully!";
        $this->deleteMsg = $module ." has been deleted successfully!";
        $this->deleteErrorMsg = $module . " can not deleted!";

        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['module_title'] ='Offers'; 
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['addBtnName'] = $this->module;
        $data['btnAdd'] = 1;
        $data['productes'] = Product::productList();

        return view($this->moduleViewName.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['formObj'] = $this->modelObj;
        $data['module_title'] = $this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "<i class='fa fa-check'></i>Add";
        $data["method"] = "POST";
        $data['products'] = Product::productList();
        $data["editOfferProduct"] = [];
        $data["isEdit"] = 0;

        return view($this->moduleViewName.'.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::guard('admins')->user();
        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        $requestData = $request->all();
        $offerPicture = $request->file('picture');
        if($offerPicture){
            $imgSize = $offerPicture->getSize();
            if($imgSize > 4000000 || $imgSize == 0){
                $msg = 'The image may not be greater than 4 MB';
                return ['status' => 0, 'msg' => $msg, 'data' => $data];
            }
        }
        $validationArr =    [
                                'product_id' => 'required|exists:products,id',
                                'quantity' =>'required|numeric',
                                'description' => 'required',
                                'picture' => 'required|image|max:4000',// max 4000kb
                                'offer_product_id.*' => 'required|exists:products,id',
                                'offer_quantity.*' => 'required|numeric',
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
            $offerProductIds = $request->get('offer_product_id');
            $offerQuantities = $request->get('offer_quantity');

            if(!empty($offerProductIds) && !empty($offerQuantities))
            {
                $model = $this->modelObj;
                $model->product_id =  $request->get('product_id');
                $model->quantity =  $request->get('quantity');
                $model->description =  $request->get('description');
                $model->status = 1;
                $model->save();

                if(!empty($offerPicture))
                {
                    $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'offers'.DIRECTORY_SEPARATOR.$model->id;
                    $image_name =$offerPicture->getClientOriginalName();
                    $extension =$offerPicture->getClientOriginalExtension();
                    $image_name=md5($image_name);
                    $product_image= $image_name.'.'.$extension;
                    $file =$offerPicture->move($destinationPath,$product_image);
                    $model->picture = '/uploads/offers/'.$model->id.'/'.$product_image;
                    $model->save();
                }

                if (is_array($offerProductIds) && is_array($offerQuantities)) {
                    foreach ($offerProductIds as $key => $value) {
                        $offerDetaliObj = new OfferDetail;

                        $offerDetaliObj->offer_master_id = $model->id;
                        $offerDetaliObj->product_id = $value;
                        $offerDetaliObj->quantity = isset($offerQuantities[$key])?$offerQuantities[$key]:0;

                        $offerDetaliObj->save();
                    }
                }
                /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->ADD_OFFER;
                $params['remark']   = 'Add offer, Offer ID :: '.$model->id;
                ActivityLogs::storeActivityLog($params);
            }
        }

        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formObj  = $this->modelObj->find($id);

        if(!$formObj)
        {
            return abort(404);
        }
        $data = array();
        $data['formObj'] = $formObj;
        $data['products'] = Product::productList();
        $data["editOfferProducts"] = OfferDetail::select('*')->where('offer_master_id',$id)->get();

        return view($this->moduleViewName.'.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formObj  = $this->modelObj->find($id);

        if(!$formObj)
        {
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
        $data['products'] = Product::productList();
        $data["editOfferProducts"] = OfferDetail::select('*')->where('offer_master_id',$id)->get();

        return view($this->moduleViewName.'.add', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = \Auth::guard('admins')->user();
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
        $offerPicture = $request->file('picture');
        if($offerPicture){
            $imgSize = $offerPicture->getSize();
            if($imgSize > 4000000 || $imgSize == 0){
                $msg = 'The image may not be greater than 4 MB';
                return ['status' => 0, 'msg' => $msg, 'data' => $data];
            }
        }
        $validationArr =    [
                                'product_id' => 'required|exists:products,id',
                                'quantity' =>'required|numeric',
                                'description' => 'required',
                                'picture' => 'image|max:4000',// max 4000kb
                                'offer_product_id.*' => 'required|exists:products,id',
                                'offer_quantity.*' => 'required|numeric',
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
            $offerProductIds = $request->get('offer_product_id');
            $offerQuantities = $request->get('offer_quantity');

            if(!empty($offerProductIds) && !empty($offerQuantities))
            {
                $model->product_id =  $request->get('product_id');
                $model->quantity =  $request->get('quantity');
                $model->description =  $request->get('description');
                $model->save();

                if(!empty($offerPicture))
                {
                    $oldPicture = public_path().$model->picture;
                    if(file_exists($oldPicture)){
                        @unlink($oldPicture);
                    }
                    
                    $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'offers'.DIRECTORY_SEPARATOR.$model->id;
                    $image_name =$offerPicture->getClientOriginalName();
                    $extension =$offerPicture->getClientOriginalExtension();
                    $image_name=md5($image_name);
                    $product_image= $image_name.'.'.$extension;
                    $file =$offerPicture->move($destinationPath,$product_image);
                    $model->picture = '/uploads/offers/'.$model->id.'/'.$product_image;
                    $model->save();
                }

                if (is_array($offerProductIds) && is_array($offerQuantities)) {
                    OfferDetail::where('offer_master_id',$id)->delete();
                    foreach ($offerProductIds as $key => $value) {
                        $offerDetaliObj = new OfferDetail;

                        $offerDetaliObj->offer_master_id = $model->id;
                        $offerDetaliObj->product_id = $value;
                        $offerDetaliObj->quantity = isset($offerQuantities[$key])?$offerQuantities[$key]:0;

                        $offerDetaliObj->save();
                    }
                }

                /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->EDIT_OFFER;
                $params['remark']   = 'Edit Offer, Offer ID :: '.$model->id;
                ActivityLogs::storeActivityLog($params);
            }
        }

        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
                OfferDetail::where('offer_master_id',$id)->delete();
                $modelObj->delete();

                 /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->DELETE_OFFER;
                $params['remark']   = 'Delete Offer, Offer ID :: '.$id;

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

    public function data(Request $request)
    {
        $model = OfferMaster::select('offer_masters.*','product_translations.product_name')
                    ->join('product_translations','offer_masters.product_id','=','product_translations.product_id')
                    ->groupBy('offer_masters.id');

        return DataTables::eloquent($model)
         ->editColumn('picture', function ($row) {
            $profileImg = OfferMaster::getAttachment($row->id);
            if(isset($row->id) && $row->id != 0)
            {
               return '<img src="'.$profileImg.'" border="2" width="50" height="50" class="img-rounded" align="center" />';
            }else{
                return '<img src="{{ asset("images/coming_soon.png")}}" border="0" width="40" class="img-rounded" align="center" />';
            }
        })
        ->editColumn('product_name',function($row){
            $products = Product::productList();
            return isset($products[$row->product_id])?$products[ $row->product_id]:'' ;
        })
        ->editColumn('status', function($row) {
                if($row->status == 1)
                    return '<a class="btn btn-xs btn-success">Active</a>';                
                else
                    return '<a class="btn btn-xs btn-danger">Inactive</a>';
            })
        ->editColumn('action', function($row) {
            return view("admin.offers.action",
                [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row, 
                    'isEdit' =>1,
                    'isDelete' =>1,
                    'isView' =>1,
                    'isStatus' => 1,
                ]
            )->render();
        })->rawcolumns(['picture','action','status'])
        ->filter(function ($query) 
            {
                $search_id = request()->get("search_id");                                         
                $search_product_name = request()->get("search_product_name");
                $search_status = request()->get("search_status");

                $searchData = array();


                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn("offer_masters.id",$idArr);
                        $searchData['search_id'] = $search_id;
                    } 
                } 
                if(!empty($search_product_name))
                {
                    $query = $query->where("product_translations.product_name", 'LIKE', '%'.$search_product_name.'%');
                    $searchData['search_product_name'] = $search_product_name;
                } 
                if($search_status == "1" || $search_status == "0" )
                {
                    $query = $query->where("offer_masters.status", $search_status);
                    $searchData['search_status'] = $search_status;
                }
                    $goto = \URL::route($this->moduleRouteText.'.index', $searchData);
                    \session()->put($this->moduleRouteText.'_goto',$goto);
            })
        ->make(true);
    }
    public function changeOfferStatus($id,$status){
        $user = \Auth::guard('admins')->user();
        $OfferMaster = OfferMaster::find($id);
        if(!$OfferMaster){
            return abort(404);
        }
        if($status == 1){
            $OfferMaster->status = 0;
            $OfferMaster->save();
            OfferMaster::find($id)->update(['status'=>0]);

                /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->EDIT_OFFER;
                $params['remark']   = 'Change offer status, offer ID :: '.$id.' - Inactive';

                ActivityLogs::storeActivityLog($params);
            session()->flash('success_message','status has been changed Inactive sucessfully');
        }else{
            $OfferMaster->status = 1;
            $OfferMaster->save();
            OfferMaster::find($id)->update(['status'=>1]);
            /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->EDIT_OFFER;
                $params['remark']   = 'Change offer status, offer ID :: '.$id.' - Active';

                ActivityLogs::storeActivityLog($params);

            session()->flash('success_message','status has been changed active sucessfully');
        }
        return redirect()->route('offers.index');
    }
}
