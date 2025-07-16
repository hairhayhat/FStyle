<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->role_id === 1) {
                return redirect('admin.dashboard');
            } else {
                return redirect('client.welcome');
            }
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with([
            'success' => 'Chúng tôi đã gửi lại email cho bạn. Vui lòng kiểm tra lại',
        ]);
    }
}
