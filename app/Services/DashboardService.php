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

    // Thống kê doanh thu theo tháng
    public function getRevenueByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfMonth() : Carbon::now()->startOfYear();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfMonth() : Carbon::now()->endOfYear();

        $results = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $revenues = [];

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $key = $current->format('Y-n');
            $months[] = $current->format('Y-m');
            
            $monthData = $results->where('year', $current->year)->where('month', $current->month)->first();
            $revenues[] = $monthData ? $monthData->revenue : 0;
            
            $current->addMonth();
        }

        return [
            'months' => $months,
            'revenues' => $revenues
        ];
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

    // Thống kê doanh thu theo ngày
    public function getRevenueByDay(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        $results = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dates = [];
        $revenues = [];
        $orders = [];

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $dateStr = $current->toDateString();
            $dates[] = $dateStr;
            
            $dayData = $results->get($dateStr);
            $revenues[] = $dayData ? $dayData->revenue : 0;
            $orders[] = $dayData ? $dayData->orders : 0;
            
            $current->addDay();
        }

        return [
            'dates' => $dates,
            'revenues' => $revenues,
            'orders' => $orders
        ];
    }

    // Top sản phẩm bán chạy (đơn giản)
    public function getTopProducts(?string $from_date = null, ?string $to_date = null, int $limit = 5)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        // Sử dụng raw query để tính toán số lượng bán
        return Product::with(['category'])
            ->select('products.*')
            ->selectSub(function($query) use ($fromDate, $toDate) {
                $query->selectRaw('COALESCE(SUM(order_details.quantity), 0)')
                    ->from('product_variants')
                    ->join('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->whereBetween('orders.created_at', [$fromDate, $toDate])
                    ->where('orders.status', '!=', 'cancelled');
            }, 'total_sold')
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    // Thống kê trạng thái đơn hàng
    public function getOrderStatusStats(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        $statusCounts = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalOrders = array_sum($statusCounts);

        $statusPercentages = [];
        foreach ($statusCounts as $status => $count) {
            $statusPercentages[$status] = $totalOrders > 0 ? round(($count / $totalOrders) * 100, 2) : 0;
            }

            return [
            'counts' => $statusCounts,
            'percentages' => $statusPercentages,
            'total' => $totalOrders
        ];
    }

    // Thống kê phương thức thanh toán
    public function getPaymentMethodStats(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        $paymentCounts = Payment::where('status', 'success')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('method, COUNT(*) as count')
            ->groupBy('method')
            ->pluck('count', 'method')
            ->toArray();

        $totalPayments = array_sum($paymentCounts);

        $paymentPercentages = [];
        foreach ($paymentCounts as $method => $count) {
            $paymentPercentages[$method] = $totalPayments > 0 ? round(($count / $totalPayments) * 100, 2) : 0;
        }

        return [
            'counts' => $paymentCounts,
            'percentages' => $paymentPercentages,
            'total' => $totalPayments
        ];
    }

    // Thống kê khách hàng theo tháng
    public function getCustomersByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfMonth() : Carbon::now()->startOfYear();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfMonth() : Carbon::now()->endOfYear();

        $results = User::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as customers')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $customers = [];

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $months[] = $current->format('Y-m');
            
            $monthData = $results->where('year', $current->year)->where('month', $current->month)->first();
            $customers[] = $monthData ? $monthData->customers : 0;
            
            $current->addMonth();
        }

        return [
            'months' => $months,
            'customers' => $customers
        ];
    }

    // Bảng đơn hàng gần đây
    public function getRecentOrders(?string $from_date = null, ?string $to_date = null, int $limit = 10)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return Order::with(['user', 'payment'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Bảng khách hàng mới
    public function getRecentCustomers(?string $from_date = null, ?string $to_date = null, int $limit = 10)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return User::whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Bảng sản phẩm mới
    public function getRecentProducts(?string $from_date = null, ?string $to_date = null, int $limit = 10)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return Product::with(['category'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Bảng bình luận mới
    public function getRecentComments(?string $from_date = null, ?string $to_date = null, int $limit = 10)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return Comment::with(['user', 'product'])
            ->where('status', 1)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    // Bảng thống kê theo danh mục
    public function getCategoryTable(?string $from_date = null, ?string $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return Category::withCount(['products as total_products'])
            ->select('categories.*')
            ->selectSub(function($query) use ($fromDate, $toDate) {
                $query->selectRaw('COALESCE(SUM(order_details.quantity), 0)')
                    ->from('products')
                    ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->join('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('products.category_id', 'categories.id')
                    ->whereBetween('orders.created_at', [$fromDate, $toDate])
                    ->where('orders.status', '!=', 'cancelled');
            }, 'total_sold')
            ->selectSub(function($query) use ($fromDate, $toDate) {
                $query->selectRaw('COALESCE(SUM(order_details.quantity * order_details.price), 0)')
                    ->from('products')
                    ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->join('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('products.category_id', 'categories.id')
                    ->whereBetween('orders.created_at', [$fromDate, $toDate])
                    ->where('orders.status', '!=', 'cancelled');
            }, 'total_revenue')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    // Bảng thống kê theo màu sắc
    public function getColorStats(?string $from_date = null, ?string $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return DB::table('colors')
            ->leftJoin('product_variants', 'colors.id', '=', 'product_variants.color_id')
            ->leftJoin('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select('colors.name', 'colors.code')
            ->selectRaw('COALESCE(SUM(order_details.quantity), 0) as total_sold')
            ->selectRaw('COALESCE(SUM(order_details.quantity * order_details.price), 0) as total_revenue')
            ->groupBy('colors.id', 'colors.name', 'colors.code')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    // Bảng thống kê theo kích thước
    public function getSizeStats(?string $from_date = null, ?string $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return DB::table('sizes')
            ->leftJoin('product_variants', 'sizes.id', '=', 'product_variants.size_id')
            ->leftJoin('order_details', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select('sizes.name')
            ->selectRaw('COALESCE(SUM(order_details.quantity), 0) as total_sold')
            ->selectRaw('COALESCE(SUM(order_details.quantity * order_details.price), 0) as total_revenue')
            ->groupBy('sizes.id', 'sizes.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    // Bảng thống kê tồn kho
    public function getInventoryTable()
    {
        return ProductVariant::with(['product', 'color', 'size'])
            ->select('product_variants.*')
            ->selectSub(function($query) {
                $query->selectRaw('COALESCE(SUM(order_details.quantity), 0)')
                    ->from('order_details')
                    ->join('orders', 'order_details.order_id', '=', 'orders.id')
                    ->whereColumn('order_details.product_variant_id', 'product_variants.id')
                    ->where('orders.status', '!=', 'cancelled');
            }, 'total_sold')
            ->orderBy('quantity', 'asc')
            ->get();
    }

    // Bảng thống kê đánh giá sản phẩm
    public function getProductRatings(?string $from_date = null, ?string $to_date = null, int $limit = 10)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subYear()->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        return Product::with(['category'])
            ->select('products.*')
            ->selectSub(function($query) use ($fromDate, $toDate) {
                $query->selectRaw('COALESCE(AVG(comments.rating), 0)')
                    ->from('comments')
                    ->whereColumn('comments.product_id', 'products.id')
                    ->where('comments.status', 1)
                    ->whereBetween('comments.created_at', [$fromDate, $toDate]);
            }, 'avg_rating')
            ->selectSub(function($query) use ($fromDate, $toDate) {
                $query->selectRaw('COALESCE(COUNT(comments.id), 0)')
                    ->from('comments')
                    ->whereColumn('comments.product_id', 'products.id')
                    ->where('comments.status', 1)
                    ->whereBetween('comments.created_at', [$fromDate, $toDate]);
            }, 'total_comments')
            ->having('total_comments', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->limit($limit)
            ->get();
    }

    // Bảng thống kê theo giờ trong ngày
    public function getHourlyTable(?string $from_date = null, ?string $to_date = null)
    {
        $fromDate = $from_date ? Carbon::parse($from_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate = $to_date ? Carbon::parse($to_date)->endOfDay() : Carbon::now()->endOfDay();

        $hourlyData = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $hourlyStats = [];
        for ($i = 0; $i < 24; $i++) {
            $hourData = $hourlyData->get($i);
            $hourlyStats[] = [
                'hour' => sprintf('%02d:00', $i),
                'orders' => $hourData ? $hourData->orders : 0,
                'revenue' => $hourData ? $hourData->revenue : 0,
                'avg_order_value' => $hourData && $hourData->orders > 0 ? $hourData->revenue / $hourData->orders : 0
            ];
        }

        return $hourlyStats;
    }
}