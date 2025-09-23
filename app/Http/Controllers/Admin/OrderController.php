<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use App\Events\UpdateOrderStatus;

class OrderController extends Controller
{

    public function __construct(
        private NotificationService $notificationService
    ) {

    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 5);
        $status = $request->get('status', 'pending');
        $payment = $request->get('payment');

        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'packaging' => Order::where('status', 'packaging')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'rated' => Order::where('status', 'rated')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'returned' => Order::where('status', 'returned')->count(),
        ];

        $query = Order::with(['orderDetails.productVariant.product', 'orderVoucher', 'payment']);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($payment) && $payment !== 'all') {
            $query->whereHas('payment', function ($q) use ($payment) {
                $q->where('method', $payment);
            });
        }

        $orders = $query->orderBy('updated_at', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        if ($request->ajax()) {
            $html = view('admin.partials.table-orders', compact('orders'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.order.index', compact('orders', 'statusCounts', 'status', 'payment'));
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
            if ($newStatus === 'confirmed') {
                foreach ($order->orderDetails as $item) {
                    if ($item->productVariant->quantity < $item->quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => "Sản phẩm {$item->productVariant->product->name} ({$item->productVariant->color->name}, {$item->productVariant->size->name}) chỉ còn {$item->productVariant->quantity} trong kho, không đủ để xác nhận đơn hàng."
                        ], 400);
                    }
                    $productVariant = $item->productVariant;
                    $productVariant->quantity -= $item->quantity;
                    $productVariant->save();
                }
            }
            if ($newStatus === 'cancelled') {
                foreach ($order->orderDetails as $item) {
                    $productVariant = $item->productVariant;
                    $productVariant->quantity += $item->quantity;
                    $productVariant->save();
                }
            }

            $order->status = $newStatus;
            $order->save();

            $statusMessages = [
                'confirmed' => "Đơn hàng #{$order->code} đã được xác nhận.",
                'packaging' => "Đơn hàng #{$order->code} đang được đóng gói.",
                'shipped' => "Đơn hàng #{$order->code} đã được gửi đi.",
                'delivered' => "Đơn hàng #{$order->code} đã được giao thành công.",
                'cancelled' => "Đơn hàng #{$order->code} đã bị hủy.",
                'returned' => "Đơn hàng #{$order->code} đã được trả lại.",
            ];

            $user = $order->user;
            if (isset($statusMessages[$newStatus])) {
                $this->notificationService->notifyUser(
                    $user,
                    'Cập nhật đơn hàng',
                    $statusMessages[$newStatus],
                    "/client/checkout/{$order->code}"
                );
            }

            broadcast(new UpdateOrderStatus($order->fresh()))->toOthers();

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
            'rated' => 'Đã đánh giá',
            'cancelled' => 'Đã hủy',
            'returned' => 'Đã trả hàng'
        ];

        return $statusNames[$status] ?? $status;
    }

    public function detail($code)
    {
        $order = Order::with('orderDetails.productVariant.product', 'shippingAddress', 'orderVoucher')
            ->where('code', $code)
            ->firstOrFail();

        return view('admin.order.detail', compact('order'));
    }
}
