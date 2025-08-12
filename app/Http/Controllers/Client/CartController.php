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
            return ($item->productVariant->price ?? 0) * $item->quantity;
        });

        return view('client.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request): JsonResponse
    {
        $userId = Auth::user()->id;

        // Xử lý trường hợp thêm từ wishlist (có color_id và size_id)
        if ($request->has('color_id') && $request->has('size_id')) {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'color_id' => 'required|exists:colors,id',
                'size_id' => 'required|exists:sizes,id',
                'quantity' => 'required|integer|min:1',
            ]);

            // Tìm product variant dựa trên product_id, color_id, size_id
            $variant = ProductVariant::where('product_id', $request->product_id)
                ->where('color_id', $request->color_id)
                ->where('size_id', $request->size_id)
                ->where('quantity', '>', 0)
                ->first();

            if (!$variant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Biến thể sản phẩm không tồn tại hoặc đã hết hàng',
                ], 400);
            }

            // Kiểm tra số lượng
            if ($request->quantity > $variant->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Số lượng vượt quá tồn kho. Chỉ còn ' . $variant->quantity . ' sản phẩm',
                ], 400);
            }

            $productVariantId = $variant->id;
        } else {
            // Xử lý trường hợp thêm trực tiếp (có product_variant_id)
            if ($request->product_variant_id == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm không tồn tại hoặc đã hết hàng. Vui lòng chọn sản phẩm khác',
                ], 400);
            }

            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $productVariantId = $request->product_variant_id;
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $existingItem = CartDetail::where('cart_id', $cart->id)
            ->where('product_variant_id', $productVariantId)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            $variant = ProductVariant::with('product', 'color', 'size')
                ->find($productVariantId);

            CartDetail::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'price' => $variant->price,
                'color' => $variant->color->name,
                'size' => $variant->size->name,
                'quantity' => $request->quantity
            ]);
        }

        // Lấy tổng số lượng sản phẩm trong giỏ hàng
        $cartCount = $cart->details()->sum('quantity');

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm vào giỏ hàng thành công',
            'cart_count' => $cartCount
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
            return ($item->productVariant->price ?? 0) * $item->quantity;
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
