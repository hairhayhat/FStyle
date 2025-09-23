<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderChartController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {

    }
    public function index()
    {
        return view('admin.chart.chart-order');
    }

    public function getAverageOrderValue(Request $request)
    {
        $data = $this->orderService->getAverageOrderValue($request->from_date, $request->to_date);
        return response()->json($data);
    }

    public function getDoneAndCancelledOrders(Request $request)
    {
        $data = $this->orderService->getTotalDoneOrderAndCancel($request->from_date, $request->to_date);
        return response()->json($data);
    }

    public function getPaymentMethodDistribution(Request $request)
    {
        $data = $this->orderService->getPaymentUsageByDay($request->from_date, $request->to_date);
        return response()->json($data);
    }
}
