<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Validator;
use App\AdminUser;
use App\ActivityLogs;
use App\AdminAction;


class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest_admin',['except' => ['getLogout']]);
       $this->activityAction = new AdminAction();

    }
    public function getLogin()
    {   
        return view('admin.before_login.login');
    } 
    public function postLogin(Request $request)
    {   
        $status = 0;
        $goto = route('admin-dashboard');
        $msg = "The credential that you've entered doesn't match any account.";
        
        $validator = Validator::make($request->all(), [
            'email' => 'required', 
            'password' => 'required',            
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
        else{
            if (Auth::guard('admins')->attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) 
            {
                /*$authuser = AdminUser::where('email',$request->get('email'))->first();
                if(!$authuser){
                    $msg = 'Your user cannot do this';
                    return ['status' => 0, 'msg' => $msg,'goto'=>$goto];
                }*/

                $user = Auth::guard('admins')->user();
                $status = 1;
                $msg ='Logged in successfully.';
                $user->save();

                /* store log */
                $params=array();
                $params['user_id']  = $user->id;
                $params['action_id']  = $this->activityAction->LOGIN_USER;
                $params['remark']   = 'Login Admin User';
                ActivityLogs::storeActivityLog($params);

            }
        } 
        return ['status' => $status, 'msg' => $msg,'goto'=>$goto];
       
    }
    public function toggleChange()
    {
        $toggleFlag = \session()->get('toggleFlag');

        if($toggleFlag == 1)
        {
            \session()->put(['toggleFlag'=>0]);
        }else{
            \session()->put(['toggleFlag'=>1]);
        }
    }
    public function getLogout()
    {
        $url = '/';
        $user = Auth::guard('admins')->user();
        /* store log */
        $params=array();
        $params['user_id']  = $user->id;
        $params['action_id']  = $this->activityAction->LOGOUT;
        $params['remark']   = 'Logout User';
        ActivityLogs::storeActivityLog($params);
        Auth::guard('admins')->logout();
        return redirect($url);  
    }
}
