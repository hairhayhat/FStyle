<?php // Helper chuyển trạng thái (slug) sang tên hiển thị tiếng Việt

if (!function_exists('getStatusName')) { // Tránh định nghĩa lại nếu đã tồn tại
    function getStatusName($status) // Nhận vào mã trạng thái (pending, confirmed...)
    {
        $statusNames = [ // Bảng ánh xạ mã -> tên hiển thị
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'packaging' => 'Đang đóng gói',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã bị hủy',
            'returned' => 'Đã trả hàng'
        ];

        return $statusNames[$status] ?? $status; // Nếu không khớp, trả lại chính mã trạng thái
    }
}
