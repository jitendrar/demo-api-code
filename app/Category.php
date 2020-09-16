<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Category extends Model implements TranslatableContract
{

	use Translatable;
	
	public $translatedAttributes = ['category_name', 'description'];

	protected $table = 'categories';

	protected $guarded = [];

    public static function getCategory($id)
    {
        $category = Category::find($id);

        return ($category)?$category->category_name :'-';
    }
    public function categories(){
        return $this->hasMany(categoryTranslation::class, 'category_id');
    } 
    public static function getAttachment($cat_id = 0)
    {
        $img = '';
        $formObj = Category::find($cat_id);
        if($formObj)
        {
            $img = asset('uploads/categories/'.$formObj->id.'/'.$formObj->picture);    
            if(file_exists(public_path().'/uploads/categories/'.$formObj->id.'/'.$formObj->picture))
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
    public static function categoryList(){
        return Category::where('status', 1)->leftJoin('category_translations','categories.id','=','category_translations.category_id')
            ->orderBy('category_translations.category_name', 'desc')
            ->pluck('category_translations.category_name', 'category_translations.category_id')
            ->all();
    }
}

