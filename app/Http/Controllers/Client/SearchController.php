<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;

class SearchController extends Controller
{

    public function ajaxSearchProducts(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['success' => false, 'message' => 'No search query provided'], 400);
        }

        $products = Product::where('name', 'like', "%$query%")
            ->take(5)
            ->get(['name', 'slug', 'image'])
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => asset('storage/' . $product->image)
                ];
            });

        $categories = Category::where('name', 'like', "%$query%")
            ->take(5)
            ->get(['name', 'slug', 'image'])
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => asset('storage/' . $category->image)
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function searchCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::withCount('products')->get();
        $colors = Color::with('variants')->get();
        $sizes = Size::with('variants')->get();

        $products = Product::with(['category', 'variants', 'galleries'])
            ->where('category_id', $category->id)
            ->get();

        return view('client.search', compact('products', 'categories', 'colors', 'sizes'));
    }

    public function filter(Request $request)
    {
        $query = Product::with('variants', 'category');

        if ($request->categories) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->whereIn('name', $request->categories);
            });
        }

        if ($request->color) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        if ($request->size) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size_id', $request->size);
            });
        }

        if ($request->price_from && $request->price_to) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereBetween('price', [$request->price_from, $request->price_to]);
            });
        }

        $products = $query->get();

        return view('client.partials.product', compact('products'));
    }

}
