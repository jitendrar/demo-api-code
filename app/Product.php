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

    public static function _CheckProductIsActve($product_id=0) {
        if(!empty($product_id)) {
            $Product = Product::where('id',$product_id)->first();
            if($Product->status == Product::$STATUS_ACTIVE) {
                return true;
            }
        }
        return false;
    }


    public static function getAttachment($product_id = 0)
    {
        $img = '';
        $formObj = Product::find($product_id);
        if($formObj)
        {
            $img = asset($formObj->picture); 
            if(file_exists(public_path().$formObj->picture))
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
        return $this->hasMany(ProductsImages::class, 'id');
    }

    public static function productList($ArrProductID = array())
    {
        $modal =  Product::where('status', 1);
        if(!empty($ArrProductID)) {
            $modal = $modal->whereIn('products.id',$ArrProductID);
        }
        $modal = $modal->leftJoin('product_translations','products.id','=','product_translations.product_id');
        $modal = $modal->orderBy('product_translations.product_name', 'desc');
        $modal = $modal->pluck('product_translations.product_name', 'product_translations.product_id');
        return $modal->all();
    }
}
