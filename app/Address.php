<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\AddressResource;
class Address extends Model
{

	protected $table = 'addresses';

	protected $guarded = [];

	public static function _GetPrimaryAddressByUserID($user_id=0) {
        if(!empty($user_id)) {
            $Address = Address::where('user_id',$user_id)
            					->where('primary_address',1)->first();
            return new AddressResource($Address);
        }
    }
}
