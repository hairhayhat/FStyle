<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {
    }

    public function index(Request $request)
    {
        $query = Order::query();

        $fromDate = $request->from_date ? Carbon::parse($request->from_date)->startOfDay() : Carbon::now()->subYear()->format('Y-m-d');
        $toDate = $request->to_date ? Carbon::parse($request->to_date)->endOfDay() : Carbon::now()->format('Y-m-d');

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($request->filter) {
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

        $orders = $query->get();
        $totalBooking = $orders->count();

        $topTierProducts = Product::with('variants')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $totalInventory = ProductVariant::whereBetween('created_at', [$fromDate, $toDate])
            ->sum(DB::raw('quantity * import_price'));

        $totalEarnings = (clone $query)->sum('total_amount');

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
