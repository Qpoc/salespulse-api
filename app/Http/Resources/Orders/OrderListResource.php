<?php

namespace App\Http\Resources\Orders;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
            'total_price' => $this->productVariants->sum(fn($pv) => $pv->price * $pv->pivot->quantity),
            'date' => Carbon::parse($this->order_at)->format('F j, Y g:i A'),
        ];
    }
}
