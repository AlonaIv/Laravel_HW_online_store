<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'thumbnail' => $this->thumbnailUrl,
            'prices' => $this->getPrices()
        ];
    }

    protected function getPrices(): array
    {
        return [
            'price' => $this->price,
            'discount' => $this->discount,
            'final_price' => $this->endPrice
        ];
    }
}
