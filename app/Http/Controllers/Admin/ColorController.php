<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->get();
        return view('admin.color.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.color.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:7',
        ]);

        // Thêm '#' nếu chưa có
        if (strpos($data['code'], '#') !== 0) {
            $data['code'] = '#' . ltrim($data['code'], '#');
        }

        Color::create($data);

        return redirect()->route('admin.color.index')->with('success', 'Thêm màu thành công!');
    }

    public function edit(Color $color)
    {
        return view('admin.color.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:7',
        ]);

        // Thêm '#' nếu chưa có
        if (strpos($data['code'], '#') !== 0) {
            $data['code'] = '#' . ltrim($data['code'], '#');
        }

        $color->update($data);

        return redirect()->route('admin.color.index')->with('success', 'Cập nhật màu thành công!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.color.index')->with('success', 'Đã xoá màu!');
    }
}
