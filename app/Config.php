<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	protected $table = 'configs';

	protected $guarded = [];

    public static $GET_VERSION = 1;

	public static $DELIVERY_CHARGE = 2;

    public static $GST_CHARGE = 3;

    public static $ABOUT_US = 4;

    public static $CONTACT_US = 5;


    public static function GetConfigurationList($id)
    {
        $arr_Config = array();
        if(!empty($id)) {
            $arr_Config = Config::where("id",$id)->first();
            if($arr_Config){
                if(_IsJsonOrNot($arr_Config->value)) {
                    return json_decode($arr_Config->value);
                } else {
                    return $arr_Config->value;
                }
            }
        }
        return $arr_Config;
    }




}
