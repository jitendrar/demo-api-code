<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    public static function _GetProductByID($product_id=0) {
        if(!empty($product_id)) {
            $Product = Product::where('id',$product_id)->first();
            return $Product;
        }
    }
}
