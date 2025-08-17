<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {
    }

    public function index(Request $request)
    {
        $query = Order::query();

        // Lọc theo filter
        if ($request->filter) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;

                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;

                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                    break;

                case 'year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;

                case 'custom':
                    if ($request->from_date && $request->to_date) {
                        $query->whereBetween('created_at', [
                            Carbon::parse($request->from_date)->startOfDay(),
                            Carbon::parse($request->to_date)->endOfDay()
                        ]);
                    }
                    break;
            }
        }

        // Lấy orders sau khi filter
        $orders = $query->get();
        $totalBooking = $orders->count();

        // Top sản phẩm nhiều view
        $topTierProducts = Product::with('variants')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Tính tổng tồn kho
        $totalInventory = 0;
        $products = Product::with('variants')->get();
        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
                $totalInventory += $variant->quantity * $variant->import_price;
            }
        }

        // Tính tổng doanh thu
        $totalEarnings = $orders->sum('total_amount');

        $chartData = $this->dashboardService->getOrdersAndAOVByMonth();
        $months = $chartData['months'];
        $ordersData = $chartData['ordersData'];
        $aovData = $chartData['aovData'];

        $chartTotalData = $this->dashboardService->getNetRevenueByMonth();
        $monthsTotal = $chartTotalData['months'];
        $netRevenue = $chartTotalData['netRevenue'];

        return view('admin.dashboard.index', compact(
            'totalBooking',
            'topTierProducts',
            'totalInventory',
            'totalEarnings',
            'months',
            'ordersData',
            'aovData',
            'monthsTotal',
            'netRevenue',
        ));
    }
}
