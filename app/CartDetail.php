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

	public static function _UpdateUserIDByLoginToke($user_id =0, $non_login_token=0) {
		if(!empty($user_id) && !empty($non_login_token)) {
			CartDetail::where('non_login_token',$non_login_token)->update(['user_id' => $user_id]);
		}
	}

	public static function _DeleteOtherCartByUserIDByLoginToke($user_id =0, $non_login_token=0) {
		if(!empty($user_id) && !empty($non_login_token)) {
			CartDetail::where('user_id', $user_id)
						->where('non_login_token','!=', $non_login_token)
						->delete();
		}
	}

	public static function _AddUpdateCartItems($requestData=array()) {
		if(isset($requestData['non_login_token']) && !empty($requestData['non_login_token'])) {
			if(isset($requestData['product_id']) && !empty($requestData['product_id'])) {
				$CartDetail  = CartDetail::where('non_login_token',$requestData['non_login_token'])
										->where('product_id',$requestData['product_id'])
										->first();
				if($CartDetail) {
					$requestData['quantity'] = $requestData['quantity']+$CartDetail->quantity;
                    $ArrProduct = Product::_GetProductByID($requestData['product_id']);
                    if($ArrProduct) {
                        $unity_price            = $ArrProduct->unity_price;
                        $requestData['price']   = $unity_price*$requestData['quantity'];
                    }
                    $requestData['status'] = 1;
                    $CartDetail->update($requestData);
				} else {
					CartDetail::create($requestData);
				}
			}
		}
	}

}