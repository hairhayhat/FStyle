<?php

namespace App\Http\Controllers\Client;

use App\Events\UpdateOrderStatus;
use App\Models\Order;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\OrderDetail;
use App\Models\OrderVoucher;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class CheckoutController extends Controller
{
    public function __construct
    (
        private NotificationService $notificationService,
    ) {

    }
    public function checkout(Request $request)
    {
        $type = $request->get('type');

        if ($type === 'buy_now') {
            $buyNow = session('buy_now');
            if (!$buyNow) {
                return redirect()->route('client.welcome')
                    ->withErrors('Không có sản phẩm nào để mua ngay.');
            }

            $variant = ProductVariant::with('product', 'color', 'size')
                ->find($buyNow['product_variant_id']);
            if ($variant->quantity < $buyNow['quantity']) {
                return redirect()->back()
                    ->with('error', 'Sản phẩm không đủ số lượng để mua ngay.');
            }
            if ($variant->product->status === 'discontinued') {
                return redirect()->back()
                    ->with('error', 'Sản phẩm "' . $variant->product->name . '" đã tạm thời ngừng bán.');
            }
            $cartItems = collect([
                (object) [
                    'productVariant' => $variant,
                    'price' => $variant->sale_price ?? $variant->price ?? 0,
                    'quantity' => $buyNow['quantity'],
                    'color' => $variant->color->name,
                    'size' => $variant->size->name
                ]
            ]);
        } elseif ($type === 'cart') {
            $cart = Auth::user()->cart;
            $selectedIds = $request->input('cart_items', []);

            if (empty($selectedIds)) {
                return redirect()->back()->withErrors('Bạn chưa chọn sản phẩm nào để thanh toán.');
            }

            $cartItems = $cart->details()
                ->with('productVariant.product', 'productVariant.color', 'productVariant.size')
                ->whereIn('id', $selectedIds)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'productVariant' => $item->productVariant,
                        'price' => $item->productVariant->sale_price ?? $item->productVariant->price ?? 0,
                        'quantity' => $item->quantity,
                        'color' => $item->color,
                        'size' => $item->size
                    ];
                });
            foreach ($cartItems as $item) {
                if ($item->productVariant->quantity < $item->quantity) {
                    return redirect()->back()
                        ->with('error', 'Sản phẩm ' . $item->productVariant->product->name . ' không đủ số lượng để mua.');
                }
                if ($item->productVariant->product->status === 'discontinued') {
                    return redirect()->back()
                        ->with('error', 'Sản phẩm "' . $item->productVariant->product->name . '" đã tạm thời ngừng bán.');
                }
            }
        } else {
            return redirect()->route('client.welcome');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $addresses = Auth::user()->addresses;
        $vouchers = Voucher::all();

        return view('client.checkout', compact('cartItems', 'total', 'addresses', 'vouchers'));
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 5);
        $status = $request->get('status', 'pending');
        $payment = $request->get('payment');

        $statusCounts = [

            'pending' => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Order::where('user_id', Auth::id())->where('status', 'confirmed')->count(),
            'packaging' => Order::where('user_id', Auth::id())->where('status', 'packaging')->count(),
            'shipped' => Order::where('user_id', Auth::id())->where('status', 'shipped')->count(),
            'delivered' => Order::where('user_id', Auth::id())->where('status', 'delivered')->count(),
            'rated' => Order::where('user_id', Auth::id())->where('status', 'rated')->count(),
            'cancelled' => Order::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
            'returned' => Order::where('user_id', Auth::id())->where('status', 'returned')->count(),
        ];


        $query = Order::with(['orderDetails.productVariant.product', 'orderVoucher', 'payment'])
            ->where('user_id', Auth::id());

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
            $html = view('client.partials.orders-table', compact('orders'))->render();
            return response()->json(['html' => $html]);
        }

        return view('client.dashboard.order', compact('orders', 'statusCounts', 'status', 'payment'));
    }


    public function detail($code)
    {
        $order = Order::with('orderDetails.productVariant.product', 'shippingAddress')
            ->where('code', $code)
            ->firstOrFail();
        return view('client.checkout-success', compact('order'));
    }

    public function apiDetail($code)
    {
        $order = Order::with('orderDetails.productVariant.product', 'shippingAddress')
            ->where('code', $code)
            ->firstOrFail();

        return response()->json([
            'order_id' => $order->id,
            'order_code' => $order->code,
            'shipping_address' => $order->shippingAddress ? [
                'full_name' => $order->shippingAddress->full_name,
                'phone' => $order->shippingAddress->phone,
                'address' => $order->shippingAddress->address,
            ] : null,
            'items' => $order->orderDetails->map(function ($detail) {
                $product = $detail->productVariant->product;

                return [
                    'order_detail_id' => $detail->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $detail->product_variant_id,
                    'product_name' => $product->name ?? 'Sản phẩm không tồn tại',
                    'variant_name' => $detail->productVariant->name ?? '',
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'size' => $detail->size,
                    'color' => $detail->color,
                    'image_url' => $product && $product->image ? asset('/storage/' . $product->image) : asset('images/no-image.png'),
                ];
            }),
        ]);
    }

    protected function getOrderItems(Request $request, $user)
    {
        $type = $request->get('type');

        if ($type === 'buy_now') {
            $buyNow = session('buy_now');
            if (!$buyNow) {
                return collect();
            }

            $variant = ProductVariant::with('product', 'size', 'color')
                ->find($buyNow['product_variant_id']);

            if (!$variant) {
                return collect();
            }

            return collect([
                (object) [
                    'product_variant_id' => $variant->id,
                    'size' => $variant->size->name,
                    'color' => $variant->color->name,
                    'quantity' => $buyNow['quantity'],
                    'price' => $variant->sale_price ?? $variant->price ?? 0,
                ]
            ]);
        } elseif ($type === 'cart') {
            $cart = $user->cart;

            $selectedIds = $request->input('cart_items', []);
            if (empty($selectedIds)) {
                return collect();
            }

            return $cart->details()
                ->with('productVariant.product', 'productVariant.size', 'productVariant.color')
                ->whereIn('id', $selectedIds)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'product_variant_id' => $item->product_variant_id,
                        'size' => $item->size,
                        'color' => $item->color,
                        'quantity' => $item->quantity,
                        'price' => $item->productVariant->sale_price ?? $item->productVariant->price ?? 0,
                    ];
                });
        }

        return collect();
    }

    protected function calculateTotal($items, $voucherCode = null)
    {
        $total = $items->sum(fn($item) => $item->price * $item->quantity);
        $discount = 0;

        if ($voucherCode) {
            $voucher = Voucher::where('code', $voucherCode)->first();
            if ($voucher && $voucher->isValidForAmount($total)) {
                $discount = $voucher->type === 'percent'
                    ? min(($total * $voucher->value) / 100, $voucher->max_discount_amount ?? $total)
                    : min($voucher->value, $total);
            }
        }

        return [$total, $discount];
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_address' => 'required|exists:addresses,id',
            'payment_method' => 'nullable|string|in:cod,vnpay,momo,zalopay',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
            'type' => 'nullable|string|in:cart,buy_now',
        ]);

        $user = Auth::user();

        // Kiểm tra tài khoản bị khóa
        if (!$user->canPurchase()) {
            return redirect()->back()->withErrors('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.');
        }

        $items = $this->getOrderItems($request, $user);

        if ($items->isEmpty()) {
            return redirect()->back()->withErrors('Không có sản phẩm để đặt hàng.');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $request->selected_address,
                'code' => bin2hex(random_bytes(8)),
                'status' => 'pending',
                'total_amount' => 0,
            ]);

            $totalPrice = 0;
            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'size' => $item->size,
                    'color' => $item->color,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
                $totalPrice += $item->price * $item->quantity;

            }

            [$total, $discount] = $this->calculateTotal($items, $request->voucher_code);

            $order->update(['total_amount' => $total - $discount]);

            if ($request->filled('voucher_code') && $discount > 0) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher) {
                    OrderVoucher::create([
                        'order_id' => $order->id,
                        'voucher_id' => $voucher->id,
                        'code' => $voucher->code,
                        'discount' => $discount,
                        'applied_at' => now(),
                    ]);

                    $voucher->increment('used_count');
                }
            }

            if ($request->get('type') === 'cart') {
                $selectedIds = $request->input('cart_items', []);
                if (!empty($selectedIds)) {
                    $user->cart->details()->whereIn('id', $selectedIds)->delete();
                }
            } else {
                session()->forget('buy_now');
            }

            $method = $request->payment_method ?? 'cod';

            $payment = Payment::create([
                'order_id' => $order->id,
                'method' => $method,
                'total_amount' => $total - $discount,
                'status' => 'pending',
            ]);

            DB::commit();


            if ($method === 'vnpay') {
                return $this->processVNPayPayment($order, $payment);
            } else {
                $this->notificationService->notifyAdmins(
                    'Đơn hàng mới',
                    " Tài khoản {$order->user?->name} đã đặt đơn {$order->code}",
                    '/admin/orders/' . $order->code,
                    $order->id
                );
                broadcast(new UpdateOrderStatus($order));
            }
            return redirect()->route('client.checkout.detail', ['code' => $order->code])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Lỗi khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function edit($code)
    {
        $order = Order::with('orderDetails.productVariant.product', 'shippingAddress')
            ->where('code', $code)
            ->firstOrFail();
        $addresses = Auth::user()->addresses;
        $vouchers = Voucher::all();
        return view('client.edit-checkout', compact('order', 'addresses', 'vouchers'));
    }

    public function update(Request $request, $code)
    {
        $request->validate([
            'selected_address' => 'required|exists:addresses,id',
            'payment_method' => 'nullable|string|in:cod,vnpay,momo,zalopay',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ]);

        $user = Auth::user();

        // Kiểm tra tài khoản bị khóa
        if (!$user->canPurchase()) {
            return redirect()->back()->withErrors('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.');
        }

        $order = Order::with(['orderDetails.productVariant', 'payment', 'orderVoucher'])
            ->where('code', $code)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $order->address_id = $request->selected_address;
            $order->status = 'pending';

            $totalPrice = 0;
            foreach ($order->orderDetails as $detail) {
                $totalPrice += $detail->price * $detail->quantity;
            }

            if ($order->orderVoucher) {
                $oldVoucher = $order->orderVoucher->voucher;
                if ($oldVoucher && $oldVoucher->used_count > 0) {
                    $oldVoucher->decrement('used_count');
                }
                $order->orderVoucher()->delete();
            }

            $discountAmount = 0;
            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();

                if ($voucher && $voucher->isValidForAmount($totalPrice)) {
                    if ($voucher->type === 'percent') {
                        $discountAmount = ($totalPrice * $voucher->value) / 100;
                        if ($voucher->max_discount_amount) {
                            $discountAmount = min($discountAmount, $voucher->max_discount_amount);
                        }
                    } else {
                        $discountAmount = $voucher->value;
                    }

                    $discountAmount = min($discountAmount, $totalPrice);

                    $order->orderVoucher()->create([
                        'voucher_id' => $voucher->id,
                        'code' => $voucher->code,
                        'discount' => $discountAmount,
                        'applied_at' => now(),
                    ]);

                    $voucher->increment('used_count');
                }
            }

            $finalAmount = $totalPrice - $discountAmount;
            $order->total_amount = $finalAmount;
            $order->save();

            $method = $request->payment_method ?? 'cod';

            if ($order->payment) {
                $order->payment->update([
                    'method' => strtoupper($method),
                    'total_amount' => $finalAmount,
                    'status' => 'pending',
                ]);
            } else {
                $order->payment()->create([
                    'method' => strtoupper($method),
                    'total_amount' => $finalAmount,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            if ($method === 'vnpay') {
                return $this->processVNPayPayment($order, $order->payment);
            } else {
                $this->notificationService->notifyAdmins(
                    'Đơn hàng mới',
                    " Tài khoản {$order->user?->name} đã đặt đơn {$order->code}",
                    '/admin/orders/' . $order->code,
                    $order->id
                );
                broadcast(new UpdateOrderStatus($order));
            }

            return redirect()->route('client.checkout.detail', ['code' => $order->code])
                ->with('success', 'Cập nhật đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,shipped,delivered,cancelled'
        ]);
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        broadcast(new UpdateOrderStatus($order));

        return response()->json(['success' => true]);
    }

    public function cancelOrder(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $order = Order::findOrFail($id);
        $order->status = 'cancelled';
        $order->note = $request->reason;
        $order->save();

        foreach ($order->orderDetails as $detail) {
            $variant = ProductVariant::find($detail->product_variant_id);
            if ($variant) {
                $variant->increment('quantity', $detail->quantity);
            }
        }

        if ($order->payment) {
            $order->payment->update(['status' => 'failed']);
        }

        $this->notificationService->notifyAdmins(
            "Đơn hàng đã bị huỷ",
            "Tài khoản {$order->user->name} đã huỷ đơn #{$order->code}",
            "/admin/orders/{$order->code}",
            $order->id
        );

        broadcast(new UpdateOrderStatus($order));

        return response()->json([
            'success' => true,
            'message' => 'Huỷ đơn hàng thành công.',
            'order' => $order
        ]);
    }

    public function reBuy($id)
    {
        $order = Order::with('orderDetails.productVariant')->findOrFail($id);
        $cart = Auth::user()->cart;

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
        }

        DB::beginTransaction();
        try {
            foreach ($order->orderDetails as $detail) {
                $existingItem = $cart->details()
                    ->where('product_variant_id', $detail->product_variant_id)
                    ->where('size', $detail->size)
                    ->where('color', $detail->color)
                    ->first();

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $detail->quantity
                    ]);
                } else {
                    $cart->details()->create([
                        'product_variant_id' => $detail->product_variant_id,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'size' => $detail->size,
                        'color' => $detail->color,
                    ]);
                }
            }
            $order->delete();

            DB::commit();
            return redirect()->route('client.cart');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    protected function processVNPayPayment(Order $order, Payment $payment)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/client/payment/vnpay/return";
        $vnp_TmnCode = "VFD3SN64";
        $vnp_HashSecret = "I5H2HD3HTN7ZDJ1APE3GY37AB0LY8S8H";

        $vnp_TxnRef = $order->code;
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $payment->total_amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00'
            ,
            'message' => 'success'
            ,
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        return redirect($vnp_Url);

    }
}
