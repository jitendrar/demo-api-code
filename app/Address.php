<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\AddressResource;
class Address extends Model
{

	protected $table = 'addresses';

	protected $guarded = [];
	public static function getAddress($id)
    {
        $address = Address::where('user_id',$id)->first();
        return ($address)?$address->address_line_1 :'';
    }

	public static function _GetPrimaryAddressByUserID($user_id=0) {
        if(!empty($user_id)) {
            $Address = Address::where('user_id',$user_id)->where('primary_address',1)->first();
            if($Address) {
            	return new AddressResource($Address);
            } else {
            	return array();
            }
        }
    }

    public static function _SelectAddressForCartByUserID($user_id=0) {
        if(!empty($user_id)) {
            $Address = Address::where('user_id',$user_id)->where('is_select',1)->first();
            if($Address) {
                return new AddressResource($Address);
            } else {
                $Address = Address::where('user_id',$user_id)->where('primary_address',1)->first();
                if($Address) {
                    return new AddressResource($Address);
                } else {
                    return array();
                }
            }
        }
    }
}
