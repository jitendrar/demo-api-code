<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMapping extends Model
{
	protected $table = 'product_mappings';

	protected $guarded = [];

    public static function _GetProductByCategoryID($category_id=0) {
    	$ArrProductID = array();
        if(!empty($category_id)) {
            $ProductMapping = ProductMapping::where('category_id',$category_id)->pluck('product_id');
            // echo "=======>".$ProductMapping->count()." < =======";
            if($ProductMapping->count()) {
            	$ArrProductID = $ProductMapping->toArray();
            }
        }
       	return $ArrProductID;
    }

}
