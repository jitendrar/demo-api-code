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

}