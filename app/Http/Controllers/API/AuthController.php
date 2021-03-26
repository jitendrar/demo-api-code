<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\DeviceInfo;
use App\Config;
use App\CartDetail;
use App\WalletHistory;
use Validator;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{

    public function __construct() {
        $this->language  = \Request::header('language');
    }

	public function register(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $accessToken    = "";

        $RegisterData = Validator::make($request->all(), [
            'first_name' => 'required|max:55|min:3',
            'last_name' => 'required|max:55',
            'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10|unique:users',
            // 'password' => 'required|confirmed',
            'referralfrom' => [
                                function ($attribute, $value, $fail) {
                                    if(!empty($value)) {
                                        $usercount = User::query()->where('referralcode','=',$value)->count();
                                        if($usercount <= 0){
                                            $fail('The referral code is invalid.');
                                        }
                                    }
                                },
                            ],
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
            $device_type = $request->get("device_type");
            $requestData = $request->all();
            $statement  = \DB::select("SHOW TABLE STATUS LIKE 'users'");
            $nextId     = $statement[0]->Auto_increment;
            $firstname  = substr($requestData['first_name'],0,3);
            $referralcode = strtoupper($firstname).$nextId;
            
            if(isset($requestData['password']) && !empty($requestData['password'])) {
                $requestData['password']    = bcrypt($requestData['password']);
            }

            $requestData['new_phone']   = $requestData['phone'];
            $requestData['referralcode'] = $referralcode;
            $user = User::create($requestData);
            if($user) {

                $userID = $user->id;
                CartDetail::_UpdateUserIDByLoginToke($userID,$user->non_login_token);
                $ArrDeviceInfo = array();
                $ArrDeviceInfo['user_id'] = $user->id;
                $ArrDeviceInfo['device_type'] = $device_type;
                DeviceInfo::_CreateOrUpdate($ArrDeviceInfo);
                $arrOtp['status'] = 1;
                $arrOtp = User::_SendOtp($userID);
                if($arrOtp['status'] == 1) {
                    $user = User::where('id',$userID)->first();
                    $data = new UserResource($user);
                    $accessToken    = $user->createToken('authToken')->accessToken;
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg = __('words.user_created_successfully');
                    $data = new UserResource($user);
                    $OtpMsg = "New User Onboarded On BopalDaily,";
                    $OtpMsg.="\r\nUser ID :: ".$userID;
                    $OtpMsg.="\r\nUser Name :: ".$user->first_name.' '.$user->last_name;
                    $OtpMsg = urlencode($OtpMsg);
                    SendSMSForAdmin($OtpMsg);
                    // $content = ['content' => $user->toArray()];
                    // EmailSendForAdmin('admin.emails.new_user_created', 'New User Onboarded On BopalDaily', $content);
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
                        if(isset($user['referralfrom']) && !empty($user['referralfrom'])) {
                            // WalletHistory::AddReferaalMoney($user);
                        }
                        $data = new UserResource($user);
                        $accessToken = $user->createToken('authToken')->accessToken;
                        $msg = __('words.verified_otp');
                    } else {
                        $msg = __('words.already_verified_otp');
                    }
                } else {
                    $StatusCode = 401;
                    $msg = __('words.invalid_otp');
                }
            } else {
                $StatusCode = 401;
                $msg = __('words.user_not_found');
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
                    $msg = __('words.otp_sent');
                    $user = User::where('id',$userID)->first();
                    $data = new UserResource($user);
                } else {
                    $StatusCode     = 409;
                    $msg = $arrOtp['msg'];
                }
            } else {
                $StatusCode = 401;
                $msg = __('words.user_not_found');
            }
        }

        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function login(Request $request)
    {
        $StatusCode = 401;
        $status = 0;
        $msg = __('words.user_not_found');
        $accessToken = "";
        $data = [];
        $data = array();
        $loginData = Validator::make($request->all(), [
            'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10|exists:users',
            // 'password' => 'required'
        ], ['phone.exists' => $msg]);

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
            $msg = __('words.incorrect_password');
            $requestData = $request->all();
            $device_type        = $request->get("device_type");
            $notification_token = $request->get("notification_token");
            $non_login_token    = $request->get("non_login_token");

            if(isset($requestData["password"]) && !empty($requestData["password"])) {
                $loginData = [
                    'phone' => $request->get("phone"),
                    'password' => $request->get("password"),
                ];
                if (auth()->attempt($loginData)) {
                    $user = auth()->user();
                    $status = 0;
                    $msg = __('words.mobile_not_verified');
                    if($user->status == 1) {
                        User::where('id',$user->id)->update(['non_login_token' => $non_login_token]);
                        CartDetail::_UpdateUserIDByLoginToke($user->id,$non_login_token);
                        CartDetail::_DeleteOtherCartByUserIDByLoginToke($user->id,$non_login_token);
                        User::where('id',$user->id)->update(['notification_token' => $notification_token]);
                        $ArrDeviceInfo = array();
                        $ArrDeviceInfo['user_id'] = $user->id;
                        $ArrDeviceInfo['device_type'] = $device_type;
                        DeviceInfo::_CreateOrUpdate($ArrDeviceInfo);
                        $StatusCode     = 200;
                        $status         = 1;
                        $msg = __('words.login');
                        $data           = new UserResource($user);
                        $accessToken = auth()->user()->createToken('authToken')->accessToken;
                    }
                }
            } else {
                $user = User::where('phone',$requestData["phone"])->first();
                $status = 0;
                $msg = __('words.mobile_not_verified');
                if($user->status == 1) {
                    User::where('id',$user->id)->update(['non_login_token' => $non_login_token]);
                    CartDetail::_UpdateUserIDByLoginToke($user->id,$non_login_token);
                    CartDetail::_DeleteOtherCartByUserIDByLoginToke($user->id,$non_login_token);
                    User::where('id',$user->id)->update(['notification_token' => $notification_token]);
                    $ArrDeviceInfo = array();
                    $ArrDeviceInfo['user_id'] = $user->id;
                    $ArrDeviceInfo['device_type'] = $device_type;
                    DeviceInfo::_CreateOrUpdate($ArrDeviceInfo);
                    $userID = $user->id;
                    $arrOtp = User::_SendOtp($userID);
                    if($arrOtp['status'] == 1) {
                        $StatusCode     = 200;
                        $status = 1;
                        $msg = __('words.otp_sent');
                        $user = User::where('id',$userID)->first();
                        $data = new UserResource($user);
                    } else {
                        $StatusCode     = 409;
                        $msg = $arrOtp['msg'];
                    }
                }
            }
        }

        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function userlogin(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $accessToken    = "";

        $RegisterData = Validator::make($request->all(), [
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
            $device_type = $request->get("device_type");
            $notification_token = $request->get("notification_token");
            $requestData = $request->all();
            $statement  = \DB::select("SHOW TABLE STATUS LIKE 'users'");
            $nextId     = $statement[0]->Auto_increment;
            $firstname  =  env('REFERRELNAME');
            $referralcode = strtoupper($firstname).$nextId;
            $requestData['new_phone']   = $requestData['phone'];

            $user = User::where('phone',$requestData["phone"])->first();
            if($user){

                $status = 0;
                $msg = __('words.mobile_not_verified');
                if($user->status == 1) {
                    User::where('id',$user->id)->update(['notification_token' => $notification_token]);
                    $ArrDeviceInfo = array();
                    $ArrDeviceInfo['user_id'] = $user->id;
                    $ArrDeviceInfo['device_type'] = $device_type;
                    DeviceInfo::_CreateOrUpdate($ArrDeviceInfo);
                    $userID = $user->id;
                    $arrOtp = User::_SendOtp($userID);
                    if($arrOtp['status'] == 1) {
                        $StatusCode     = 200;
                        $status = 1;
                        $msg = __('words.otp_sent');
                        $user = User::where('id',$userID)->first();
                        $data = new UserResource($user);
                    } else {
                        $StatusCode     = 409;
                        $msg = $arrOtp['msg'];
                    }
                }
            }else{
                $isreferralvalid = 'true';
                if (!empty($request->get("referralfrom"))) {
                        $usercount = User::query()->where('referralcode','=',$request->get("referralfrom"))->count();
                        if($usercount <= 0){
                            $isreferralvalid = 'false';
                            $msg ='The referral code is invalid.';
                        }
                }
                if($isreferralvalid == 'true'){
                    $requestData['referralcode'] = $referralcode;
                    $requestData['status'] = 1;
                    $user = User::create($requestData);
                    if($user) {
                        $userID = $user->id;
                        $ArrDeviceInfo = array();
                        $ArrDeviceInfo['user_id'] = $user->id;
                        $ArrDeviceInfo['device_type'] = $device_type;
                        DeviceInfo::_CreateOrUpdate($ArrDeviceInfo);
                        $arrOtp['status'] = 1;
                        $arrOtp = User::_SendOtp($userID);
                        if($arrOtp['status'] == 1) {
                            $user = User::where('id',$userID)->first();
                            $data = new UserResource($user);
                            $StatusCode     = 200;
                            $status         = 1;
                            $msg = __('words.user_created_successfully');
                            $data = new UserResource($user);
                            $OtpMsg = "New User Onboarded On BopalDaily,";
                            $OtpMsg.="\r\nUser ID :: ".$userID;
                            $OtpMsg.="\r\nUser Name :: ".$user->first_name.' '.$user->last_name;
                            $OtpMsg = urlencode($OtpMsg);
                            SendSMSForAdmin($OtpMsg);
                        } else {
                            $msg = $arrOtp['msg'];
                        }
                    }
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
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
                $msg = __('words.password_changed');
                $data   = new UserResource($users);
            } else {
                $StatusCode = 401;
                $status = 0;
                $msg = __('words.user_not_found');
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
            $users  = User::where('id',$user_id)->first();
            if($users) {
                $userID = $users->id;
                if($userotp == $users->phone_otp) {
                    $status     = 1;
                    $StatusCode = 200;
                    $msg = __('words.verified_otp');
                    if(isset($requestData['changephone']) && trim($requestData['changephone']) == 1) {
                        $new_phone  = $users->new_phone;
                        User::where('id', $userID)->update(['phone' => $new_phone]);
                        $msg = __('words.phone_change_sucsses');
                    }
                    $users  = User::where('id',$userID)->first();
                    $data   = new UserResource($users);
                    $accessToken = $users->createToken('authToken')->accessToken;
                } else {
                    $StatusCode = 401;
                    $msg = __('words.invalid_otp');
                }
            } else {
                $StatusCode = 401;
                $msg = __('words.user_not_found');
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

        public function verifyotpusers(Request $request) {
        
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
            $users  = User::where('id',$user_id)->first();
            if($users) {
                $userID = $users->id;
                if($userotp == $users->phone_otp) {
                    $status     = 1;
                    $StatusCode = 200;
                    $msg = __('words.verified_otp');
                    $users  = User::where('id',$userID)->first();
                    $data   = new UserResource($users);
                    $accessToken = $users->createToken('authToken')->accessToken;
                } else {
                    $StatusCode = 401;
                    $msg = __('words.invalid_otp');
                }
            } else {
                $StatusCode = 401;
                $msg = __('words.user_not_found');
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function sendnewphoneotp(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $accessToken    = "";

        $RegisterData = Validator::make($request->all(), [
            'id' => 'required',
            'phone' => 'required|numeric|regex:/[6-9]\d{9}/|digits:10|unique:users',
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
            $user_id        = trim($requestData['id']);
            $new_phone      = trim($requestData['phone']);
            $user           = User::where('id',$user_id)->first();
            if($user) {
                $userID = $user->id;
                User::where('id', $userID)->update(['new_phone' => $new_phone]);
                $arrOtp['status'] = 1;
                $ChangePhone = 1;
                $arrOtp = User::_SendOtp($userID, $ChangePhone);
                if($arrOtp['status'] == 1) {
                    $StatusCode     = 200;
                    $status         = 1;
                    $msg            = __('words.user_created_successfully');
                    $user           = User::where('id',$userID)->first();
                    $accessToken    = $user->createToken('authToken')->accessToken;
                    $data           = new UserResource($user);
                } else {
                    $msg = $arrOtp['msg'];
                }
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn,$StatusCode);
    }


    public function logout(Request $request)
    {
        $StatusCode = 401;
        $status = 0;
        $msg = __('words.user_not_found');
        $accessToken = "";
        $data = [];
        $data = array();
        $loginData = Validator::make($request->all(), [
            'id' => 'required|numeric',
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
            $id     = $request->get("id");
            $user   = User::where('id',$id)->first();
            if ($user) {
                \DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => true]);
                $StatusCode     = 200;
                $status         = 1;
                $msg            = "Logout Successfully";
                // $data           = new UserResource($user);
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function getversion(Request $request)
    {
        
        $StatusCode = 200;
        $status     = 1;
        $msg        = __('words.retrieved_successfully');
        $accessToken = "";
        $data = array();
        $data = Config::GetConfigurationList(Config::$GET_VERSION);
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function updateProfile(Request $request)
    {
        $StatusCode = 401;
        $status = 0;
        $msg = __('words.user_not_found');
        $accessToken = "";
        $data = [];
        $data = array();
        $loginData = Validator::make($request->all(), [
            'id' => 'required|numeric',
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
            $id    = $request->get("id");
            $picture    = $request->get("picture");
            $user       = User::where('id',$id)->first();
            if ($user) {
                if(!empty($picture)) {
                    $folderPath =  'uploads'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.$user->id;
                    $fileName = $user->id.time();
                    $output = _SaveBased64Image($picture, $fileName, $folderPath);
                    $arrUpdate = array();
                    $arrUpdate['picture'] = $output;
                    User::where('id', $user->id)->update($arrUpdate);
                }
                $StatusCode     = 200;
                $status         = 1;
                $msg    = __('words.profile_image_changed');
                $user   = User::where('id',$user->id)->first();
                $data   = new UserResource($user);
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function changeuserlng(Request $request) {

        $StatusCode = 403;
        $status = 0;
        $msg = "Please enter valid user id";
        $data           = array();
        $accessToken = "";
        $arrReturn = array();
        $RegisterData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'lang' => 'required'
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
            $users = User::where('id',$user_id)->first();
            if($users) {
                $arrUpdate = array();
                $arrUpdate['lang'] = $requestData['lang'];
                User::where('id', $users->id)->update($arrUpdate);
                $status     = 1;
                $StatusCode = 200;
                $user = User::where('id',$users->id)->first();
                $data = new UserResource($user);
                $msg = __('words.retrieved_successfully');
            } else {
                $StatusCode = 401;
                $msg = __('words.user_not_found');
            }
        }
        $arrReturn = array("status" => $status,'message' => $msg, "data" => $data);
        $StatusCode = 200;
        return response($arrReturn, $StatusCode);
    }

    public function updateuserprofile(Request $request)
    {
        $StatusCode     = 403;
        $status         = 0;
        $msg            = "";
        $data           = array();
        $accessToken    = "";
        $usersdata = Validator::make($request->all(), [
            'user_id' => 'required|max:55|min:1',
            'first_name' => 'nullable|max:55|min:3',
            'last_name' => 'nullable|max:55',
            'phone' => 'nullable|numeric|regex:/[6-9]\d{9}/|digits:10|min:8|unique:users,phone,'.trim($request->get("user_id")),
        ]);
            if ($usersdata->fails()) {
                $messages = $usersdata->messages();
                $status = 0;
                $msg = "";
                foreach ($messages->all() as $message) {
                    $msg = $message;
                    $StatusCode     = 409;
                    break;
                }
            } else {
                $requestData = $request->all();
                $user_id = trim($requestData['user_id']);
                $UserDetail = User::where('id',$user_id)->first();
                if($UserDetail) {
                        $UserDetail->update($requestData);
                        $accessToken    = $UserDetail->createToken('authToken')->accessToken;
                        $StatusCode     = 200;
                        $status         = 1;
                        $msg = __('words.user_updated_successfully');
                        $data = new UserResource($UserDetail);
               
                }else{
                      $StatusCode     = 204;
                    $status         = 0;
                    $msg            = 'No User Found.';
                }
        }
         $arrReturn = array("status" => $status,'message' => $msg, "data" => $data, 'access_token' => $accessToken);
            $StatusCode = 200;
            return response($arrReturn,$StatusCode);
    }

}
