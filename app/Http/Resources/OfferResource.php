<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\ProductMapping;
class OfferResource extends JsonResource
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
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'picture' => self::GetImage(),
            'offer_detail' => OfferDetailResource::collection($this->offerDetail),
            'created_at' => date($API_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at' => date($API_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }

    public function GetImage() {
        $picture = url("/images/no_image.jpeg");
        if(!empty($this->picture)) {
            $filename = public_path().$this->picture;
            if (file_exists($filename)) {
                $picture = url($this->picture);
            }
        }
        return $picture;
    }

}
