<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        if (Auth::check() && Auth::user()->role_id == 2) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này!');
    }
}
