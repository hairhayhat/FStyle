<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderDetails.productVariant.product')->paginate(10);

        return view('admin.order.index', compact('orders'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        $validStatuses = [
            'pending',
            'confirmed',
            'packaging',
            'shipped',
            'delivered',
            'cancelled',
            'returned'
        ];

        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', $validStatuses)]
        ]);


        $currentStatus = $order->status;
        $newStatus = $request->status;

        if (!$this->isValidStatusTransition($currentStatus, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chuyển từ trạng thái ' .
                    $this->getStatusName($currentStatus) . ' sang ' .
                    $this->getStatusName($newStatus)
            ], 422);
        }

        try {
            $order->status = $newStatus;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'data' => [
                    'new_status' => $newStatus,
                    'status_name' => $this->getStatusName($newStatus)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function isValidStatusTransition($current, $new)
    {
        if ($current === $new) {
            return true;
        }

        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['packaging', 'cancelled'],
            'packaging' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'returned'],
            'delivered' => ['returned'],
            'cancelled' => [],
            'returned' => []
        ];

        return in_array($new, $allowedTransitions[$current] ?? []);
    }

    protected function getStatusName($status)
    {
        $statusNames = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'packaging' => 'Đang đóng gói',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng'
        ];

        return $statusNames[$status] ?? $status;
    }
}
