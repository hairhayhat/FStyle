<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    // Thống kê tổng quan đơn giản
    public function getOverviewStats(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return [
            'total_revenue' => $this->getTotalRevenue($fromDate, $toDate),
            'total_orders' => $this->getTotalOrders($fromDate, $toDate),
            'total_customers' => $this->getTotalCustomers($fromDate, $toDate),
            'total_products' => $this->getTotalProducts($fromDate, $toDate),
            'avg_order_value' => $this->getAverageOrderValue($fromDate, $toDate),
            'total_categories' => Category::count(),
            'total_inventory_value' => $this->getInventoryValue(),
            'total_comments' => $this->getTotalComments($fromDate, $toDate),
        ];
    }

    // Tổng doanh thu
    private function getTotalRevenue(Carbon $fromDate, Carbon $toDate): float
    {
        return Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->sum('total_amount') ?? 0;
    }

    // Tổng đơn hàng
    private function getTotalOrders(Carbon $fromDate, Carbon $toDate): int
    {
        return Order::whereBetween('created_at', [$fromDate, $toDate])->count();
    }

    // Tổng khách hàng mới
    private function getTotalCustomers(Carbon $fromDate, Carbon $toDate): int
    {
        return User::whereBetween('created_at', [$fromDate, $toDate])->count();
    }

    // Tổng sản phẩm mới
    private function getTotalProducts(Carbon $fromDate, Carbon $toDate): int
    {
        return Product::whereBetween('created_at', [$fromDate, $toDate])->count();
    }

    // Giá trị đơn hàng trung bình
    private function getAverageOrderValue(Carbon $fromDate, Carbon $toDate): float
    {
        return Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->avg('total_amount') ?? 0;
    }

    // Giá trị tồn kho
    private function getInventoryValue(): float
    {
        return ProductVariant::sum(DB::raw('quantity * import_price')) ?? 0;
    }

    // Tổng bình luận
    private function getTotalComments(Carbon $fromDate, Carbon $toDate): int
    {
        return Comment::where('status', 1)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();
    }

    // Thống kê đơn hàng theo tháng
    public function getOrdersByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfMonth() : Carbon::now()->startOfYear();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfMonth() : Carbon::now()->endOfYear();

        $results = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as orders')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $orders = [];

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $key = $current->format('Y-n');
            $months[] = $current->format('Y-m');

            $monthData = $results->where('year', $current->year)->where('month', $current->month)->first();
            $orders[] = $monthData ? $monthData->orders : 0;

            $current->addMonth();
        }

        return [
            'months' => $months,
            'orders' => $orders
        ];
    }
}
