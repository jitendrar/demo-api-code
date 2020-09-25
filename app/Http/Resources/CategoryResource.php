<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\ProductMapping;
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $API_DATE_FORMAT = env('API_DATE_FORMAT');
        return [
            'id' => $this->id,
            'category_name' => self::_SetCategoryNameWithCount(),
            'description' => $this->description,
            'picture' => self::GetImage(),
            'created_at' => date($API_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at' => date($API_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }

    public function _SetCategoryNameWithCount() {
        $ArrProductID  = ProductMapping::_GetActiveProductCountByCategoryID($this->id);
        // return $this->category_name;
        return $this->category_name." (".$ArrProductID.")";
    }
    public function GetImage() {
        $picture = url("/images/no_image.jpeg");
        if(!empty($this->picture)) {
            $filename = public_path()."/".$this->picture;
            if (file_exists($filename)) {
                $picture = url("/".$this->picture);
            }
        }
        return $picture;
    }

}
