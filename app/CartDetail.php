<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{

	protected $table = 'cart_details';

	protected $guarded = [];


	public function product() {
		return $this->belongsTo('App\Product');
	}

}
