<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceInfo extends Model
{

	protected $table = 'device_info';

	protected $guarded = [];


	public static function _CreateOrUpdate($ArrDeviceInfo = array()) {
		if(isset($ArrDeviceInfo['user_id']) && !empty($ArrDeviceInfo['user_id'])) {
			$DeviceInfo	= DeviceInfo::where('user_id',$ArrDeviceInfo['user_id'])->first();
			if($DeviceInfo) {
				DeviceInfo::where('user_id', $ArrDeviceInfo['user_id'])->update(['device_type' => $ArrDeviceInfo['device_type']]);
			} else {
				DeviceInfo::create($ArrDeviceInfo);
			}
		}
	}



}
