<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\OrderDetail;

class GetProfitService
{
    public function getProfit(?string $from_date = null, ?string $to_date = null): array
    {
        $fromDate = $from_date
            ? Carbon::parse($from_date)->startOfDay()
            : Carbon::now()->startOfDay();

        $toDate = $to_date
            ? Carbon::parse($to_date)->endOfDay()
            : Carbon::now()->endOfDay();


        if ($fromDate->equalTo($toDate->copy()->startOfDay())) {
            return $this->getProfitByHour($fromDate, $toDate);
        }

        if ($fromDate->diffInDays($toDate) <= 31) {
            return $this->getProfitByDay($fromDate, $toDate);
        }

        if ($fromDate->diffInMonths($toDate) > 12) {
            return $this->getProfitByYear($fromDate, $toDate);
        }

        return $this->getProfitByMonth($fromDate, $toDate);
    }

    private function getProfitByHour(Carbon $fromDate, Carbon $toDate): array
    {
        $results = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->selectRaw('HOUR(orders.created_at) as hour,
                         SUM(order_details.price * order_details.quantity) as revenue,
                         SUM(product_variants.import_price * order_details.quantity) as cost,
                         SUM((order_details.price - product_variants.import_price) * order_details.quantity) as profit')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $hours = [];
        $profits = [];
        $revenues = [];
        $costs = [];

        for ($h = 0; $h < 24; $h++) {
            $hours[] = $h;
            $data = $results->get($h);
            $profits[] = $data ? $data->profit : 0;
            $revenues[] = $data ? $data->revenue : 0;
            $costs[] = $data ? $data->cost : 0;
        }

        return [
            'labels' => $hours,
            'profits' => $profits,
            'revenues' => $revenues,
            'costs' => $costs,
            'type' => 'hour'
        ];
    }

    private function getProfitByDay(Carbon $fromDate, Carbon $toDate): array
    {
        $results = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->selectRaw('DATE(orders.created_at) as date,
                         SUM(order_details.price * order_details.quantity) as revenue,
                         SUM(product_variants.import_price * order_details.quantity) as cost,
                         SUM((order_details.price - product_variants.import_price) * order_details.quantity) as profit')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dates = [];
        $profits = [];
        $revenues = [];
        $costs = [];

        $current = $fromDate->copy();
        while ($current <= $toDate) {
            $dateStr = $current->toDateString();
            $dates[] = $dateStr;
            $dayData = $results->get($dateStr);

            $profits[] = $dayData ? $dayData->profit : 0;
            $revenues[] = $dayData ? $dayData->revenue : 0;
            $costs[] = $dayData ? $dayData->cost : 0;

            $current->addDay();
        }

        return [
            'labels' => $dates,
            'profits' => $profits,
            'revenues' => $revenues,
            'costs' => $costs,
            'type' => 'day'
        ];
    }

    private function getProfitByMonth(Carbon $fromDate, Carbon $toDate): array
    {
        $results = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(orders.created_at) as year,
                         MONTH(orders.created_at) as month,
                         SUM(order_details.price * order_details.quantity) as revenue,
                         SUM(product_variants.import_price * order_details.quantity) as cost,
                         SUM((order_details.price - product_variants.import_price) * order_details.quantity) as profit')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $profits = [];
        $revenues = [];
        $costs = [];

        $current = $fromDate->copy()->startOfMonth();
        while ($current <= $toDate) {
            $months[] = $current->format('Y-m');
            $monthData = $results
                ->where('year', $current->year)
                ->where('month', $current->month)
                ->first();
            $profits[] = $monthData ? $monthData->profit : 0;
            $revenues[] = $monthData ? $monthData->revenue : 0;
            $costs[] = $monthData ? $monthData->cost : 0;

            $current->addMonth();
        }

        return [
            'labels' => $months,
            'profits' => $profits,
            'revenues' => $revenues,
            'costs' => $costs,
            'type' => 'month'
        ];
    }

    private function getProfitByYear(Carbon $fromDate, Carbon $toDate): array
    {
        $results = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_details.product_variant_id')
            ->whereIn('orders.status', ['delivered', 'rated'])
            ->whereBetween('orders.created_at', [$fromDate, $toDate])
            ->selectRaw('YEAR(orders.created_at) as year,
                         SUM(order_details.price * order_details.quantity) as revenue,
                         SUM(product_variants.import_price * order_details.quantity) as cost,
                         SUM((order_details.price - product_variants.import_price) * order_details.quantity) as profit')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $years = [];
        $profits = [];
        $revenues = [];
        $costs = [];

        $current = $fromDate->copy()->startOfYear();
        while ($current <= $toDate) {
            $y = $current->year;
            $years[] = $y;
            $data = $results->get($y);

            $profits[] = $data ? $data->profit : 0;
            $revenues[] = $data ? $data->revenue : 0;
            $costs[] = $data ? $data->cost : 0;

            $current->addYear();
        }

        return [
            'labels' => $years,
            'profits' => $profits,
            'revenues' => $revenues,
            'costs' => $costs,
            'type' => 'year'
        ];
    }
}
