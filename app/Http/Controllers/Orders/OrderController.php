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
        return OrderListResource::collection(paginate_if_needed(Order::with(['productVariants.variantLabel']), $request->per_page));
    }

    public function show(Request $request)
    {
        return new OrderDetailResource(Order::where('reference_id', $request->reference_id)->with(['productVariants.variantLabel'])->firstOrFail());
    }

    public function recent(Request $request)
    {
        return OrderListResource::collection(paginate_if_needed(Order::with(['productVariants.variantLabel'])->orderBy('order_at', 'desc')->limit($request->limit)));
    }
}
