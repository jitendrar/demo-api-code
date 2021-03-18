<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Product;
use App\OrderDetail;
use App\Order;
use App\Config;
use App\Address;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Validator;

class CommonController extends Controller
{
    public function getaboutus()
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        
        $aboutus    = Config::GetConfigurationList(Config::$ABOUT_US);
        if(!empty($aboutus)) {
            $status         = 1;
            $StatusCode     = 200;
            $msg            = __('words.retrieved_successfully');
            $data = $aboutus;
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    public function getcontactus()
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $contactus    = Config::GetConfigurationList(Config::$CONTACT_US);
        if(!empty($contactus)) {
            $status         = 1;
            $StatusCode     = 200;
            $msg            = __('words.retrieved_successfully');
            $data = $contactus;
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    public function referraldesc()
    {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $referral_m    = Config::GetConfigurationList(Config::$REFERRAL_MONEY);
        if(!empty($referral_m)) {
            $status         = 1;
            $StatusCode     = 200;
            $msg            = __('words.retrieved_successfully');
            $get_msg        = __('words.referral_text');
            $data['referal_text'] = str_replace("[MONEYTEXT]",$referral_m,$get_msg);;
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    public function paymentoptions()
    {
        $language  = \Request::header('language');
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $get_data    = Config::GetConfigurationList(Config::$PAYMENT_OPTIONS);
        if(!empty($get_data)) {
            $status         = 1;
            $StatusCode     = 200;
            $msg            = __('words.retrieved_successfully');
            $i = 0;
            foreach ($get_data as $k => $value) {
                $value = (array)$value;
                $data[$i]['payment_type']   = $value['payment_type'];
                $data[$i]['payment_number'] = $value['payment_number'];
                $data[$i]['description']    = $value['description_eng'];
                if(strtolower($language) == 'guj') {
                    $data[$i]['description'] = $value['description_guj'];
                }
                $data[$i]['logo'] = GetImageFromUpload($value['logo']);
                $i++;
            }
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

    public function referralinfo(Request $request) {
        $StatusCode     = 204;
        $status         = 0;
        $ArrReturn      = array();
        $msg            = __('words.no_data_available');
        $data           = array();
        $requestData = $request->all();
        $user_id = 0;
        if(isset($requestData['id']) && !empty($requestData['id'])) {
            $user_id = trim($requestData['id']);
        }
        $status         = 1;
        $StatusCode     = 200;
        $msg            = __('words.retrieved_successfully');
        $get_msg        = __('words.referral_text');
        $referral_m    = Config::GetConfigurationList(Config::$REFERRAL_MONEY);
        if(!empty($referral_m)) {
            $data['referral_text'] = str_replace("[MONEYTEXT]",$referral_m,$get_msg);;
        }
        $referral_msg_text        = __('words.referral_msg_text');
        if(!empty($user_id)) {
            $user = User::where('id',$user_id)->first();
            $data['referral_msg_text'] = str_replace("[USER_REFERRAL_CODE]",$user->referralcode,$referral_msg_text);
        }
        $ArrReturn = array("status" => $status,'message' => $msg, 'data' =>$data);
        $StatusCode = 200;
        return response($ArrReturn, $StatusCode);
    }

}
