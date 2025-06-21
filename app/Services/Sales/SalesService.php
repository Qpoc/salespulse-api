<?php

namespace App\Services\Sales;

use Illuminate\Support\Collection;
use App\Repositories\Contracts\Sales\SalesRepositoryInterface;

class SalesService
{
    protected $salesRepository;

    public function __construct(SalesRepositoryInterface $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    public function getSummary(): array
    {
        $sales = $this->salesRepository->getSalesWithVariants();
        $variantSales = $this->calculateVariantSales($sales);

        return [
            'total_sales' => $this->salesRepository->getTotalSalesCount(),
            'total_revenue' => $this->salesRepository->getTotalRevenue(),
            'average_sale_value' => $this->calculateAverageSaleValue(),
            'most_popular_variant' => $this->getMostPopularVariant($variantSales),
            'top_category' => $this->getTopCategory($variantSales),
        ];
    }

    public function getRevenueOverTime(int $limit): Collection
    {
        return $this->salesRepository->getRevenueOverTime($limit);
    }

    public function getTopSellingProducts(int $limit): Collection
    {
        return $this->salesRepository->getTopSellingProducts($limit);
    }

    public function getTopSellingVariants(int $limit): Collection
    {
        return $this->salesRepository->getTopSellingVariants($limit)
            ->map(function ($variant) {
                $quantitySold = $variant->orders->sum('pivot.quantity');

                return [
                    'product_id' => $variant->product->id,
                    'product_name' => $variant->product->name,
                    'variant' => $variant->variantLabel?->label, // PHP 8 nullsafe operator
                    'variant_price' => (float) $variant->price,
                    'quantity_sold' => $quantitySold,
                    'total_sales' => (float) $variant->price * $quantitySold,
                ];
            });
    }

    protected function calculateVariantSales($sales): array
    {
        $variantSales = [];

        foreach ($sales as $sale) {
            foreach ($sale->productVariants as $variant) {
                $variantId = $variant->id;

                if (!isset($variantSales[$variantId])) {
                    $variantSales[$variantId] = [
                        'variant' => $variant,
                        'quantity' => 0
                    ];
                }

                $variantSales[$variantId]['quantity'] += $variant->pivot->quantity;
            }
        }

        return $variantSales;
    }

    protected function calculateAverageSaleValue(): float
    {
        $totalSales = $this->salesRepository->getTotalSalesCount();
        return $totalSales > 0
            ? round($this->salesRepository->getTotalRevenue() / $totalSales, 2)
            : 0;
    }

    protected function getMostPopularVariant(array $variantSales): ?array
    {
        return collect($variantSales)
            ->sortByDesc('quantity')
            ->first();
    }

    protected function getTopCategory(array $variantSales): string
    {
        $categoryCounts = [];

        foreach ($variantSales as $sale) {
            $category = $sale['variant']->product->category->name ?? 'Unknown';
            $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + $sale['quantity'];
        }

        arsort($categoryCounts);

        return array_key_first($categoryCounts) ?? 'Unknown';
    }
}
