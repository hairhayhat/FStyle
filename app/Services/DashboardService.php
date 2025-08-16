<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class DashboardService
{
    public function getOrdersAndAOVByMonth($year = null): array
    {
        $year = $year ?? Carbon::now()->year;

        // Đếm số đơn hàng theo tháng
        $orderCounts = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_orders', 'month');

        // Tính AOV (Average Order Value) theo tháng
        $aov = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) / COUNT(*) as avg_order_value')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('avg_order_value', 'month');

        // Chuẩn hóa đủ 12 tháng
        $months = range(1, 12);
        $ordersData = [];
        $aovData = [];

        foreach ($months as $m) {
            $ordersData[] = $orderCounts[$m] ?? 0;
            $aovData[] = $aov[$m] ?? 0;
        }

        return [
            'months' => $months,
            'ordersData' => $ordersData,
            'aovData' => $aovData,
        ];
    }
    public function getNetRevenueByMonth($year = null): array
    {
        $year = $year ?? Carbon::now()->year;

        // Doanh thu thuần (Net Revenue) = tổng total_amount theo tháng
        $revenues = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        $months = range(1, 12);
        $netRevenue = [];

        foreach ($months as $m) {
            $netRevenue[] = $revenues[$m] ?? 0;
        }

        return [
            'months' => $months,
            'netRevenue' => $netRevenue,
        ];
    }
}
