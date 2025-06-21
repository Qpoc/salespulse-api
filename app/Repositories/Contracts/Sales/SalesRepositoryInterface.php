<?php
namespace App\Repositories\Contracts\Sales;

use Illuminate\Support\Collection;

interface SalesRepositoryInterface
{
    public function getRevenueOverTime(int $limit): Collection;
    public function getTopSellingProducts(int $limit): Collection;
    public function getTopSellingVariants(int $limit): Collection;
    public function getSalesWithVariants(): Collection;
    public function getTotalSalesCount(): int;
    public function getTotalRevenue(): float;
}
