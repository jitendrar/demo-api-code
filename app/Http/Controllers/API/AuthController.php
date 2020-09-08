<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\DeviceInfo;
use Validator;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{

	public function register(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $accessToken    = "";

        
        $RegisterData = Validator::make($request->all(), [
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10|unique:users',
            'password' => 'required|confirmed'
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $device_type        = $request->get("device_type");
            $requestData = $request->all();
            $requestData['password']    = bcrypt($requestData['password']);
            $requestData['new_phone']   = $requestData['phone'];
            $user = User::create($requestData);
            if($user) {
                $userID = $user->id;
                $ArrDeviceInfo = array();
                $ArrDeviceInfo['user_id'] = $user->id;
                $ArrDeviceInfo['device_type'] = $device_type;
                DeviceInfo::updateOrCreate($ArrDeviceInfo);
                $arrOtp['status'] = 1;
                $arrOtp = User::_SendOtp($userID);
                if($arrOtp['status'] == 1) {
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $StatusCode     = 200;
                    $status = 1;
                    $msg = 'User successfully created.';
                    $data = new UserResource($user);
                } else {
                    $msg = $arrOtp['msg'];
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }

    public function userverifyotp(Request $request) {
        
        $StatusCode = 403;
        $status = 0;
        $msg = "Please enter valid user id";
        $data           = array();
        $accessToken = "";
        $arrReturn = array();
        $RegisterData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'phone_otp' => 'required|numeric'
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $requestData = $request->all();
            $user_id = trim($requestData['id']);
            $userotp = trim($requestData['phone_otp']);
            $users = User::where('id',$user_id)->first();
            if($users) {
                if($userotp == $users->phone_otp) {
                    if($users->status == 0) {
                        $arrUpdate = array();
                        $arrUpdate['phone'] = $users->new_phone;
                        $arrUpdate['status'] = 1;
                        $arrUpdate['phone_verified_at'] = date('Y-m-d H:i:s');
                        User::where('id', $user_id)->update($arrUpdate);
                        $status = 1;
                        $StatusCode = 200;
                        $user = User::where('id',$user_id)->first();
                        $data = new UserResource($user);
                        $accessToken = $user->createToken('authToken')->accessToken;
                        $msg = "User successfully verified.";
                    } else {
                        $msg ="You have already verified OTP.";
                    }
                } else {
                    $StatusCode = 401;
                    $msg ="Invalid OTP. Please try again.";
                }
            } else {
                $StatusCode = 401;
                $msg = "The credential that you've entered doesn't match any account.";
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function otpresend(Request $request) {
        
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "Please enter valid phone";
        $data           = array();
        $accessToken    = "";
        $arrReturn      = array();
        
        $RegisterData   = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10',
        ]);

        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $requestData    = $request->all();
            $phone          = trim($requestData['phone']);
            $users          = User::where('phone',$phone)->first();
            if($users) {
                $userID = $users->id;
                $arrOtp = User::_SendOtp($userID);
                if($arrOtp['status'] == 1) {
                    $StatusCode     = 200;
                    $status = 1;
                    $msg    = 'OTP send successfully.';
                    $data   = new UserResource($users);
                } else {
                    $StatusCode     = 409;
                    $msg = $arrOtp['msg'];
                }
            } else {
                $StatusCode = 401;
                $msg = "The credential that you've entered doesn't match any account.";
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function login(Request $request)
    {
        $StatusCode = 401;
        $status = 0;
        $msg = "The credential that you've entered doesn't match any account.";
        $accessToken = "";
        $data = [];
        $data = array();
        $loginData = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10',
            'password' => 'required'
        ]);

        // check validations
        if ($loginData->fails()) {
            $messages = $loginData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode = 409;
                break;
            }
        } else {
            $loginData = [
                'phone' => $request->get("phone"),
                'password' => $request->get("password"),
            ];
            $device_type        = $request->get("device_type");
            $notification_token = $request->get("notification_token");
            if (auth()->attempt($loginData)) {
                $user = auth()->user();
                $status = 0;
                $msg = "Phone number does not verified.  Please verify your phone number.";
                if($user->status == 1) {
                    User::where('id',$user->id)->update(['notification_token' => $notification_token]);
                    $ArrDeviceInfo = array();
                    $ArrDeviceInfo['user_id'] = $user->id;
                    $ArrDeviceInfo['device_type'] = $device_type;
                    DeviceInfo::updateOrCreate($ArrDeviceInfo);
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = "Login Successfully.";
                    $data           = new UserResource($user);
                    $accessToken = auth()->user()->createToken('authToken')->accessToken;
                }
            }
        }
        // return ['status' => $status, 'message' => $msg, 'data' => $data,'access_token' => $accessToken];
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function passwordreset(Request $request) {

        $StatusCode     = 403;
        $status         = 0;
        $msg            = "Please enter valid phone";
        $data           = array();
        $accessToken    = "";
        $arrReturn      = array();

        $RegisterData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            // 'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10',
            'password' => 'required|confirmed'
        ]);

        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $requestData    = $request->all();
            $user_id        = $requestData['id'];
            $users = User::where('id',$user_id)->first();
            if($users) {
                $arrUpdate = array();
                $arrUpdate['password']    = bcrypt($requestData['password']);
                $users->update($arrUpdate);
                $StatusCode     = 200;
                $status = 1;
                $msg    = 'Password successfully changed.';
                $data   = new UserResource($users);
            } else {
                $StatusCode = 401;
                $status = 0;
                $msg = "The credential that you've entered doesn't match any account.";
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function verifyotp(Request $request) {
        
        $StatusCode = 403;
        $status = 0;
        $msg = "Please enter valid user id";
        $data           = array();
        $accessToken = "";
        $arrReturn = array();
        $RegisterData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'phone_otp' => 'required|numeric'
        ]);
        if ($RegisterData->fails()) {
            $messages = $RegisterData->messages();
            $status = 0;
            $msg = "";
            foreach ($messages->all() as $message) {
                $msg = $message;
                $StatusCode     = 409;
                break;
            }
        } else {
            $requestData = $request->all();
            $user_id = trim($requestData['id']);
            $userotp = trim($requestData['phone_otp']);
            $users = User::where('id',$user_id)->first();
            if($users) {
                if($userotp == $users->phone_otp) {
                    $status     = 1;
                    $StatusCode = 200;
                    $data       = new UserResource($users);
                    $accessToken = $users->createToken('authToken')->accessToken;
                    $msg = "OTP successfully verified.";
                } else {
                    $StatusCode = 401;
                    $msg ="Invalid OTP. Please try again.";
                }
            } else {
                $StatusCode = 401;
                $msg = "The credential that you've entered doesn't match any account.";
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

}
