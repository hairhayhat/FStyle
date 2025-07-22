<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\Size;
use DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(perPage: 10);
        return view('admin.product.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product.create', compact('categories', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'variants' => 'required|array|min:1',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Tuỳ chỉnh thông báo lỗi (có thể dùng tiếng Việt)
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'image.required' => 'Hình ảnh sản phẩm là bắt buộc',
            'category_id.required' => 'Bạn phải chọn danh mục',
            'variants.required' => 'Cần ít nhất 1 biến thể',
            'variants.*.color_id.required' => 'Bạn phải chọn màu sắc cho mỗi biến thể',
            'variants.*.size_id.required' => 'Bạn phải chọn kích cỡ cho mỗi biến thể',
            'variants.*.price.required' => 'Giá không được để trống',
            'variants.*.quantity.required' => 'Số lượng không được để trống',
            'gallery.*.image' => 'Tập tin phải là hình ảnh.',
            'gallery.*.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'gallery.*.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xử lý lưu sản phẩm
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'views' => 0,
            'image' => $request->file('image')->store('products', 'public'),
        ]);

        foreach ($request->variants as $variant) {
            $product->variants()->create([
                'color_id' => $variant['color_id'],
                'size_id' => $variant['size_id'],
                'price' => $variant['price'],
                'quantity' => $variant['quantity'],
            ]);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('product_gallery', 'public');

                $product->galleries()->create([
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'variants.color', 'variants.size']); // eager load
        return view('admin.product.show', compact('product'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $variants = $product->variants()->get();
        return view('admin.product.edit', compact('product', 'categories', 'colors', 'sizes', 'variants'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);
        // Cập nhật sản phẩm
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $request->hasFile('image')
                ? $request->file('image')->store('products', 'public')
                : $product->image
        ]);
        // Xoá các biến thể cũ (hoặc có thể dùng cập nhật nếu muốn nâng cao)
        $product->variants()->delete();
        // Lưu các biến thể mới
        foreach ($request->variants as $variant) {
            $product->variants()->create($variant);
        }
        return redirect()->route('admin.product.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Xóa sản phẩm thành công!');
    }
}
