<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.product.create', compact('categories', 'colors', 'sizes'));
    }

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
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'image.required' => 'Hình ảnh sản phẩm là bắt buộc',
            'category_id.required' => 'Bạn phải chọn danh mục',
            'variants.required' => 'Cần ít nhất 1 biến thể',
            'variants.*.color_id.required' => 'Chọn màu sắc cho mỗi biến thể',
            'variants.*.size_id.required' => 'Chọn kích cỡ cho mỗi biến thể',
            'variants.*.price.required' => 'Giá không được để trống',
            'variants.*.quantity.required' => 'Số lượng không được để trống',
            'gallery.*.image' => 'Tập tin phải là hình ảnh.',
            'gallery.*.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'gallery.*.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        // kiểm tra trùng biến thể color+size nội bộ
        if ($request->has('variants') && is_array($request->variants)) {
            $combos = [];
            foreach ($request->variants as $idx => $variant) {
                $color = $variant['color_id'] ?? null;
                $size = $variant['size_id'] ?? null;
                if ($color && $size) {
                    $key = "{$color}-{$size}";
                    if (in_array($key, $combos)) {
                        $validator->errors()->add("variants.{$idx}.color_id", 'Biến thể trùng màu và size.'); // thêm lỗi cụ thể
                        $validator->errors()->add("variants.{$idx}.size_id", 'Biến thể trùng màu và size.');
                    } else {
                        $combos[] = $key;
                    }
                }
            }
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // phần lưu giống như trước
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . time()),
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
                $product->galleries()->create(['image' => $path]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
    }


    public function show(Product $product)
    {
        $product->load(['category', 'variants.color', 'variants.size']);
        return view('admin.product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $variants = $product->variants()->get();
        return view('admin.product.edit', compact('product', 'categories', 'colors', 'sizes', 'variants'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'keep_gallery' => 'nullable|array',
            'keep_gallery.*' => 'integer|exists:product_galleries,id',
        ], [
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'category_id.required' => 'Danh mục là bắt buộc',
            'image.image' => 'Ảnh chính phải là file hình ảnh',
            'gallery.*.image' => 'Tập tin trong thư viện ảnh phải là hình ảnh',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật thông tin cơ bản
        $product->update([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'description' => $request->input('description'),
        ]);

        // Ảnh chính
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
            $product->save();
        }

        // Thư viện ảnh
        $keepIds = $request->input('keep_gallery', []);

        // Xoá ảnh cũ không còn được giữ lại
        $product->gallery()->whereNotIn('id', $keepIds)->get()->each(function ($img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        });

        // Thêm ảnh mới nếu có
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('products/gallery', 'public');
                $product->gallery()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.product.edit', $product->id)
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }



    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Xoá sản phẩm thành công!');
    }
}
