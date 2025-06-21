<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Models\Products\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductListResource;
use App\Http\Resources\Products\ProductDetailResource;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return ProductListResource::collection(paginate_if_needed(Product::query(), $request->per_page));
    }

    public function show(Request $request)
    {
        return new ProductDetailResource(Product::where('product_id', $request->product_id)->firstOrFail());
    }
}
