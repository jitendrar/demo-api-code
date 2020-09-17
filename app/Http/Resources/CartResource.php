<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
class CartResource extends JsonResource
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
            'user_id' => $this->user_id,
            'non_login_token' => $this->non_login_token,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => round($this->price,2),
            'discount' => round($this->discount,2),
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'product' => new ProductResource($this->product),
        ];

    }
}
