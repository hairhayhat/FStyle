<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;

class UserService
{
    public function topFiveUsersByOrdersCount($fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate   = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfDay();

        return User::withCount(['orders' => function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate])
                      ->whereIn('status', ['delivered', 'rated']);
            }])
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get(['id', 'name']);
    }

    public function topFiveUsersBySpending($fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $toDate   = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfDay();

        return User::withSum(['orders' => function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate])
                      ->whereIn('status', ['delivered', 'rated']);
            }], 'total_amount')
            ->orderByDesc('orders_sum_total_amount')
            ->limit(5)
            ->get(['id', 'name']);
    }
}
