<?php

if (!function_exists('getStatusName')) {
    function getStatusName($status)
    {
        $statusNames = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'packaging' => 'Đang đóng gói',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'rated' => 'Đã đánh giá',
            'cancelled' => 'Đã bị hủy',
            'returned' => 'Đã trả hàng'
        ];

        return $statusNames[$status] ?? $status;
    }
}
