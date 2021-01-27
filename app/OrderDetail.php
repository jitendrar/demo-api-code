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

	public static function getProductTotalPrice($order_id){
		$totalPrice = 0;
		$productTotalDiscount = 0;
		$order = Order::find($order_id);
		$orderDetail = OrderDetail::where('order_id',$order_id)->get();
		foreach ($orderDetail as $detail) {
			$productPrice = $detail->price;
			$productDiscount = $detail->discount;
			if(!empty($productDiscount)){
				$productTotalDiscount = $productTotalDiscount + $productDiscount;
			}
			if(!empty($productPrice)){
				$totalPrice = $totalPrice + $productPrice;
			}
		}
		return $totalPrice;
	}

	public static function getOrderTotalPrice($order_id){
		$order = Order::find($order_id);
		$total = self::getProductTotalPrice($order_id);
		if($order){
			if(!empty($order->delivery_charge)){
				$total = $total + $order->delivery_charge;
			}
		}
		return $total;
	}

}
