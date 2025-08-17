<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use App\Models\User;

class DashboardService
{
    public function getOrdersAndAOVByMonth($from_date = null, $to_date = null): array
    {
        $query = Order::query();

        // Nếu có from_date và to_date thì lọc theo khoảng ngày
        if ($from_date && $to_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($from_date)->startOfDay(),
                Carbon::parse($to_date)->endOfDay()
            ]);
        } else {
            // Ngược lại lọc theo năm hiện tại
            $year = Carbon::now()->year;
            $query->whereYear('created_at', $year);
        }

        // Đếm số đơn hàng theo tháng
        $orderCounts = (clone $query)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_orders', 'month');

        // Tính AOV (Average Order Value) theo tháng
        $aov = (clone $query)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) / COUNT(*) as avg_order_value')
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

    public function getNetRevenueByMonth($from_date = null, $to_date = null): array
    {
        $query = Order::query();

        if ($from_date && $to_date) {
            // lọc theo ngày đầy đủ
            $query->whereBetween('created_at', [
                Carbon::parse($from_date)->startOfDay(),
                Carbon::parse($to_date)->endOfDay()
            ]);
        } else {
            // mặc định lọc theo năm hiện tại
            $year = Carbon::now()->year;
            $query->whereYear('created_at', $year);
        }

        // Doanh thu thuần theo tháng
        $revenues = (clone $query)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
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

    public function getUsersByMonth($from_date = null, $to_date = null): array
    {
        $query = User::query();

        // Nếu có khoảng ngày thì lọc theo from_date - to_date
        if ($from_date && $to_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($from_date)->startOfDay(),
                Carbon::parse($to_date)->endOfDay()
            ]);
        } else {
            // Nếu không có thì mặc định lấy theo năm hiện tại
            $year = Carbon::now()->year;
            $query->whereYear('created_at', $year);
        }

        // Đếm user đăng ký theo tháng
        $userCounts = (clone $query)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_users')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_users', 'month');

        // Chuẩn hóa 12 tháng
        $months = range(1, 12);
        $usersData = [];

        foreach ($months as $m) {
            $usersData[] = $userCounts[$m] ?? 0;
        }

        return [
            'months' => $months,
            'usersData' => $usersData,
        ];
    }


}
