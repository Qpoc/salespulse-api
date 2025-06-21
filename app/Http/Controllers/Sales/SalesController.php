<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Sales\SalesService;

class SalesController extends Controller
{

    protected $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    public function summary()
    {
        $summary = $this->salesService->getSummary();

        return response()->json($summary);
    }

    public function topProducts(Request $request)
    {
        $variants = $this->salesService->getTopSellingProducts($request->limit);

        return response()->json($variants);
    }

    public function topVariants(Request $request)
    {
        $variants = $this->salesService->getTopSellingVariants($request->limit);
        return response()->json($variants);
    }

    public function revenueOverTime(Request $request)
    {
        $revenueOverTime = $this->salesService->getRevenueOverTime($request->limit);
        return response()->json($revenueOverTime);
    }
}
