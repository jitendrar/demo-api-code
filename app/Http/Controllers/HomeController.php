<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminAction;
use Validator;
use DataTables;
use Illuminate\Support\Facades\Mail;



class HomeController extends Controller
{
    public function __construct(){
    }
    
    public function index()
    {
        return view('home');
    }

    public function debug($flag=null)
    {
    	$realm = 'Restricted area';
    	$users = json_decode(env('DEBUG_LOGIN_DETAILS'), true);

    	// $data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST']);
    	// prd($data);

    	if(empty($_SERVER['PHP_AUTH_DIGEST'])) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Digest realm="'.$realm.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
            die('Text to send if user hits Cancel button');
        }

        // analyze the PHP_AUTH_DIGEST variable
       if (!($data = $this->http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']])) {
       		header("Status:401 Logout");
       		header("WWW-Authenticate: Invalidate, Basic realm=logout");
       		die('Wrong Credentials!');
        }
        // generate the valid response
        $A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
        $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
        if ($data['response'] != $valid_response){
			die('Wrong Credentials!');
        }
        // ok, valid username & password
        if($flag == "on"){
        	//echo 'You are logged in as: ' . $data['username'];
            $this->addIpInDebugeFile(1);
            return redirect('/');
            exit();
        } else if($flag == "off") {
            $this->addIpInDebugeFile(0);
            return redirect('/');
            exit();
        } else {
            header('HTTP/1.1 403 Forbidden');
            return redirect('/');
            exit();
        }
    }
    
    public function http_digest_parse($txt) {
        // protect against missing data
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));
        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }
        return $needed_parts ? false : $data;
    }

    public function addIpInDebugeFile($addIP = 0)
    {
        $ip 		= \Request::ip();
        $filePath 	= storage_path("debug_ip_list_cs.json");;
        $ips 		= array();
        
        if(is_file($filePath)) {
            $data = file_get_contents($filePath);
            $data = json_decode($data, true);
            $data[$ip] = $ip;
            $ips = $data;
        } else {
            $ips[$ip] = $ip;
        }
        
        if($addIP == 0) {
            if(isset($ips[$ip])) {
            	unset($ips[$ip]);
            }
        }
        $json = json_encode($ips);
        $myfile = fopen($filePath, "w") or die("Unable to open file!");
        fwrite($myfile, $json);
        fclose($myfile);
    }

    public function storeContactUsForm(Request $request)
    {
        $msgresponse = Array();
         $rules=array(
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => 'required|max:100'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails()) {
            return redirect(url()->previous() .'#contact-us')->withErrors($validator->errors());
            exit;
        } 

        // $url = 'https://www.google.com/recaptcha/api/siteverify';
        // $remoteip = $_SERVER['REMOTE_ADDR'];
        // $data = [
        //     'secret' => config('services.recaptcha.secret'),
        //     'response' => $request->get('recaptcha'),
        //     'remoteip' => $remoteip
        // ];
        // $options = [
        //     'http' => [
        //       'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        //       'method' => 'POST',
        //       'content' => http_build_query($data)
        //   ]
        // ];
        // $context = stream_context_create($options);
        // $result = file_get_contents($url, false, $context);
        // $resultJson = json_decode($result);
        // if ($resultJson->success != true) {
        // return redirect(url()->previous() .'#contact-us')->withErrors(['status' => 'ReCaptcha Error']);
        // }
        // if ($resultJson->score >= 0.3) {
            //Validation was successful, add your form submission logic here
            $emailTemplate = "admin.emails.contact_us";
            $EmailData['first_name']  = $request->first_name;
            $EmailData['last_name']   = $request->last_name;
            $EmailData['phone_number']     = $request->phone_number;
            $EmailData['email'] = $request->email;
            $content = ['content' => $EmailData];
            $EmailSubject = "Contact Us Email";
            $DISABLE_EMAIL_FOR_STAGING = env('DISABLE_EMAIL_FOR_STAGING', 1);
            if($DISABLE_EMAIL_FOR_STAGING) {
                if(!empty($emailTemplate)) {
                    try{

                        Mail::send($emailTemplate, $content, function($message)   use ($EmailSubject) {
                            $MAIL_FROM_ADDRESS          = env("MAIL_FROM_ADDRESS");
                            $MAIL_FROM_NAME             = env("MAIL_FROM_NAME");
                            $CONTACT_US_EMAIL_TO        = env("CONTACT_US_EMAIL_TO");
                            $CONTACT_US_EMAIL_TO_NAME   = env("CONTACT_US_EMAIL_TO_NAME");
                            $CONTACT_US_EMAIL_CC        = env("CONTACT_US_EMAIL_CC", '');
                            $CONTACT_US_EMAIL_CC_NAME   = env("CONTACT_US_EMAIL_CC_NAME", '');

                            $message->from($MAIL_FROM_ADDRESS, $MAIL_FROM_NAME);
                            $message->to($CONTACT_US_EMAIL_TO, $CONTACT_US_EMAIL_TO_NAME);
                            if(!empty($CONTACT_US_EMAIL_CC)) {
                                $message->cc($CONTACT_US_EMAIL_CC, $CONTACT_US_EMAIL_CC_NAME);
                            }
                            $message->subject($EmailSubject);
                        });

                    }catch (\Exception $e) {
                        return redirect(url()->previous() .'#contact-us')->with('error', 'Something Went Wrong');
                        echo $e->getMessage();
                    }
                  
                }
            }
            
            return redirect(url()->previous() .'#contact-us')->with('status', 'Thanks for contacting us !');
        // } else {
        // return redirect(url()->previous() .'#contact-us')->withErrors(['status' => 'ReCaptcha Error']);
        // }
    }

    public function privacypolicy() {
        return view('privacy_policy');
    }
    public function termsofuse() {
        return view('terms_of_use');
    }
}
