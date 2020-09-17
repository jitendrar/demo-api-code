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
}
