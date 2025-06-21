<?php

namespace App\Models\Products;

use App\Models\Orders\Order;
use App\Models\Products\Product;
use App\Models\Variants\VariantLabel;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_variant_id',
        'product_id',
        'variant_label_id',
        'price',
    ];

    public function variantLabel(){
        return $this->belongsTo(VariantLabel::class);
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_items', 'product_variant_id', 'reference_id', 'product_variant_id', 'reference_id')->withPivot('quantity');
    }
}
