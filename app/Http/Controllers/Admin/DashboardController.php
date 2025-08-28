<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Comment;
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
        $fromDate = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : Carbon::now()->subYear()->startOfDay();

        $toDate = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $totalBooking = Order::whereBetween('created_at', [$fromDate, $toDate])->count();

        $totalComments = Comment::whereBetween('created_at', [$fromDate, $toDate])->where('status', 1)->count();

        $totalEarnings = Order::whereBetween('created_at', [$fromDate, $toDate])->sum('total_amount');

        $topTierProducts = Product::with('variants')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $totalInventory = ProductVariant::whereBetween('created_at', [$fromDate, $toDate])
            ->sum(DB::raw('quantity * import_price'));

        $chartData = $this->dashboardService->getOrdersAndAOVByMonth($fromDate, $toDate);
        $months = $chartData['months'];
        $ordersData = $chartData['ordersData'];
        $aovData = $chartData['aovData'];

        $chartTotalData = $this->dashboardService->getNetRevenueByMonth($fromDate, $toDate);
        $monthsTotal = $chartTotalData['months'];
        $netRevenue = $chartTotalData['netRevenue'];

        $usersTotal = $this->dashboardService->getUsersByMonth($fromDate, $toDate);
        $monthsUser = $usersTotal['months'];
        $usersData = $usersTotal['usersData'];

        $notifyTotal = $this->dashboardService->getUserNotificationsByMonth($fromDate, $toDate);
        $monthsNotify = $notifyTotal['months'];
        $notifyData = $notifyTotal['notificationsData'];

        $deliveryAndCancel = $this->dashboardService->getDeleveryAndCancellByMonth($fromDate, $toDate);
        $monthsDelivery = $deliveryAndCancel['months'];
        $deliveryData = $deliveryAndCancel['deliveredData'];
        $cancelData = $deliveryAndCancel['cancelledData'];

        $paymentTotal = $this->dashboardService->getPercentageOfPayment($fromDate, $toDate);
        $totalPercen = $paymentTotal['payment_percentages'];

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
            'usersData',
            'monthsNotify',
            'notifyData',
            'monthsDelivery',
            'deliveryData',
            'cancelData',
            'totalPercen',
            'totalComments'
        ));
    }
}
