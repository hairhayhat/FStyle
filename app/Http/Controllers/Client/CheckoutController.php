<?php

namespace App\Http\Controllers\Client;

use App\Models\Order;
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
        if ($request->get('type') === 'buy_now') {
            $buyNow = session('buy_now');
            if (!$buyNow) {
                return redirect()->route('client.welcome')
                    ->withErrors('Không có sản phẩm nào để mua ngay.');
            }

            $variant = ProductVariant::with('product')
                ->find($buyNow['product_variant_id']);

            $cartItems = collect([
                (object) [
                    'productVariant' => $variant,
                    'price' => $variant->sale_price ?? $variant->price ?? 0,
                    'quantity' => $buyNow['quantity'],
                    'color' => $variant->color->name,
                    'size' => $variant->size->name
                ]
            ]);

            $total = $cartItems->sum(function ($item) {
                $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
                return $price * $item->quantity;
            });

            $addresses = Auth::user()->addresses;
            $vouchers = Voucher::all();
            return view('client.checkout', compact('cartItems', 'total', 'addresses', 'vouchers'));
        }

        $cart = Auth::user()->cart;
        $cartItems = $cart->details()->with('productVariant.product')->get();
        $total = $cartItems->sum(function ($item) {
            $price = $item->productVariant->sale_price ?? $item->productVariant->price ?? 0;
            return $price * $item->quantity;
        });
        $addresses = Auth::user()->addresses;
        $vouchers = Voucher::all();
        return view('client.checkout', compact('cartItems', 'total', 'addresses', 'vouchers'));
    }

    public function index()
    {
        $orders = Order::with('orderDetails.productVariant.product', 'orderVoucher')->where('user_id', Auth::user()->id)->paginate(5);

        return view('client.dashboard.order', compact('orders'));
    }

    public function detail($code)
    {
        $order = Order::with('orderDetails.productVariant.product', 'shippingAddress')
            ->where('code', $code)
            ->firstOrFail();

        return view('client.checkout-success', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_address' => 'required|exists:addresses,id',
            'payment_method' => 'nullable|string|in:vnpay,momo,zalopay',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
        ]);

        $user = Auth::user();
        $cart = $user->cart;
        $cartItems = $cart->details()->with('productVariant.product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->withErrors('Giỏ hàng trống, không thể đặt hàng.');
        }

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->user_id = $user->id;
            $order->address_id = $request->selected_address;
            $order->code = bin2hex(random_bytes(8));
            $order->status = 'pending';
            $order->total_amount = 0;
            $order->save();

            $totalPrice = 0;

            foreach ($cartItems as $item) {
                $productVariant = $item->productVariant;

                $price = $productVariant->sale_price;
                $quantity = $item->quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'size' => $item->size,
                    'color' => $item->color,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalPrice += $price * $quantity;

                $productVariant->decrement('quantity', $quantity);
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

                    OrderVoucher::create([
                        'order_id' => $order->id,
                        'voucher_id' => $voucher->id,
                        'code' => $voucher->code,
                        'discount' => $discountAmount,
                        'applied_at' => now(),
                    ]);

                    $voucher->increment('used_count');
                }
            }

            $order->total_amount = $totalPrice - $discountAmount;
            $order->save();

            $cart->details()->delete();

            Payment::create([
                'order_id' => $order->id,
                'method' => $request->payment_method ?? 'COD',
                'total_amount' => $order->total_amount,
                'status' => 'pending',
            ]);

            $this->notificationService->notifyAdmins(
                'Bạn có đơn hàng mới',
                "Tài khoản {$user->name} đã đặt đơn #{$order->code}",
                'order',
                "/admin/orders/{$order->code}",
                $order->id
            );

            DB::commit();

            return redirect()->route('client.checkout.detail', ['code' => $order->code])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
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

        return response()->json(['success' => true]);
    }

    public function cancelOrder(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
            'status' => 'required|string|in:pending,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);

        $order->status = $request->status;
        $order->note = $request->reason;
        $order->save();

        return response()->json(['success' => true]);
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


}
