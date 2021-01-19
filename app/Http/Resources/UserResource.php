<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'new_phone' => $this->new_phone,
            'phone_otp' => $this->phone_otp,
            'referralcode' => $this->referralcode,
            'referralfrom' => $this->referralfrom,
            'status' => _GetStatusName($this->status),
            'picture' => self::GetImage(),
            'created_at' => date($API_DATE_FORMAT,strtotime($this->created_at)),
            'updated_at' => date($API_DATE_FORMAT,strtotime($this->updated_at)),
        ];
    }
    public function GetImage() {
        $picture = url("/images/no_image.jpeg");
        if(!empty($this->picture)) {
            $filename = public_path().DIRECTORY_SEPARATOR.$this->picture;
            if (file_exists($filename)) {
                $picture = url($this->picture);
            }
        }
        return $picture;
    }
}
