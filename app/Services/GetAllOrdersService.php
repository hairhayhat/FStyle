<?php

namespace App\Services;
use App\Models\Order;

class GetAllOrdersService
{
    public function getOrdersByStatus()
    {
        $statuses = ['pending', 'confirmed', 'packaging', 'shipped', 'delivered', 'rated', 'cancelled'];

        $ordersCount = [];
        foreach ($statuses as $status) {
            $ordersCount[getStatusName($status)] = Order::where('status', $status)->count();
        }

        return $ordersCount;
    }
}
