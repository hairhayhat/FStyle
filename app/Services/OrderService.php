<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getAverageOrderValue(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date
            ? Carbon::parse($from_date)->startOfDay()
            : Carbon::now()->startOfDay();

        $toDate = $to_date
            ? Carbon::parse($to_date)->endOfDay()
            : Carbon::now()->endOfDay();

        if ($fromDate->equalTo($toDate->copy()->startOfDay())) {
            return $this->groupByHour($fromDate, $toDate);
        }

        if ($fromDate->diffInDays($toDate) <= 31) {
            return $this->groupByDay($fromDate, $toDate);
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return $this->groupByYear($fromDate, $toDate);
        }

        return $this->groupByMonth($fromDate, $toDate);
    }

    private function groupByHour(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('status', ['delivered', 'rated'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('HOUR(created_at) as hour,
                         COUNT(*) as total_orders,
                         SUM(total_amount) as total_amount,
                         ROUND(SUM(total_amount)/COUNT(*), 0) as avg_order_value')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $labels = $totalOrders = $totalAmount = $avgOrderValue = [];

        for ($h = 0; $h < 24; $h++) {
            $labels[] = $h;
            $data = $results->get($h);
            $totalOrders[] = $data ? $data->total_orders : 0;
            $totalAmount[] = $data ? $data->total_amount : 0;
            $avgOrderValue[] = $data ? $data->avg_order_value : 0;
        }

        return [
            'labels' => $labels,
            'total_orders' => $totalOrders,
            'total_amount' => $totalAmount,
            'avg_order_value' => $avgOrderValue,
            'type' => 'hour'
        ];
    }

    private function groupByDay(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('status', ['delivered', 'rated'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('DATE(created_at) as date,
                         COUNT(*) as total_orders,
                         SUM(total_amount) as total_amount,
                         ROUND(SUM(total_amount)/COUNT(*), 0) as avg_order_value')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = $totalOrders = $totalAmount = $avgOrderValue = [];
        $current = $fromDate->copy();

        while ($current <= $toDate) {
            $dateStr = $current->toDateString();
            $labels[] = $dateStr;

            $data = $results->get($dateStr);
            $totalOrders[] = $data ? $data->total_orders : 0;
            $totalAmount[] = $data ? $data->total_amount : 0;
            $avgOrderValue[] = $data ? $data->avg_order_value : 0;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'total_orders' => $totalOrders,
            'total_amount' => $totalAmount,
            'avg_order_value' => $avgOrderValue,
            'type' => 'day'
        ];
    }

    private function groupByMonth(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('status', ['delivered', 'rated'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year,
                         MONTH(created_at) as month,
                         COUNT(*) as total_orders,
                         SUM(total_amount) as total_amount,
                         ROUND(SUM(total_amount)/COUNT(*), 0) as avg_order_value')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = $totalOrders = $totalAmount = $avgOrderValue = [];
        $current = $fromDate->copy()->startOfMonth();

        while ($current <= $toDate) {
            $label = $current->format('Y-m');
            $labels[] = $label;

            $monthData = $results->where('year', $current->year)
                ->where('month', $current->month)
                ->first();

            $totalOrders[] = $monthData ? $monthData->total_orders : 0;
            $totalAmount[] = $monthData ? $monthData->total_amount : 0;
            $avgOrderValue[] = $monthData ? $monthData->avg_order_value : 0;

            $current->addMonth();
        }

        return [
            'labels' => $labels,
            'total_orders' => $totalOrders,
            'total_amount' => $totalAmount,
            'avg_order_value' => $avgOrderValue,
            'type' => 'month'
        ];
    }

    private function groupByYear(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('status', ['delivered', 'rated'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year,
                         COUNT(*) as total_orders,
                         SUM(total_amount) as total_amount,
                         ROUND(SUM(total_amount)/COUNT(*), 0) as avg_order_value')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $labels = $totalOrders = $totalAmount = $avgOrderValue = [];
        $current = $fromDate->copy()->startOfYear();

        while ($current <= $toDate) {
            $year = $current->year;
            $labels[] = $year;

            $data = $results->get($year);
            $totalOrders[] = $data ? $data->total_orders : 0;
            $totalAmount[] = $data ? $data->total_amount : 0;
            $avgOrderValue[] = $data ? $data->avg_order_value : 0;

            $current->addYear();
        }

        return [
            'labels' => $labels,
            'total_orders' => $totalOrders,
            'total_amount' => $totalAmount,
            'avg_order_value' => $avgOrderValue,
            'type' => 'year'
        ];
    }

    public function getTotalDoneOrderAndCancel(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date
            ? Carbon::parse($from_date)->startOfDay()
            : Carbon::now()->startOfDay();

        $toDate = $to_date
            ? Carbon::parse($to_date)->endOfDay()
            : Carbon::now()->endOfDay();

        if ($fromDate->equalTo($toDate->copy()->startOfDay())) {
            return $this->groupByHourFortotal($fromDate, $toDate);
        }

        if ($fromDate->diffInDays($toDate) <= 31) {
            return $this->groupByDayFortotal($fromDate, $toDate);
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return $this->groupByYearFortotal($fromDate, $toDate);
        }

        return $this->groupByMonthFortotal($fromDate, $toDate);
    }

    private function groupByHourFortotal(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('HOUR(created_at) as hour,
                     SUM(CASE WHEN status IN ("delivered", "rated") THEN 1 ELSE 0 END) as done_orders,
                     SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancel_orders')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $labels = $doneOrders = $cancelOrders = [];

        for ($h = 0; $h < 24; $h++) {
            $labels[] = $h;
            $data = $results->get($h);
            $doneOrders[] = $data ? $data->done_orders : 0;
            $cancelOrders[] = $data ? $data->cancel_orders : 0;
        }

        return [
            'labels' => $labels,
            'done_orders' => $doneOrders,
            'cancel_orders' => $cancelOrders,
            'type' => 'hour'
        ];
    }

    private function groupByDayFortotal(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('DATE(created_at) as date,
                     SUM(CASE WHEN status IN ("delivered", "rated") THEN 1 ELSE 0 END) as done_orders,
                     SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancel_orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = $doneOrders = $cancelOrders = [];
        $current = $fromDate->copy();

        while ($current <= $toDate) {
            $dateStr = $current->toDateString();
            $labels[] = $dateStr;

            $data = $results->get($dateStr);
            $doneOrders[] = $data ? $data->done_orders : 0;
            $cancelOrders[] = $data ? $data->cancel_orders : 0;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'done_orders' => $doneOrders,
            'cancel_orders' => $cancelOrders,
            'type' => 'day'
        ];
    }

    private function groupByMonthFortotal(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year,
                     MONTH(created_at) as month,
                     SUM(CASE WHEN status IN ("delivered", "rated") THEN 1 ELSE 0 END) as done_orders,
                     SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancel_orders')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = $doneOrders = $cancelOrders = [];
        $current = $fromDate->copy()->startOfMonth();

        while ($current <= $toDate) {
            $label = $current->format('Y-m');
            $labels[] = $label;

            $monthData = $results->where('year', $current->year)
                ->where('month', $current->month)
                ->first();

            $doneOrders[] = $monthData ? $monthData->done_orders : 0;
            $cancelOrders[] = $monthData ? $monthData->cancel_orders : 0;

            $current->addMonth();
        }

        return [
            'labels' => $labels,
            'done_orders' => $doneOrders,
            'cancel_orders' => $cancelOrders,
            'type' => 'month'
        ];
    }

    private function groupByYearFortotal(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(created_at) as year,
                     SUM(CASE WHEN status IN ("delivered", "rated") THEN 1 ELSE 0 END) as done_orders,
                     SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancel_orders')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $labels = $doneOrders = $cancelOrders = [];
        $current = $fromDate->copy()->startOfYear();

        while ($current <= $toDate) {
            $year = $current->year;
            $labels[] = $year;

            $data = $results->get($year);
            $doneOrders[] = $data ? $data->done_orders : 0;
            $cancelOrders[] = $data ? $data->cancel_orders : 0;

            $current->addYear();
        }

        return [
            'labels' => $labels,
            'done_orders' => $doneOrders,
            'cancel_orders' => $cancelOrders,
            'type' => 'year'
        ];
    }

    public function getPaymentUsageByDay(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date
            ? Carbon::parse($from_date)->startOfDay()
            : Carbon::now()->startOfDay();

        $toDate = $to_date
            ? Carbon::parse($to_date)->endOfDay()
            : Carbon::now()->endOfDay();

        $results = Order::whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->selectRaw('DATE(orders.created_at) as date,
                     payments.method as payment_method,
                     COUNT(orders.id) as orders_count')
            ->groupBy('date', 'payment_method')
            ->orderBy('date')
            ->get();

        $labels = [];
        $methods = $results->pluck('payment_method')->unique()->toArray();
        $series = [];

        foreach ($methods as $method) {
            $series[$method] = [];
        }

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $dateStr = $current->toDateString();
            $labels[] = $dateStr;

            $dayData = $results->where('date', $dateStr);

            $totalOrders = $dayData->sum('orders_count') ?: 1;

            foreach ($methods as $method) {
                $count = $dayData->where('payment_method', $method)->sum('orders_count');
                $series[$method][] = round(($count / $totalOrders) * 100, 2);
            }

            $current->addDay();
        }

        $chartSeries = [];
        foreach ($series as $method => $data) {
            $chartSeries[] = [
                'name' => $method,
                'data' => $data
            ];
        }

        return [
            'labels' => $labels,
            'series' => $chartSeries
        ];
    }

}

