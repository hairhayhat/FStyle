<?php

namespace App\Http\Controllers\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function renderProfile()
    {
        return view('client.dashboard.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $avatarName, 'public');
            $user->avatar = $avatarName;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Thông tin đã được cập nhật!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#^()_\-+=.]/',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.regex' => 'Mật khẩu mới phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);


        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
    public function dashboard(){

        $user = Auth::user();

        return view('client.dashboard.index', compact('user'));
    }

}
