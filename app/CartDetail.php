<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OfferMaster;
use App\OfferDetail;
use App\Product;
use App\User;


class CartDetail extends Model
{

	protected $table = 'cart_details';

	protected $guarded = [];

	public static $IS_OFFER_YES = 1;

	public static $IS_OFFER_NO 	= 0;

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

	public static function _AddRemoveOfferItemsInCart($cart_id='', $requestData=array())
	{
		if(isset($requestData['non_login_token']) && !empty($requestData['non_login_token'])) {
			$non_login_token = $requestData['non_login_token'];
			if(!empty($cart_id)) {
				$cartdata = CartDetail::where('id',$cart_id)->first();
				if($cartdata){
					$OfferMaster = OfferMaster::where('status',OfferMaster::$STATUS_ACTIVE)
												->where('product_id',$cartdata->product_id)->first();
					if($OfferMaster) {
						if($cartdata->quantity >= $OfferMaster->quantity) {
							$OfferDetail = OfferDetail::where('offer_master_id',$OfferMaster->id)->get()->toArray();
							if(!empty($OfferDetail)) {
								$ArrCartCreate = array();
								$ArrCartCreate['non_login_token'] = $non_login_token;
								$ArrUSer = User::where('non_login_token',$non_login_token)->first();
								if($ArrUSer) {
									$ArrCartCreate['user_id'] = $ArrUSer->id;
								}
								$updateQ = floor($cartdata->quantity/$OfferMaster->quantity);
								foreach ($OfferDetail as $k => $V) {
									$OferQuantity 	= $updateQ*$V['quantity'];
									$ArrProduct = Product::_GetProductByID($V['product_id']);
									if($ArrProduct) {
										$OfferAdded = CartDetail::where('non_login_token', $non_login_token)->where('product_id', $ArrProduct->id)->where('is_offer',CartDetail::$IS_OFFER_YES)->first();
										$discount = $ArrProduct->unity_price*$OferQuantity;
										if($OfferAdded) {
											$ArrCartCreate['quantity'] 		= $OferQuantity;
											$ArrCartCreate['discount'] 		= $discount;
											$ArrCartCreate['is_offer'] 		= CartDetail::$IS_OFFER_YES;
											$OfferAdded->update($ArrCartCreate);
										} else {
											$ArrCartCreate['product_id'] 	= $ArrProduct->id;
											$ArrCartCreate['quantity'] 		= $OferQuantity;
											$ArrCartCreate['price'] 		= 0;
											$ArrCartCreate['discount'] 		= $discount;
											$ArrCartCreate['is_offer'] 		= CartDetail::$IS_OFFER_YES;
											CartDetail::create($ArrCartCreate);
										}
									}
								}
							}
						} else {
							$OfferDetail = OfferDetail::where('offer_master_id',$OfferMaster->id)->get()->toArray();
							if(!empty($OfferDetail)) {
								$ArrProductID = array();
								foreach ($OfferDetail as $k => $V){
									$ArrProductID[] = $V['product_id'];
								}
								$ObjCard = CartDetail::where('non_login_token', $non_login_token)->whereIn('product_id', $ArrProductID)->where('is_offer',CartDetail::$IS_OFFER_YES);
								$ObjCard->delete();
							}
						}
					}
				}
			}
		}
	}
}