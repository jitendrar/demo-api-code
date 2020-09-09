<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'product' => new ProductResource($this->product),
        ];

    }
}
