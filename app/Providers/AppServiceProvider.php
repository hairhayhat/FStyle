<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\NotificationUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collections\Collection;

class AppServiceProvider extends ServiceProvider
{

    public function boot(): void
    {

        try {
            View::share('categories', Cache::remember('categories', 3600, fn() => Category::all()));

            $userId = Auth::id() ?? 0;

            View::share('notifications', Cache::remember("notifications_user_{$userId}", 3600, function () use ($userId) {
                return NotificationUser::with('notification')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }));
        } catch (\Throwable $e) {
            View::share('categories', collect());
            View::share('notifications', collect());
        }
    }
}
