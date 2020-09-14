<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\OrderResource;
class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => OrderResource::collection($this->collection),
            "current_page" => $this->currentPage(),
            "first_page_url"=> $this->url(1),
            "from"=> $this->firstItem(),
            "last_page"=> $this->lastPage(),
            "last_page_url"=> $this->url($this->lastPage()),
            "next_page_url"=> $this->nextPageUrl(),
            "path"=> $this->path(),
            "per_page"=> $this->perPage(),
            "prev_page_url"=> $this->previousPageUrl(),
            "to"=> $this->lastItem(),
            "total"=> $this->total(),
        ];
    }
}