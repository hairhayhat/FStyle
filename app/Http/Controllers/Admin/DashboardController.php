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
        // Thống kê tổng quan
        $overviewStats = $this->dashboardService->getOverviewStats($request->from_date, $request->to_date);

        // Biểu đồ doanh thu theo tháng
        $revenueChart = $this->dashboardService->getRevenueByMonth($request->from_date, $request->to_date);

        // Biểu đồ đơn hàng theo tháng
        $ordersChart = $this->dashboardService->getOrdersByMonth($request->from_date, $request->to_date);

        // Biểu đồ doanh thu theo ngày
        $dailyChart = $this->dashboardService->getRevenueByDay($request->from_date, $request->to_date);

        // Top sản phẩm bán chạy
        $topProducts = $this->dashboardService->getTopProducts($request->from_date, $request->to_date, 5);

        // Thống kê trạng thái đơn hàng
        $orderStatusStats = $this->dashboardService->getOrderStatusStats($request->from_date, $request->to_date);

        // Thống kê phương thức thanh toán
        $paymentStats = $this->dashboardService->getPaymentMethodStats($request->from_date, $request->to_date);

        // Thống kê khách hàng theo tháng
        $customersChart = $this->dashboardService->getCustomersByMonth($request->from_date, $request->to_date);

        // Dữ liệu cho các bảng mới
        $recentOrders = $this->dashboardService->getRecentOrders($request->from_date, $request->to_date, 10);
        $recentCustomers = $this->dashboardService->getRecentCustomers($request->from_date, $request->to_date, 10);
        $recentProducts = $this->dashboardService->getRecentProducts($request->from_date, $request->to_date, 10);
        $recentComments = $this->dashboardService->getRecentComments($request->from_date, $request->to_date, 10);
        $categoryTable = $this->dashboardService->getCategoryTable($request->from_date, $request->to_date);
        $colorStats = $this->dashboardService->getColorStats($request->from_date, $request->to_date);
        $sizeStats = $this->dashboardService->getSizeStats($request->from_date, $request->to_date);
        $inventoryTable = $this->dashboardService->getInventoryTable();
        $productRatings = $this->dashboardService->getProductRatings($request->from_date, $request->to_date, 10);
        $hourlyTable = $this->dashboardService->getHourlyTable($request->from_date, $request->to_date);

        return view('admin.dashboard.index', compact(
            'overviewStats',
            'revenueChart',
            'ordersChart',
            'dailyChart',
            'topProducts',
            'orderStatusStats',
            'paymentStats',
            'customersChart',
            'recentOrders',
            'recentCustomers',
            'recentProducts',
            'recentComments',
            'categoryTable',
            'colorStats',
            'sizeStats',
            'inventoryTable',
            'productRatings',
            'hourlyTable'
        ));
    }
}
