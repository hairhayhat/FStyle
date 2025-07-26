<?php

namespace App\Http\Controllers\Admin;

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

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Lưu sản phẩm
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . time()),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'views' => 0,
            'image' => $request->file('image')->store('products', 'public'),
        ]);

        // Lưu biến thể
        foreach ($request->variants as $variant) {
            $product->variants()->create([
                'color_id' => $variant['color_id'],
                'size_id' => $variant['size_id'],
                'price' => $variant['price'],
                'quantity' => $variant['quantity'],
            ]);
        }

        // Lưu gallery nếu có
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
        // Lấy product theo id
        $product = Product::findOrFail($id);

        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_new.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_old' => 'array',
            'gallery_old.*' => 'string',
            // Các validation khác tùy yêu cầu
        ], [
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'category_id.required' => 'Danh mục là bắt buộc',
            'gallery_new.*.image' => 'Ảnh thư viện phải là file ảnh hợp lệ',
            // Thông báo tùy chỉnh khác nếu cần
        ]);

        // Cập nhật các trường cơ bản sản phẩm
        $product->name = $request->input('name');
        $product->category_id = $request->input('category_id');
        $product->description = $request->input('description');

        // Xử lý ảnh chính (nếu upload mới)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Lưu ảnh mới
            $path = $request->file('image')->store('products/main', 'public');
            $product->image = $path;
        }

        // Xử lý thư viện ảnh

        // 1. Lấy ảnh cũ người dùng giữ lại
        $galleryOld = $request->input('gallery_old', []); // Mảng các đường dẫn ảnh cũ

        // 2. Tìm ảnh cũ cần xóa (ảnh trong DB nhưng không nằm trong gallery_old)
        $imagesToDelete = array_diff($product->gallery ?? [], $galleryOld);

        foreach ($imagesToDelete as $imgPath) {
            Storage::disk('public')->delete($imgPath);
        }

        // 3. Khởi tạo mảng gallery mới bắt đầu từ ảnh cũ giữ lại
        $gallery = $galleryOld;

        // 4. Xử lý upload ảnh mới
        if ($request->hasFile('gallery_new')) {
            foreach ($request->file('gallery_new') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->galleries()->create(['image' => $path]);
            }
        }

        // Lưu sản phẩm
        $product->save();

        return redirect()->route('admin.product.edit', $product->id)
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Xoá sản phẩm thành công!');
    }
}
