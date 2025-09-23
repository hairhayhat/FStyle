<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserChartController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function index()
    {
        return view('admin.chart.chart-user');
    }

    public function getTopUsersByOrdersCountData(Request $request)
    {
        $topUsers = $this->userService->topFiveUsersByOrdersCount($request->from_date, $request->to_date);

        return response()->json([
            'labels' => $topUsers->pluck('name'),
            'data' => $topUsers->pluck('orders_count'),
        ]);
    }

    public function getTopUsersBySpendingData(Request $request)
    {
        $topUsers = $this->userService->topFiveUsersBySpending($request->from_date, $request->to_date);

        return response()->json([
            'labels' => $topUsers->pluck('name'),
            'data' => $topUsers->pluck('orders_sum_total_amount'),
        ]);
    }
}
