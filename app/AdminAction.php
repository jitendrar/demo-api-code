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
    public $DELETE_USERS = 6;
    public $CHANGE_PASSWORD = 7;
    public $ADD_AMOUNT = 8;
    public $ADD_PRODUCT = 9;
    public $EDIT_PRODUCT = 10;
    public $ADD_CATEGORY = 11;
    public $EDIT_CATEGORY = 12;
    public $ADD_DELIVERY_USER = 13;
    public $EDIT_DELIVERY_USER = 14;
    public $ORDER_STATUS = 15;
    public $ASSIGN_DELIVERY_USER = 16;
    public $EDIT_ORDER = 17;
    public $DELETE_ORDER_PRODUCT = 18;
    public $ADD_ORDER_PRODUCT = 19;
    public $ADD_ORDER = 20;

    public static function activityTypeList()
    {
        return AdminAction::select('*')->pluck('title','id')->all();
    }
}
