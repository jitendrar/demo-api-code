<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

	protected $table = 'orders';

	protected $guarded = [];

	public static $ORDER_STATUS_PENDING = "P";
	
	public static $ORDER_STATUS_COMPLETE = "C";

	
	public function orderDetail()
	{
		return $this->hasMany('App\OrderDetail');
	}
}
