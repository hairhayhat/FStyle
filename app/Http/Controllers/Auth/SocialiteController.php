<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Exception;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Không thể kết nối với Google. Vui lòng thử lại' . $e->getMessage());
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            dd($google_user);

            $user = User::where('google_id', $google_user->getId())->first();

            if (!$user) {
                $user = new User();
                $user->name = $google_user->name ?? $google_user->nickname;
                $user->email = $google_user->email;
                $user->provider_id = $google_user->id;
                $user->avatar = $this->_getAvatarUrl($google_user);
                $user->role_id = 2;
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user, true);

            $user = Auth::user();

            if ($user->role_id === 2) {
                return redirect()->intended(route('client.welcome', absolute: false));
            } else {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }
        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.' . $e->getMessage());
        }
    }

    /**
     * Lấy URL avatar từ dữ liệu nhà cung cấp
     */
    protected function _getAvatarUrl($data)
    {
        if (isset($data->avatar)) {
            return $data->avatar;
        }

        if (isset($data->picture)) {
            return is_object($data->picture)
                ? $data->picture->data->url ?? null
                : $data->picture;
        }

        return null;
    }
}
