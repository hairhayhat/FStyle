<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {
    }

public function index(Request $request)
{
    $query = Order::query();

    $fromDate = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : null;
    $toDate   = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : null;

    // Ưu tiên lọc theo khoảng ngày nếu có
    if ($fromDate && $toDate) {
        $query->whereBetween('created_at', [$fromDate, $toDate]);
    } elseif ($request->filter) {
        // Nếu không có from/to date thì dùng filter nhanh
        switch ($request->filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;

            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;

            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
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

    // Tính tổng tồn kho (tối ưu bằng query thẳng DB)
    $totalInventory = ProductVariant::whereBetween('created_at', [$fromDate, $toDate])
    ->sum(DB::raw('quantity * import_price'));

    // Tính tổng doanh thu (tối ưu không cần load all records)
    $totalEarnings = (clone $query)->sum('total_amount');

    // Chart orders + AOV
    $chartData = $this->dashboardService->getOrdersAndAOVByMonth($fromDate, $toDate);
    $months = $chartData['months'];
    $ordersData = $chartData['ordersData'];
    $aovData = $chartData['aovData'];

    // Chart doanh thu
    $chartTotalData = $this->dashboardService->getNetRevenueByMonth($fromDate, $toDate);
    $monthsTotal = $chartTotalData['months'];
    $netRevenue = $chartTotalData['netRevenue'];

    // Chart user
    $usersTotal = $this->dashboardService->getUsersByMonth($fromDate, $toDate);
    $monthsUser = $usersTotal['months'];
    $usersData = $usersTotal['usersData'];

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
        'monthsUser',
        'usersData'
    ));
}

}
