<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferMaster extends Model
{

	protected $table = 'offer_masters';

    protected $guarded = [];

    public static $STATUS_ACTIVE = 1;

    public static $STATUS_INACTIVE = 0;

    public function offerDetail() {
        return $this->hasMany('App\OfferDetail');
    }

	public function product() {
		return $this->belongsTo('App\Product');
	}

    public static function _CheckOfferIsActve($offer_id=0) {
        if(!empty($offer_id)) {
            $OfferMaster = OfferMaster::where('id',$offer_id)->first();
            if($OfferMaster->status == OfferMaster::$STATUS_ACTIVE) {
                return true;
            }
        }
        return false;
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