<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Product extends Model implements TranslatableContract
{

	use Translatable;

    public $translatedAttributes = ['product_name', 'description', 'units_in_stock','units_stock_type', 'unity_price'];

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
    public static function getAttachment($product_id = 0)
    {
        $img = '';
        $formObj = Product::find($product_id);
        if($formObj)
        {
            $img = asset('uploads/products/'.$formObj->id.'/'.$formObj->picture);    
            if(file_exists(public_path().'/uploads/products/'.$formObj->id.'/'.$formObj->picture))
            {
                $img = $img;
            }
            else
            {
                $img = asset('images/coming_soon.png');
            }
        }
        return $img;
    }
    
    public function images()
    {
        return $this->hasMany(ProductsImages::class, 'product_id');
    }
}
