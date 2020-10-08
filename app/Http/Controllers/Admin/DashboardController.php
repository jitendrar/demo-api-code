<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Validator;
use App\User;
use App\AdminUser;
use App\Product;
use App\Category;
use App\Order;
use App\OrderDetail;

class DashboardController extends Controller
{
    public function dashboard()
    {        
        $data = array();
        $authUser = Auth::guard('admins')->user();
        $data['authUser'] = $authUser;
        $data['total_User'] = User::count();
        $order = Order::query()->where('orders.order_status','=','P')->count();
        $data['total_pending_orders'] = $order;
        $data['total_orders'] = Order::count();
        $data['totalProducts'] = Product::where('status',1)->count();
        $data['totalCategories'] = Category::where('status',1)->count();
        $data['total_inactive_products'] = Product::where('status',0)->count();
        return view('admin.dashboard',$data);
    }
    public function myProfile()
    {
        $authUser = \Auth::guard('admins')->user();
        $formObj = AdminUser::find($authUser->id);
        if(!$formObj)
        {
            return abort(404);
        }

        $data = array();
        $data['module_title'] = 'My Profile';
        $data['formObj'] = $formObj;
        $data['title'] ='edit profile';
        $data['buttonText'] = 'Update';
        $data['action_url'] = route('admin-updateProfile');
        $data['action_params'] = $formObj->id;
        $data['method'] = "POST";
        $data["is_show_password"] = 1;
        $data["isEdit"] = 1;
        $data["redirectURL"] = route('admin-profile');
        $data["authUser"] = $authUser;
        return view('admin.profile', $data);
    }
    public function updateProfile(Request $request)
    {
        $authUser =\Auth::guard('admins')->user();
        $model = AdminUser::find($authUser->id);

        if(!$model)
        {
            $status = 0;
            $msg ='Record Not Found';
        }

        $status = 1;
        $msg = 'User Has been updated';
        $data = array();
        $validateArr = [];

        $form_type = $request->get('form_type');
        $password = $request->get('password');
        $old_password = $request->get('old_password');
        $password_confirmation = $request->get('password_confirmation');

        if($form_type == 'change-password')
        {
            $msg = 'Change Password';
            $validateArr = $validateArr + [
                'old_password' => 'required',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ];
        }
        else if($form_type == 'change-avatar')
        {
            $avatar_id = $request->file('avatar_id');
            if($avatar_id){
                $imgSize = $avatar_id->getSize();
                if($imgSize > 4000000 || $imgSize == 0){
                    $msg = 'The image may not be greater than 4 MB.';
                    return ['status' => 0, 'msg' => $msg, 'data' => $data];
                }
            }
            $msg ='Avatar has been changed successfully';
            $validateArr = $validateArr +[
                'avatar_id' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            ];
        }
        else{
            $validateArr = $validateArr + [
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'email' =>'required',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ];
        }
        // check validations
        $validator = Validator::make($request->all(), $validateArr);
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
            /* change password*/
            if($form_type == 'change-password')
            {
                if(Hash::check($old_password, $model->password))
                {
                    $model->password = bcrypt($password);
                    $model->save();
                }
                else
                {
                    $status = 0;
                    $msg = 'Old password is incorrect.';
                }
            }
            else if($form_type == 'change-avatar')
            {
                $avatar_id = $request->file('avatar_id');
                if(!empty($avatar_id))
                {
                    $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.$model->id;

                    $image_name =$avatar_id->getClientOriginalName();
                    $extension =$avatar_id->getClientOriginalExtension();
                    $image_name=md5($image_name);
                    $profile_image= $image_name.'.'.$extension;
                    $file =$avatar_id->move($destinationPath,$profile_image);
                       
                    $model->picture = $profile_image;
                    $model->save();
                }
            }
            else
            {
                $model->first_name = $request->get('first_name');
                $model->last_name =  $request->get('last_name');
                $model->email =  $request->get('email');
                $model->phone =  $request->get('phone');
                $model->save();
            }
        }
        return ['status' => $status, 'msg' => $msg, 'data' => $data];       

    }
    public function orderData(Request $request)
    {
        $authUser = \Auth::guard('admins')->user();
        $modal = Order::select('orders.user_id','orders.id','orders.total_price','users.first_name as userName','orders.created_at','addresses.address_line_1')
            ->leftJoin('users','orders.user_id','=','users.id')
            ->leftJoin('addresses','orders.address_id','=','addresses.id')
            ->where('orders.order_status','=','P');
        $modal = $modal->orderBy('orders.created_at','desc');
        return \DataTables::eloquent($modal)
        ->editColumn('created_at', function($row) {
            if(!empty($row->created_at))
                return date('Y-m-d h:i',strtotime($row->created_at));
            else
                return '';
        })->editColumn('totalPrice',function($row){
            return OrderDetail::getOrderTotalPrice($row->id);
        })->rawcolumns(['created_at','totalPrice'])
        ->make(true);
    }
}
