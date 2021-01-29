<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OfferMaster;

class OfferDetail extends Model
{

	protected $table = 'offer_details';

	protected $guarded = [];

	public function product() {
		return $this->belongsTo('App\Product');
	}
	
	public static function _CheckProductIsInOfferOrNot($product_id=0) {
        if(!empty($product_id)) {
            $OfferDetail = OfferDetail::where('product_id',$product_id)->first();
            if($OfferDetail) {
            	if(OfferMaster::_CheckOfferIsActve($OfferDetail->offer_master_id)) {
            		return true;
            	}
            }
        }
        return false;
    }
}