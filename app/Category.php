<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public static function getCategory($id)
    {
        $category = Category::find($id);

        return ($category)?$category->category_name :'';
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
}
