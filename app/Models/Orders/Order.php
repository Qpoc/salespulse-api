<?php

namespace App\Models\Orders;

use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'reference_id',
        'order_at',
    ];

    public $timestamps = false;

    public function productVariants(){
        return $this->belongsToMany(ProductVariant::class, 'order_items', 'reference_id', 'product_variant_id', 'reference_id', 'product_variant_id')->withPivot('quantity');
    }
}
