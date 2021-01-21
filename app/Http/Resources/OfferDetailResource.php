<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
class OfferDetailResource extends JsonResource
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
            'offer_master_id' => $this->offer_master_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'created_at' => date($API_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at' => date($API_DATE_FORMAT,strtotime($this->updated_at)),
            'product' => new ProductResource($this->product),
        ];

    }
}
