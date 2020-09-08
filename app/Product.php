<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Product extends Model implements TranslatableContract
{

	use Translatable;

    public $translatedAttributes = ['product_name', 'description'];

	protected $table = 'products';

	protected $guarded = [];

	public static $STATUS_ACTIVE = 1;

	public static $STATUS_INACTIVE = 0;


    public static function _GetProductByID($product_id=0) {
        if(!empty($product_id)) {
            $Product = Product::where('id',$product_id)->first();
            return $Product;
        }
    }
}
