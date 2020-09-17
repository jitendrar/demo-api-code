<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

	protected $table = 'order_details';

	protected $guarded = [];

	public function product() {
		return $this->belongsTo('App\Product');
	}

}
