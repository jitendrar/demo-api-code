<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

	protected $table = 'addresses';

	protected $guarded = [];
	public static function getAddress($id)
    {
        $address = Address::where('user_id',$id)->first();
        return ($address)?$address->address :'';
    }

}
