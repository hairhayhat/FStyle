<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $cart = Auth::user()->cart;
        $addresses = Auth::user()->addresses;

        $cartItems = $cart->details()->with('productVariant.product')->get();
        $total = $cartItems->sum(function ($item) {
            return ($item->productVariant->price ?? 0) * $item->quantity;
        });
        return view('client.checkout', compact('cartItems', 'total', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_address' => 'required|exists:addresses,id',
            'payment_method' => 'nullable|string|in:vnpay,momo,zalopay',
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

                $price = $productVariant->price;
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

            $order->total_amount = $totalPrice;
            $order->save();

            $cart->details()->delete();

            if ($request->payment_method == null) {
                Payment::create([
                    'order_id' => $order->id,
                    'method' => 'COD',
                    'total_amount' => $order->total_amount,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('client.welcome')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }
}
