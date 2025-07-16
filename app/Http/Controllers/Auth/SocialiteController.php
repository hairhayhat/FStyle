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
                ->with('error', 'Không thể kết nối với Google. Vui lòng thử lại. Lỗi: ' . $e->getMessage());
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Tìm hoặc tạo user
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name ?? $googleUser->nickname,
                    'google_id' => $googleUser->id,
                    'avatar' => $this->getAvatarUrl($googleUser),
                    'role_id' => 2,
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, true);

            return $this->redirectAfterLogin($user);

        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Đăng nhập bằng Google thất bại. Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Xác định URL avatar
     */
    protected function getAvatarUrl($providerUser)
    {
        if (isset($providerUser->avatar)) {
            return $providerUser->avatar;
        }

        if (isset($providerUser->picture)) {
            return is_object($providerUser->picture)
                ? $providerUser->picture->data->url ?? null
                : $providerUser->picture;
        }

        return null;
    }

    /**
     * Redirect sau khi login thành công
     */
    protected function redirectAfterLogin(User $user)
    {
        if ($user->role_id === 1) { // Giả sử 1 là admin
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('client.welcome'));
    }
}
