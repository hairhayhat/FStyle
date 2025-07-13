<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        if (Auth::user()->role_id != 1) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}
