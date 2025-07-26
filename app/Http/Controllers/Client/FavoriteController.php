<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class FavoriteController extends Controller
{
    public function favorite(Product $product)
    {
        try {
            // Kiểm tra sản phẩm tồn tại
            if (!$product->exists) {
                throw new ModelNotFoundException('Sản phẩm không tồn tại');
            }

            // Kiểm tra đã yêu thích chưa
            if (Auth::user()->favorites()->where('product_id', $product->id)->exists()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích',
                    'favorites_count' => $product->favoritedBy()->count()
                ], 200);
            }

            // Thêm vào yêu thích
            Auth::user()->favorites()->syncWithoutDetaching([$product->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã thêm sản phẩm vào yêu thích',
                'favorites_count' => $product->favoritedBy()->count()
            ], 201);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unfavorite(Product $product)
    {
        try {
            // Kiểm tra sản phẩm tồn tại
            if (!$product->exists) {
                throw new ModelNotFoundException('Sản phẩm không tồn tại');
            }

            // Kiểm tra có trong danh sách yêu thích không
            if (!Auth::user()->favorites()->where('product_id', $product->id)->exists()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Sản phẩm không có trong danh sách yêu thích',
                    'favorites_count' => $product->favoritedBy()->count()
                ], 200);
            }

            // Xóa khỏi yêu thích
            Auth::user()->favorites()->detach($product->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa sản phẩm khỏi yêu thích',
                'favorites_count' => $product->favoritedBy()->count()
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    // Thêm phương thức kiểm tra trạng thái yêu thích
    public function checkFavoriteStatus(Product $product)
    {
        try {
            $isFavorited = Auth::check()
                ? Auth::user()->favorites()->where('product_id', $product->id)->exists()
                : false;

            return response()->json([
                'status' => 'success',
                'is_favorited' => $isFavorited,
                'favorites_count' => $product->favoritedBy()->count()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi kiểm tra trạng thái yêu thích'
            ], 500);
        }
    }
}
