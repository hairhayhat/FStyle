<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;

class GetRevenueService
{
    public function getRevenue(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date
            ? Carbon::parse($from_date)->startOfDay()
            : Carbon::now()->startOfDay();

        $toDate = $to_date
            ? Carbon::parse($to_date)->endOfDay()
            : Carbon::now()->endOfDay();

        if ($fromDate->equalTo($toDate->copy()->startOfDay())) {
            return $this->getRevenueByHour($fromDate, $toDate);
        }

        if ($fromDate->diffInDays($toDate) <= 31) {
            return $this->getRevenueByDay($fromDate, $toDate);
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return $this->getRevenueByYear($fromDate, $toDate);
        }

        return $this->getRevenueByMonth($fromDate, $toDate);
    }

    private function getRevenueByHour(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->leftJoin('order_vouchers', 'orders.id', '=', 'order_vouchers.order_id')
            ->selectRaw('HOUR(orders.created_at) as hour,
                         SUM(orders.total_amount) as net_revenue,
                         SUM(orders.total_amount + IFNULL(order_vouchers.discount, 0)) as gross_revenue,
                         COUNT(orders.id) as orders')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $hours = [];
        $grossRevenues = [];
        $netRevenues = [];
        $orders = [];

        for ($h = 0; $h < 24; $h++) {
            $hours[] = $h;
            $data = $results->get($h);
            $grossRevenues[] = $data ? $data->gross_revenue : 0;
            $netRevenues[] = $data ? $data->net_revenue : 0;
            $orders[] = $data ? $data->orders : 0;
        }

        return [
            'labels' => $hours,
            'gross_revenues' => $grossRevenues,
            'net_revenues' => $netRevenues,
            'orders' => $orders,
            'type' => 'hour'
        ];
    }

    private function getRevenueByDay(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->leftJoin('order_vouchers', 'orders.id', '=', 'order_vouchers.order_id')
            ->selectRaw('DATE(orders.created_at) as date,
                         SUM(orders.total_amount) as net_revenue,
                         SUM(orders.total_amount + IFNULL(order_vouchers.discount, 0)) as gross_revenue,
                         COUNT(orders.id) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dates = [];
        $grossRevenues = [];
        $netRevenues = [];
        $orders = [];

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $dateStr = $current->toDateString();
            $dates[] = $dateStr;

            $dayData = $results->get($dateStr);
            $grossRevenues[] = $dayData ? $dayData->gross_revenue : 0;
            $netRevenues[] = $dayData ? $dayData->net_revenue : 0;
            $orders[] = $dayData ? $dayData->orders : 0;

            $current->addDay();
        }

        return [
            'labels' => $dates,
            'gross_revenues' => $grossRevenues,
            'net_revenues' => $netRevenues,
            'orders' => $orders,
            'type' => 'day'
        ];
    }

    private function getRevenueByMonth(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->leftJoin('order_vouchers', 'orders.id', '=', 'order_vouchers.order_id')
            ->selectRaw('YEAR(orders.created_at) as year,
                         MONTH(orders.created_at) as month,
                         SUM(orders.total_amount) as net_revenue,
                         SUM(orders.total_amount + IFNULL(order_vouchers.discount, 0)) as gross_revenue,
                         COUNT(orders.id) as orders')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $grossRevenues = [];
        $netRevenues = [];
        $orders = [];

        $current = $fromDate->copy()->startOfMonth();
        while ($current <= $toDate) {
            $months[] = $current->format('Y-m');

            $monthData = $results
                ->where('year', $current->year)
                ->where('month', $current->month)
                ->first();

            $grossRevenues[] = $monthData ? $monthData->gross_revenue : 0;
            $netRevenues[] = $monthData ? $monthData->net_revenue : 0;
            $orders[] = $monthData ? $monthData->orders : 0;

            $current->addMonth();
        }

        return [
            'labels' => $months,
            'gross_revenues' => $grossRevenues,
            'net_revenues' => $netRevenues,
            'orders' => $orders,
            'type' => 'month'
        ];
    }

    private function getRevenueByYear(Carbon $fromDate, Carbon $toDate): array
    {
        $results = Order::whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->leftJoin('order_vouchers', 'orders.id', '=', 'order_vouchers.order_id')
            ->selectRaw('YEAR(orders.created_at) as year,
                         SUM(orders.total_amount) as net_revenue,
                         SUM(orders.total_amount + IFNULL(order_vouchers.discount, 0)) as gross_revenue,
                         COUNT(orders.id) as orders')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $years = [];
        $grossRevenues = [];
        $netRevenues = [];
        $orders = [];

        $current = $fromDate->copy()->startOfYear();
        while ($current <= $toDate) {
            $y = $current->year;
            $years[] = $y;

            $data = $results->get($y);
            $grossRevenues[] = $data ? $data->gross_revenue : 0;
            $netRevenues[] = $data ? $data->net_revenue : 0;
            $orders[] = $data ? $data->orders : 0;

            $current->addYear();
        }

        return [
            'labels' => $years,
            'gross_revenues' => $grossRevenues,
            'net_revenues' => $netRevenues,
            'orders' => $orders,
            'type' => 'year'
        ];
    }
}
