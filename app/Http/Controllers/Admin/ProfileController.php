<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function editProfile()
    {
        return view('admin.profile.profile');
    }

    public function update(Request $request)
    {
        // Validate dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        // Cập nhật thông tin cơ bản
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');

        // Nếu có upload ảnh mới
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Tạo thư mục nếu chưa có
            $destinationPath = public_path('uploads/profile');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Upload file
            $file->move($destinationPath, $filename);

            // Nếu có avatar cũ thì xóa để tránh rác
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }

            // Lưu tên file vào cột avatar
            $user->avatar = 'uploads/profile/' . $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
