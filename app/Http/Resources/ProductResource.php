<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category_id' => $this->category_id,
            'product_name' => $this->product_name,
            'description' => $this->description,
            'units_in_stock' => $this->units_in_stock,
            'units_stock_type' => $this->units_stock_type,
            'picture' => self::GetImage(),
            'status' => $this->status,
            'gst_charge' => $this->gst_charge,
            'unity_price' => $this->unity_price,
            'isAvailableInCart' => is_null($this->isAvailableInCart)?0:$this->isAvailableInCart,
            'created_at' => date($API_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at' => date($API_DATE_FORMAT,strtotime($this->updated_at)),
        ];
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
