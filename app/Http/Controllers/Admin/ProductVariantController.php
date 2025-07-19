<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        // Kiểm tra trùng biến thể
        $exists = ProductVariant::where([
            'product_id' => $data['product_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
        ])->exists();

        if ($exists) {
            return response()->json(['message' => 'Biến thể này đã tồn tại.'], 409);
        }

        $variant = ProductVariant::create($data);

        return response()->json(['variant' => $variant, 'message' => 'Thêm biến thể thành công!']);
    }

    public function update(Request $request, ProductVariant $productVariant)
    {
        $data = $request->validate([
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $productVariant->update($data);

        return response()->json(['message' => 'Cập nhật thành công']);
    }

    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();

        return response()->json(['message' => 'Xoá biến thể thành công']);
    }
}
