<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['non_login_token',
                            'first_name','last_name','phone', 'new_phone','password','notification_token'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function _SendOtp($user_id =0, $ChangePhone=0) {
        if(!empty($user_id)) {
            $users = User::where('id',$user_id)->first();
            if($users) {
                $users_id       = $users->id;
                $first_name     = $users->first_name;
                $users_phone    = $users->phone;
                if($ChangePhone == 1) {
                    $users_phone    = $users->new_phone;
                }
                $phone_otp      = 906712;
                $length = 6;
                $x = time();
                $phone_otp      = substr(str_shuffle(str_repeat($x, ceil($length/strlen($x)) )),1,$length);
                $PRODUCT_NAME        = env('PRODUCT_NAME');
                // $OtpMsg = "Hi ".$first_name.",\r\nYour OTP is ".$phone_otp.".\r\n\r\nSee you soon,\r\nTeam Phpdots";
                $OtpMsg = $phone_otp." is your OTP for account verification with ".$PRODUCT_NAME." App";
                $OtpMsg = urlencode($OtpMsg);

                // $users_phone = 9067121123;

                $SMS_URL        = env('SMS_URL');
                $SMS_MOBILE     = env('SMS_MOBILE');
                $SMS_PASSWORD   = env('SMS_PASSWORD');
                $sURLL          = $SMS_URL."?mobile=".$SMS_MOBILE."&pass=".$SMS_PASSWORD."&senderid=AGLEEO&to=".$users_phone."&msg=".$OtpMsg;
                $CURLREsponce = _CURLGeneralForAll($sURLL);
                if(isset($CURLREsponce['info']['http_code']) && $CURLREsponce['info']['http_code'] ==200)
                {
                    User::where('id', $users_id)->update(['phone_otp' => $phone_otp]);
                    $reArr = array('status' => 1);
                    return $reArr;
                } else {
                    $reArr = array('status' => 0, 'msg' => __('words.otp_not_sent'));
                    return $reArr;
                }
            } else {
                $reArr = array('status' => 0, 'msg' => __('words.user_not_found'));
            }
        }
        $reArr = array('status' => 0, 'msg' => __('words.user_not_found'));
        return $reArr;
    }

    public static function getUserList()
    {
        return User::select('id',\DB::raw('CONCAT(first_name," ",last_name) as userName'),\DB::raw('CONCAT(CONCAT(first_name," ",last_name)," ",CONCAT("(",phone,")")) as fullName'))
                        ->orderBy('userName','ASC')
                        ->pluck('fullName','id')
                        ->all();

    } 
}
