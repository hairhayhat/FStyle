<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->get();
        return view('admin.size.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.size.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Size::create($request->only('name'));

        return redirect()->route('admin.size.index')->with('success', 'Thêm size thành công!');
    }

    public function edit(Size $size)
    {
        return view('admin.size.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $size->update($request->only('name'));

        return redirect()->route('admin.size.index')->with('success', 'Cập nhật size thành công!');
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('admin.size.index')->with('success', 'Đã xoá size!');
    }
}
