<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\Payment;
class DashboardService
{
    public function getOrdersAndAOVByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        return $this->getMonthlyMetrics(
            Order::query(),
            [
                'COUNT(*) as total_orders',
                'SUM(total_amount) as total_revenue',
                'SUM(total_amount) / COUNT(*) as avg_order_value'
            ],
            $from_date,
            $to_date,
            ['ordersData' => 'total_orders', 'aovData' => 'avg_order_value']
        );
    }

    public function getNetRevenueByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        return $this->getMonthlyMetrics(
            Order::query(),
            ['SUM(total_amount) as revenue'],
            $from_date,
            $to_date,
            ['netRevenue' => 'revenue']
        );
    }

    public function getUsersByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        return $this->getMonthlyMetrics(
            User::query(),
            ['COUNT(*) as total_users'],
            $from_date,
            $to_date,
            ['usersData' => 'total_users']
        );
    }

    public function getUserNotificationsByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        return $this->getMonthlyMetrics(
            Notification::query()->where('type', 'user_to_admin'),
            ['COUNT(*) as total'],
            $from_date,
            $to_date,
            ['notificationsData' => 'total']
        );
    }

    public function getDeleveryAndCancellByMonth(?string $from_date = null, ?string $to_date = null): array
    {
        return $this->getMonthlyMetrics(
            Order::query()->whereIn('status', ['delivered', 'cancelled']),
            [
                'SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as total_delivered',
                'SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as total_cancelled'
            ],
            $from_date,
            $to_date,
            ['deliveredData' => 'total_delivered', 'cancelledData' => 'total_cancelled']
        );
    }

    public function getPercentageOfPayment(?string $from_date = null, ?string $to_date = null): array
    {
        $period = $this->getDateRange($from_date, $to_date);

        $paymentMethodsOrder = [
            'COD',
            'VNPay',
            'MoMo',
            'ZaloPay'
        ];

        $totalPayments = Payment::where('status', 'success')
            ->whereBetween('created_at', [$period['start'], $period['end']])
            ->count();

        $percentages = array_fill(0, count($paymentMethodsOrder), 0);

        if ($totalPayments > 0) {
            $paymentCounts = Payment::where('status', 'success')
                ->whereBetween('created_at', [$period['start'], $period['end']])
                ->selectRaw('method, COUNT(*) as count')
                ->groupBy('method')
                ->pluck('count', 'method');

            foreach ($paymentMethodsOrder as $index => $method) {
                if (isset($paymentCounts[$method])) {
                    $percentages[$index] = round(($paymentCounts[$method] / $totalPayments) * 100, 2);
                }
            }
        }

        return [
            'payment_percentages' => $percentages,
            'total_payments' => $totalPayments,
            'period' => [
                'start' => $period['start']->toDateString(),
                'end' => $period['end']->toDateString()
            ]
        ];
    }

    protected function getMonthlyMetrics(
        $query,
        array $selects,
        ?string $from_date,
        ?string $to_date,
        array $outputMappings
    ): array {
        $period = $this->getDateRange($from_date, $to_date);
        $query->whereBetween('created_at', [$period['start'], $period['end']]);

        $results = (clone $query)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, ' . implode(', ', $selects))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(fn($item) => $item->year . '-' . $item->month);

        return $this->formatMonthlyResults($period['start'], $period['end'], $results, $outputMappings);
    }

    protected function getDateRange(?string $from_date, ?string $to_date): array
    {
        if ($from_date && $to_date) {
            return [
                'start' => Carbon::parse($from_date)->startOfMonth(),
                'end' => Carbon::parse($to_date)->endOfMonth()
            ];
        }

        return [
            'start' => Carbon::now()->startOfYear(),
            'end' => Carbon::now()->endOfYear()
        ];
    }

    protected function formatMonthlyResults(Carbon $start, Carbon $end, $results, array $outputMappings): array
    {
        $formatted = ['months' => []];
        foreach ($outputMappings as $key => $field) {
            $formatted[$key] = [];
        }

        $current = $start->copy();
        while ($current <= $end) {
            $key = $current->format('Y-n');
            $formatted['months'][] = $current->format('Y-m');

            foreach ($outputMappings as $outputKey => $field) {
                $formatted[$outputKey][] = $results[$key]->$field ?? 0;
            }

            $current->addMonth();
        }

        return $formatted;
    }
}
