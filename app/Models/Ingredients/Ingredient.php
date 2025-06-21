<?php

namespace App\Models\Ingredients;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients', 'ingredient_id', 'product_id');
    }
}
