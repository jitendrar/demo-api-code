<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferDetail extends Model
{

	protected $table = 'offer_details';

	protected $guarded = [];

	public function product() {
		return $this->belongsTo('App\Product');
	}
	
}