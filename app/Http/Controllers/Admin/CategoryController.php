<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{


    public function index()
    {
        $categories = Category::paginate(10); // 10 là số bản ghi mỗi trang
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại trong hệ thống.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg hoặc png.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
            'image.required' => 'Ảnh là bắt buộc.',
        ]);

        // 2. Xử lý hình ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // 3. Tạo slug nếu chưa có
        $slug = $request->slug ?? Str::slug($request->name);

        // 4. Lưu vào database
        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
        ]);

        // 5. Điều hướng sau khi lưu
        return redirect()->route('admin.category.index')->with('success', 'Thêm danh mục thành công');
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại trong hệ thống.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg hoặc png.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        $data = $request->only('name');
        $data['slug'] = $request->slug ?? Str::slug($request->name);

        // Xử lý ảnh
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.category.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Kiểm tra danh mục có sản phẩm
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.category.index')
                ->with('error', 'Không thể xoá danh mục vì đang có sản phẩm.');
        }

        // Xoá ảnh nếu có
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Xoá danh mục
        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', 'Xoá danh mục thành công.');
    }
}
