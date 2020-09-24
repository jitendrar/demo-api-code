<?php

function pr($arr){
  echo "<pre>";
  print_r($arr);
}

function prd($arr){
  echo "<pre>";
  print_r($arr);
  exit();
}

function _IsJsonOrNot($string) {
  json_decode($string);
  return (json_last_error() == JSON_ERROR_NONE);
}

function _GetStatusName($statusid='') {
  if($statusid == 1) {
    return "Active";
  } else {
    return "Inactive";
  }
}

function _GetOrderStatus($statusid='') {
  
  if($statusid == "P") {
    // return "Pending";
    return __('words.order_status_pending');
  } else if($statusid == "D") {
    // return "Delivered";
    return __('words.order_status_delivered');
  }
}

function _CURLGeneralForAll($strRequestURL = null, $Method=null, $HeaderKey = array(), $arrpost=array())
{
    if(!empty($strRequestURL))
    {
        $conn = curl_init( $strRequestURL );

        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $conn, CURLOPT_URL, $strRequestURL);
        curl_setopt( $conn, CURLOPT_SSLVERSION, 1 );
        curl_setopt( $conn, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        
        if(!empty($HeaderKey)) {
          curl_setopt($conn, CURLOPT_HTTPHEADER, $HeaderKey);
        }

        if(strtoupper($Method) == 'POST') {
          curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($arrpost));
        }

        $output = curl_exec($conn);
        $result = json_decode($output);
        if(!empty($result)) {
            $result = object_to_array($result);
        }
        $info   = curl_getinfo($conn);
        $ArrReturn = array();
        $ArrReturn['info']    = $info;
        $ArrReturn['result']  = $result;
        $ArrReturn['error']   = curl_error($conn);
        // Close handle
        curl_close($conn);
        return $ArrReturn;
    }
}