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
  } else if($statusid == 'C'){
    // return cancel
    return __('words.order_status_cancel');
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


function _SaveBased64Image($base64_string, $fileName, $folderPath)
{
    if (!file_exists($folderPath)) {
      mkdir($folderPath, 0777, true);
    }
    $image_parts    = explode(";base64,", $base64_string);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type     = $image_type_aux[1];
    $image_base64   = base64_decode($image_parts[1]);
    $output_file    = public_path().DIRECTORY_SEPARATOR.$folderPath.DIRECTORY_SEPARATOR. $fileName.'.'.$image_type;
    file_put_contents($output_file, $image_base64);
    return $folderPath.DIRECTORY_SEPARATOR. $fileName.'.'.$image_type;
}

function _ReturnImageForAPI($picturepath='') {
  $picture = url("/images/no_image.jpeg");
  if(!empty($picturepath)) {
      $filename = public_path().$picturepath;
      if (file_exists($filename)) {
          $picture = url($picturepath);
      }
  }
  return $picture;
}


function GetImageFromUpload($Newpicture = '') {
    $picture = url("/images/no_image.jpeg");
    if(!empty($Newpicture)) {
        $filename = public_path().$Newpicture;
        if (file_exists($filename)) {
            $picture = url($Newpicture);
        }
    }
    return $picture;
}

function EmailSendForAdmin($emailTemplate='', $EmailSubject = '', $EmailContent=array())
{
  $DISABLE_EMAIL_FOR_STAGING = env('DISABLE_EMAIL_FOR_STAGING', 1);
  if($DISABLE_EMAIL_FOR_STAGING) {
    if(!empty($emailTemplate)) {
      // Mail::send($emailTemplate, $EmailContent, function($message)   use ($EmailSubject) {
      //        $message->from('bopaldaily@gmail.com','Bopal Daily');
      //        $message->to('jitendra.rathod@phpdots.com', 'Jitendra Rathod');
      //        // $message->to('ashok.sadhu@phpdots.com', 'Jitendra Rathod');
      //        $message->cc('ashok.sadhu@phpdots.com','Ashok Sadhu');
      //        $message->subject($EmailSubject);
      // });
    }
  }
}


function SendSMSForAdmin($OtpMsg='')
{
  $users_phone    =  9825096687;
  $SMS_URL        = env('SMS_URL');
  $SMS_MOBILE     = env('SMS_MOBILE');
  $SMS_PASSWORD   = env('SMS_PASSWORD');
  $sURLL          = $SMS_URL."?mobile=".$SMS_MOBILE."&pass=".$SMS_PASSWORD."&senderid=AGLEEO&to=".$users_phone."&msg=".$OtpMsg;
  _CURLGeneralForAll($sURLL);

  $users_phone    = 9067121123;
  $SMS_URL        = env('SMS_URL');
  $SMS_MOBILE     = env('SMS_MOBILE');
  $SMS_PASSWORD   = env('SMS_PASSWORD');
  $sURLL          = $SMS_URL."?mobile=".$SMS_MOBILE."&pass=".$SMS_PASSWORD."&senderid=AGLEEO&to=".$users_phone."&msg=".$OtpMsg;
  _CURLGeneralForAll($sURLL);

}