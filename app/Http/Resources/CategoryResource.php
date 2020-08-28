<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'category_name' => $this->category_name,
            'created_at' => date($API_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at' => date($API_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
}
