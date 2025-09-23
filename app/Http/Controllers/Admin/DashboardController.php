<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Services\DashboardService;
use App\Services\GetProfitService;
use Illuminate\Support\Facades\DB;
use App\Services\GetRevenueService;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Services\GetAllOrdersService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private GetRevenueService $getRevenueService,
        private GetProfitService $getProfitService,
        private GetAllOrdersService $getAllOrdersService,
    ) {
    }

    public function index(Request $request)
    {
        $productCount = Product::count();
        $userCount = User::where('role_id', '!=', 1)->count();
        $commentCount = Comment::count();
        $orderCount = Order::count();


        return view('admin.dashboard.index', compact('productCount', 'userCount', 'commentCount', 'orderCount'));
    }

    public function getRevenue(Request $request)
    {
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');

        $revenueData = $this->getRevenueService->getRevenue($from_date, $to_date);

        return response()->json($revenueData);
    }

    public function getProfit(Request $request)
    {
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');

        $profitData = $this->getProfitService->getProfit($from_date, $to_date);

        return response()->json($profitData);
    }

    public function getOrders(Request $request)
    {
        $ordersDate = $this->getAllOrdersService->getOrdersByStatus();

        return response()->json([
            'labels' => array_keys($ordersDate),
            'data' => array_values($ordersDate)
        ]);
    }
}
