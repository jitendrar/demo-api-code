<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryMaster extends Model
{
    protected $table = 'delivery_master';

    public static function getAttachment($id = 0)
	{
	  $img = '';
	  $formObj = DeliveryMaster::find($id);
	  if($formObj)
	  {
  		$img = asset($formObj->picture);    
        if(file_exists(public_path().$formObj->picture))
	      {
	          $img = $img;
	      }
	      else
	      {
	          $img = asset('images/coming_soon.png');
	      }
	  }
	  return $img;
	}

	public static function getActiveDeliveryUsers(){
		return DeliveryMaster::select('id',\DB::raw('CONCAT(first_name," ",last_name) as userName'))
						->where('status',1)
                        ->orderBy('userName','ASC')
                        ->pluck('userName','id')
                        ->all();
	}
	public static function getDeliveryUsers(){
		return DeliveryMaster::select('id',\DB::raw('CONCAT(first_name," ",last_name) as userName'))
                        ->orderBy('userName','ASC')
                        ->pluck('userName','id')
                        ->all();
	}

	public function orders()
	{
		return $this->hasMany(Order::class, 'delivery_master_id');
	}
}
