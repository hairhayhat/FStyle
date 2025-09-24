<?php // Tệp PHP

namespace App\Http\Controllers\Admin; // Nhóm controller quản trị

use App\Http\Controllers\Controller; // Lớp cơ sở Controller
use Illuminate\Http\Request; // Đối tượng Request
use App\Models\Size; // Model Size

class SizeController extends Controller // Controller quản lý Size
{
    public function index() // Trang danh sách size
    {
        $sizes = Size::latest()->get(); // Lấy tất cả size, sắp xếp mới nhất
        return view('admin.size.index', compact('sizes')); // Truyền dữ liệu sang view
    }

    public function create() // Trang tạo size
    {
        return view('admin.size.create'); // Trả về form tạo
    }

    public function store(Request $request) // Lưu size mới
    {
        $request->validate([
            'name' => 'required|string|max:255', // Bắt buộc tên size, tối đa 255 ký tự
        ]);

        Size::create($request->only('name')); // Tạo size chỉ với trường name

        return redirect()->route('admin.size.index')->with('success', 'Thêm size thành công!'); // Thông báo thành công
    }

    public function edit(Size $size) // Trang sửa size
    {
        return view('admin.size.edit', compact('size')); // Truyền size vào form chỉnh sửa
    }

    public function update(Request $request, Size $size) // Cập nhật size
    {
        $request->validate([
            'name' => 'required|string|max:255', // Validate tên
        ]);

        $size->update($request->only('name')); // Cập nhật chỉ trường name

        return redirect()->route('admin.size.index')->with('success', 'Cập nhật size thành công!'); // Thông báo thành công
    }

    public function destroy(Size $size) // Xoá size
    {
        $size->delete(); // Xoá bản ghi size

        return redirect()->route('admin.size.index')->with('success', 'Đã xoá size!'); // Thông báo xoá thành công
    }
}
