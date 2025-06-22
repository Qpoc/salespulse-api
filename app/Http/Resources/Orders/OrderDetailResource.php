<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'customer' => $this->customer,
            'total_price' => (float) $this->total_price,
            'items' => $this->productVariants->map(function ($variant) {
                return [
                    'product' => [
                        'name' => $variant->product->name,
                    ],
                    'variant' => [
                        'id' => $variant->product_variant_id,
                        'label' => $variant->variantLabel->label,
                    ],
                    'price' => (float) $variant->price,
                    'quantity' => $variant->pivot->quantity,
                ];
            }),
            'status' => $this->getStatus($this->status),
            'date' => Carbon::parse($this->order_at)->format('F j, Y g:i A'),
            'timeline' => [
                [
                    'status' => $this->getStatus($this->status),
                    'timestamp' => Carbon::parse($this->order_at)->format('F j, Y g:i A'),
                    'description' => 'Order is being prepared',
                ]
            ],
        ];
    }

    protected function getStatus(int $status): string
    {
        return match ($status) {
            1 => 'Pending',
            2 => 'Processing',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Cancelled',
        };
    }
}
