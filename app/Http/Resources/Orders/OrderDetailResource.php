<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->reference_id,
            'items' => $this->productVariants->map(function($pv) {
                return [
                    'product_variant_id' => $pv->product_variant_id,
                    'product_id' => $pv->product_id,
                    'label' => $pv->variantLabel->label,
                    'price' => $pv->price,
                    'quantity' => $pv->pivot->quantity,
                    'sub_total' => $pv->price * $pv->pivot->quantity,
                ];
            }),
            'total_price' => $this->productVariants->sum(fn($pv) => $pv->price * $pv->pivot->quantity),
            'date' => $this->created_at->format('F j, Y g:i A'),
        ];
    }
}
