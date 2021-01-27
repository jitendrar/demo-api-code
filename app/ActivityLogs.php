<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    protected $table = 'activity_logs';

    public static function storeActivityLog($params)
	 {
        $obj = new ActivityLogs();
        $obj->user_id     = (isset($params['user_id'])) ? $params['user_id'] : '';
        $obj->action_id     = (isset($params['action_id'])) ? $params['action_id'] : '';
        $obj->remark      = (isset($params['remark'])) ? $params['remark'] : '';
        $obj->data      = (isset($params['data'])) ? $params['data'] : '';
        $obj->save();
    }  
}
