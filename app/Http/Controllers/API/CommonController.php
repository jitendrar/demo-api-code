<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Product;
use App\OrderDetail;
use App\Order;
use App\Config;
use App\Address;
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

}
