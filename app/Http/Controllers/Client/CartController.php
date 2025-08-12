<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;

        $cartItems = $cart->details()->with('productVariant.product')->get();
        $total = $cartItems->sum(function ($item) {
            return ($item->productVariant->sale_price ?? 0) * $item->quantity;
        });

        return view('client.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request): JsonResponse
    {
        if ($request->product_variant_id == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã hết hàng. Vui lòng chọn sản phẩm khác',
            ], 400);
        }

        $userId = Auth::user()->id;

        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $existingItem = CartDetail::where('cart_id', $cart->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            $variant = ProductVariant::with('product', 'color', 'size')
                ->find($request->product_variant_id);

            CartDetail::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'price' => $variant->sale_price,
                'color' => $variant->color->name,
                'size' => $variant->size->name,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng thành công',
        ]);
    }

    public function getDropdownHTML()
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            return view('client.partials.real-time-cart', [
                'cartItems' => [],
                'total' => 0
            ])->render();
        }

        $cartItems = $cart->details()->with('productVariant.product')->get();

        $total = $cartItems->sum(function ($item) {
            return ($item->productVariant->sale_price ?? 0) * $item->quantity;
        });

        return view('client.partials.real-time-cart', compact('cartItems', 'total'))->render();
    }

    public function remove(Request $request)
    {
        $itemId = $request->input('item_id');

        $item = CartDetail::find($itemId);
        if ($item) {
            $item->delete();
            return response()->json(['success' => true, 'message' => 'Đã xoá sản phẩm khỏi giỏ hàng']);
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ']);
    }

    public function updateQuantity(Request $request, $id)
    {
        $cartItem = CartDetail::where('id', $id)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('productVariant')
            ->firstOrFail();

        // Kiểm tra số lượng tồn kho
        if ($cartItem->productVariant->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ còn ' . $cartItem->productVariant->quantity . ' sản phẩm trong kho'
            ], 200);
        }

        // Cập nhật số lượng
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Tính tổng giỏ hàng
        $total = $cartItem->cart->details->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'success' => true,
            'itemTotal' => number_format($cartItem->quantity * $cartItem->price, 0, ',', '.') . '₫',
            'cartTotal' => number_format($total, 0, ',', '.') . '₫'
        ]);
    }

}
