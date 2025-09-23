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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc');
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status');

        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($status === 'active') {
            $query->whereDoesntHave('variants', function ($q) {
                $q->whereHas('cartDetails')
                    ->orWhereHas('orderDetails');
            });
        } elseif ($status === 'locked') {
            $query->whereHas('variants', function ($q) {
                $q->whereHas('cartDetails')
                    ->orWhereHas('orderDetails');
            });
        }

        $products = $query->orderBy('created_at', $sort)
            ->paginate($perPage)
            ->appends($request->all());

        $categories = Category::all();

        if ($request->ajax()) {
            $html = view('admin.partials.table-products', compact('products'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.product.index', compact('products', 'categories'));
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
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'variants' => 'required|array',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.import_price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
        ], [
            'required' => ':attribute không được để trống.',
            'string' => ':attribute phải là chuỗi.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'integer' => ':attribute phải là số nguyên.',
            'numeric' => ':attribute phải là số.',
            'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            'exists' => ':attribute không tồn tại trong hệ thống.',
            'image' => ':attribute phải là hình ảnh.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'array' => ':attribute phải là một mảng.',
            'name' => 'Tên sản phẩm',
            'category_id' => 'Danh mục',
            'description' => 'Mô tả sản phẩm',
            'gallery' => 'Thư viện ảnh',
            'gallery.*' => 'Ảnh trong thư viện',
            'variants' => 'Biến thể sản phẩm',
            'variants.*.color_id' => 'Màu sắc',
            'variants.*.size_id' => 'Size',
            'variants.*.import_price' => 'Giá nhập',
            'variants.*.sale_price' => 'Giá bán',
            'variants.*.quantity' => 'Số lượng',
        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Lưu ảnh chính
        $mainImagePath = $request->file('image')->store('products', 'public');

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }


        // Tạo sản phẩm
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $mainImagePath,
        ]);

        // Lưu thư viện ảnh
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('products/gallery', 'public');
                $product->galleries()->create(['image' => $path]);
            }
        }

        // Lưu các biến thể
        foreach ($request->variants as $variant) {
            ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => $variant['color_id'],
                'size_id' => $variant['size_id'],
                'import_price' => $variant['import_price'],
                'sale_price' => $variant['sale_price'],
                'quantity' => $variant['quantity'],
            ]);
        }

        return redirect()->route('admin.product.index')
            ->with('success', 'Thêm sản phẩm thành công!');
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

        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'keep_gallery' => 'nullable|array',
            'keep_gallery.*' => 'integer|exists:product_galleries,id',

            // Variants
            'variants' => 'required|array',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.import_price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Slug
        $slug = Str::slug($request->name);

        // Cập nhật thông tin cơ bản
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        // Ảnh chính
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);
        }

        // Gallery
        $keepIds = $request->input('keep_gallery', []);
        $product->galleries()->whereNotIn('id', $keepIds)->get()->each(function ($img) {
            if ($img->image) {
                Storage::disk('public')->delete($img->image);
            }
            $img->delete();
        });

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('products/gallery', 'public');
                $product->galleries()->create(['image' => $path]);
            }
        }

        $keepVariantIds = [];
        foreach ($request->variants as $variant) {
            if (!empty($variant['id'])) {
                $pv = ProductVariant::find($variant['id']);
                if ($pv) {
                    $hasOrder = $pv->orderDetails()->exists();
                    if ($hasOrder) {
                        if (
                            $pv->color_id != $variant['color_id'] ||
                            $pv->size_id != $variant['size_id'] ||
                            $pv->import_price != $variant['import_price'] ||
                            $pv->sale_price != $variant['sale_price']
                        ) {
                            return back()->with('error', "Không thể sửa size, màu, giá nhập, giá bán của variant đã có đơn hàng.")
                                ->withInput();
                        }

                        $pv->update([
                            'quantity' => $variant['quantity'],
                        ]);
                    } else {
                        $pv->update([
                            'color_id' => $variant['color_id'],
                            'size_id' => $variant['size_id'],
                            'import_price' => $variant['import_price'],
                            'sale_price' => $variant['sale_price'],
                            'quantity' => $variant['quantity'],
                        ]);
                    }

                    $keepVariantIds[] = $pv->id;
                }
            } else {
                $pv = $product->variants()->create([
                    'color_id' => $variant['color_id'],
                    'size_id' => $variant['size_id'],
                    'import_price' => $variant['import_price'],
                    'sale_price' => $variant['sale_price'],
                    'quantity' => $variant['quantity'],
                ]);
                $keepVariantIds[] = $pv->id;
            }
        }

        $product->variants()->whereNotIn('id', $keepVariantIds)->delete();

        return redirect()->route('admin.product.edit', $product->id)
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->inUse() == true) {
            return redirect()
                ->route('admin.product.index')
                ->with('error', 'Sản phẩm này đang được sử dụng nên không thể xóa.');
        }
        $product->delete();
        return back()->with('success', 'Xoá sản phẩm thành công!');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        $product->status = $product->status === 'available' ? 'discontinued' : 'available';
        $product->save();

        return back()->with('success', 'Trạng thái sản phẩm đã được cập nhật!');
    }

}
