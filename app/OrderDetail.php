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

	public static function getOrderTotalPrice($order_id){
		$totalPrice = 0;
		$order = Order::find($order_id);
		$orderDetail = OrderDetail::where('order_id',$order_id)->get();
		foreach ($orderDetail as $detail) {
			$productPrice = $detail->price;
			$productDiscount = $detail->discount;
			$productQty = $detail->quantity;
			if(!empty($productDiscount)){
				$totalProductPrice = ($productPrice * $productQty) -($productDiscount * $productQty);
			}else{
				$totalProductPrice = ($productPrice * $productQty);
			}
			$totalPrice = $totalPrice + $totalProductPrice; 
		}
		if($order){
			if(!empty($order->delivery_charge)){
				$totalPrice = $totalPrice + $order->delivery_charge;		
			}
		}
		return $totalPrice;
	}

}
