<?php

namespace App\Http\Controllers\Orders;

use App\Models\Orders\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Orders\OrderListResource;
use App\Http\Resources\Orders\OrderDetailResource;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($request->month == 'this month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        return OrderListResource::collection(paginate_if_needed(Order::betweenDates($startDate, $endDate)->search($request->search)->status($request->status)->with(['productVariants.variantLabel', 'customer']), $request->per_page));
    }

    public function show(Request $request)
    {
        return new OrderDetailResource(Order::where('reference_id', $request->reference_id)->with(['productVariants.variantLabel', 'customer'])->firstOrFail());
    }

    public function recent(Request $request)
    {
        return OrderListResource::collection(paginate_if_needed(Order::with(['productVariants.variantLabel'])->orderBy('order_at', 'desc')->limit($request->limit)));
    }
}
