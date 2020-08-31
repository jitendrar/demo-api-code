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

function _GetStatusName($statusid='') {
  if($statusid == 1) {
    return "Active";
  } else {
    return "Inactive";
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
        return $ArrReturn;
    }
}