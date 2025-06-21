<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->product_id,
            'name' => $this->name,
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category->name,
            ],
        ];
    }
}
