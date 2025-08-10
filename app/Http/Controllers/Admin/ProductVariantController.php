<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;

class ProductVariantController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ], [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'price.required' => 'Giá không được để trống.',
            'price.numeric' => 'Giá phải là số hợp lệ.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'quantity.required' => 'Số lượng không được để trống.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra trùng biến thể (color_id + size_id) với cùng product_id
        $exists = ProductVariant::where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Biến thể với màu và size này đã tồn tại.'
            ], 409);
        }

        $variant = ProductVariant::create([
            'product_id' => $request->product_id,
            'color_id' => $request->color_id,
            'size_id' => $request->size_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm biến thể thành công.',
            'variant' => $variant,
        ]);
    }
    public function update(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $data = $request->validate([
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $variant->update($data);
        return response()->json(['status' => 'success']);
    }

    public function destroy(Request $request, $id)
    {
        $variant = ProductVariant::find($id);

        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Biến thể không tồn tại.'
            ], 404);
        }

        try {
            $variant->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xoá biến thể thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xoá biến thể: ' . $e->getMessage()
            ], 500);
        }
    }
}
