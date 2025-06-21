<?php

namespace App\Models\Products;

use App\Models\Categories\Category;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_id',
        'name',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
