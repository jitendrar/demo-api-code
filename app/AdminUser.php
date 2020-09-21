<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class AdminUser extends Authenticatable
{
	use HasApiTokens, Notifiable;

    protected $table = 'admin_user';
    protected $fillable = ['first_name','lastname','email','password','phone'];
}
