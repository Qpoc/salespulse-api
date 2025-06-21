<?php

namespace App\Repositories\Eloquent\Sales;

use App\Models\Orders\Order;
use App\Models\Products\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Products\ProductVariant;
use App\Repositories\Contracts\Sales\SalesRepositoryInterface;

class SalesRepository implements SalesRepositoryInterface
{
    public function getRevenueOverTime(int $limit): Collection
    {
        return DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.product_variant_id')
            ->join('orders', 'order_items.reference_id', '=', 'orders.reference_id')
            ->selectRaw('DATE(orders.order_at) as date')
            ->selectRaw('SUM(order_items.quantity * product_variants.price) as total_revenue')
            ->groupBy('date')
            ->orderByDesc('date')
            ->limit($limit)
            ->get()->sortBy('date')->values();
    }

    public function getTopSellingProducts(int $limit): Collection
    {
        return Product::select('products.product_id', 'products.name')
            ->join('product_variants', 'products.product_id', '=', 'product_variants.product_id')
            ->join('order_items', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->selectRaw('SUM(order_items.quantity) as quantity_sold')
            ->selectRaw('SUM(order_items.quantity * product_variants.price) as total_sales')
            ->groupBy('products.product_id', 'products.name')
            ->orderByDesc('quantity_sold')
            ->limit($limit)
            ->get();
    }

    public function getTopSellingVariants(int $limit): Collection
    {
        return  ProductVariant::with(['product', 'variantLabel'])
            ->withSum('orders as total_quantity', 'order_items.quantity') // alias pivot sum
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }

    public function getSalesWithVariants(): Collection
    {
        return Order::with([
            'productVariants.variantLabel',
            'productVariants.product.category'
        ])->get();
    }

    public function getTotalSalesCount(): int
    {
        return Order::count();
    }

    public function getTotalRevenue(): float
    {
        return Order::with('productVariants')->get()
            ->sum(function ($sale) {
                return $sale->productVariants->sum(function ($variant) {
                    return $variant->price * $variant->pivot->quantity;
                });
            });
    }
}
