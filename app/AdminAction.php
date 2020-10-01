<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $keyType = 'integer';
    protected $table = 'admin_action';

    protected $fillable = ['id', 'title','remark','created_at', 'updated_at'];

    public $LOGOUT = 1;
    public $LOGIN_USER = 2;
    public $UPDATE_PROFILE = 3;
    public $ADD_USERS = 4;
    public $EDIT_USERS = 5;
    public $SHOW_USERS =6;
    public $DELETE_USERS = 7;
    public $CHANGE_PASSWORD = 8;
    public $ADD_AMOUNT = 15;
}
