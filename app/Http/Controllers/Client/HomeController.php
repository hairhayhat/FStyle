<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $newProducts = Product::with(['category', 'variants', 'galleries'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('client.welcome', compact('newProducts'));
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
                    'price' => number_format($variant->price),
                    'quantity' => $variant->quantity
                ];
            }),
            'galleries' => $product->galleries->map(function ($gallery) {
                return asset('storage/' . $gallery->image);
            }),
        ];
    }
}

