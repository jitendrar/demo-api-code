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
    protected $fillable = ['first_name','last_name','phone', 'new_phone','password',];
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

    public static function _SendOtp($user_id =0) {
        if(!empty($user_id)) {
            $users = User::where('id',$user_id)->first();
            if($users) {
                $users_id       = $users->id;
                $users_phone    = $users->phone;
                $phone_otp      = 1234568;
                $length = 6;
                $x = time();
                $phone_otp      = substr(str_shuffle(str_repeat($x, ceil($length/strlen($x)) )),1,$length);
                User::where('id', $users_id)->update(['phone_otp' => $phone_otp]);
                return true;
            }
        }
        return false;
    }
}
