<?php

namespace App\Models\Orders;

use App\Models\User;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'reference_id',
        'customer_id',
        'status',
        'total_price',
        'order_at',
    ];

    public $timestamps = false;

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'order_items', 'reference_id', 'product_variant_id', 'reference_id', 'product_variant_id')->withPivot('quantity', 'price');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('order_at', [$startDate, $endDate]);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query) use ($search) {
            return $query->where('reference_id', $search)->orWhereHas('customer', function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhereHas('productVariants.product', function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            });
        });
    }

    public function scopeStatus($query, $status)
    {
        return $query->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        });
    }
}
