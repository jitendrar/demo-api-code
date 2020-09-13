<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\AddressResource;
class OrderResource extends JsonResource
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
                'address_id' => $this->address_id,
                'total_price' => $this->total_price,
                'delivery_charge' => $this->delivery_charge,
                'delivery_date' => date("Y-m-d", strtotime($this->delivery_date)),
                'delivery_time' => $this->delivery_time,
                'special_information' => $this->special_information,
                'order_number' => $this->order_number,
                'actual_delivery_date' => $this->actual_delivery_date,
                'actual_delivery_time' => $this->actual_delivery_time,
                'order_status' => _GetOrderStatus($this->order_status),
                'payment_method' => $this->payment_method,
                'created_at' => strtotime($this->created_at),
                'updated_at' => strtotime($this->updated_at),
                'address' => new AddressResource($this->address),
                'order_detail' => OrderDetailResource::collection($this->orderDetail),
        ];

    }
}
