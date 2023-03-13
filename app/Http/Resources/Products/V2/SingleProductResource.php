<?php

namespace App\Http\Resources\Products\V2;

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
            'prices' => $this->getPrices(),
            'categories' => $this->getCategories(),
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

    protected function getCategories(): array
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'category_url' => 'http://laravel.hw/api/v2/categories/' . $category->id
            ];
        }

        return $categories;
    }
}
