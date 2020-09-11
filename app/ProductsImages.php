<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsImages extends Model
{
    protected $table = 'products_images';
    protected $guarded = ['id'];
    public $timestamps = false;

    CONST PRODUCT_IMAGES_PATH =  '/public/uploads/products/';
    CONST PRODUCT_IMAGES_LIMIT =  10;

    public function isPrimary()
    {
        return ($this->is_primary == 1);
    }
    public function getPath($size = '')
    {
        $size_path = '';
        switch ($size) {
            case 50:
                $size_path = '50/';
                break;
            case 100:
                $size_path = '100/';
                break;
            case 200:
                $size_path = '200/';
                break;
            case 512:
                $size_path = '512/';
                break;
            
            default:
                break;
        }

        $file = url('/public/uploads/products/'.$size_path.$this->src);
        if (@getimagesize($file)) {
             $exists = true;
        }
        else{
            $exists = false;
        }

        if($exists){
            return $file;
        }
	return url('/public/uploads/products/'.$this->src);
    }
}
