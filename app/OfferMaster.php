<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferMaster extends Model
{

	protected $table = 'offer_masters';

	protected $guarded = [];

	public function product() {
		return $this->belongsTo('App\Product');
	}

	public static function getAttachment($offer_id = 0)
    {
        $img = '';
        $formObj = OfferMaster::find($offer_id);
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

}