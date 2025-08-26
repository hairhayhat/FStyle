<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $newProducts = Product::with(['category', 'variants', 'galleries'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $favoriteProductIds = [];
        $products = Product::with(['category', 'variants', 'galleries'])
            ->orderBy('created_at', 'desc')
            ->paginate(8);
        $categories = Category::all();

        if (Auth::check()) {
            $favoriteProductIds = Auth::user()->favorites->pluck('id')->toArray();
        }
        return view('client.welcome', compact('newProducts', 'favoriteProductIds', 'categories', 'products'));
    }

    public function show($slug)
    {
        try {
            $product = Product::with(['category', 'variants', 'galleries'])
                ->where('slug', $slug)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $this->transformProduct($product)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    protected function transformProduct($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->category->name,
            'description' => $product->description,
            'main_image' => asset('storage/' . $product->image),
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'color' => $variant->color->name,
                    'size' => $variant->size->name,
                    'sale_price' => number_format($variant->sale_price),
                    'quantity' => $variant->quantity
                ];
            }),
            'galleries' => $product->galleries->map(function ($gallery) {
                return asset('storage/' . $gallery->image);
            }),
        ];
    }

    public function detailProduct($slug)
    {
        try {
            $product = Product::with(['category', 'variants', 'galleries', 'comments.user']) // eager load comments với user
                ->where('slug', $slug)
                ->firstOrFail();

            if (is_null($product->views)) {
                $product->views = 0;
                $product->save();
            }
            $product->increment('views');

            $sizes = Size::all();
            $sameCateProducts = Product::with(['category', 'variants', 'galleries'])
                ->where('category_id', $product->category_id)
                ->take(5)
                ->get();

            $favoriteProductIds = [];
            if (Auth::check()) {
                $favoriteProductIds = Auth::user()->favorites->pluck('product_id')->toArray();
            }

            $comments = $product->ActiveComments;

            $totalRatings = $comments->count();
            $averageRating = $totalRatings ? round($comments->avg('rating'), 1) : 0;

            $ratingCounts = [];
            for ($i = 1; $i <= 5; $i++) {
                $ratingCounts[$i] = $comments->where('rating', $i)->count();
            }

            $ratingPercentages = [];
            for ($i = 1; $i <= 5; $i++) {
                $ratingPercentages[$i] = $totalRatings ? round($ratingCounts[$i] / $totalRatings * 100) : 0;
            }

            return view('client.product.detail', compact(
                'product',
                'sizes',
                'sameCateProducts',
                'favoriteProductIds',
                'comments',
                'totalRatings',
                'averageRating',
                'ratingPercentages'
            ));
        } catch (\Exception) {
            return back()->with('error', 'Sản phẩm không tồn tại');
        }
    }

}

