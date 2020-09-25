<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
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


    public static function _GetActiveProductCountByCategoryID($category_id=0) {
        $ActiveProductCount = 0;
        if(!empty($category_id)) {
            $ProductMapping = ProductMapping::where('category_id',$category_id)->pluck('product_id');
            if($ProductMapping->count()) {
                $ArrProductID = $ProductMapping->toArray();
                $STATUS_ACTIVE = Product::$STATUS_ACTIVE;
                $ActiveProductCount = Product::where('products.status',$STATUS_ACTIVE)
                                    ->whereIn('products.id',$ArrProductID)
                                    ->count();
            }
        }
        return $ActiveProductCount;
    }
}
