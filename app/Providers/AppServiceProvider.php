<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Category;
use App\Models\ChatMessages;
use App\Models\NotificationUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share categories
        try {
            View::share('categories', Cache::remember('categories', 3600, fn() => Category::all()));
        } catch (\Throwable $e) {
            View::share('categories', collect());
        }

        // Share admin users (chỉ admin khác mình)
        View::share('adminUsers', Cache::remember(
            'adminUsers_' . Auth::id(),
            3600,
            fn() =>
            User::where('id', '!=', Auth::id())
                ->where('role_id', 1)
                ->get()
        ));

        // Share chat users
        View::composer('*', function ($view) {
            $currentUserId = Auth::id();

            if ($currentUserId) {
                $users = ChatMessages::where('sender_id', $currentUserId)
                    ->orWhere('receiver_id', $currentUserId)
                    ->with(['sender', 'receiver'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy(function ($message) use ($currentUserId) {
                        return $message->sender_id == $currentUserId
                            ? $message->receiver_id
                            : $message->sender_id;
                    })
                    ->map(function ($messages, $userId) use ($currentUserId) {
                        $newCount = $messages->where('receiver_id', $currentUserId)
                            ->where('is_read', false)
                            ->count();

                        return [
                            'user' => User::find($userId),
                            'new_count' => $newCount,
                        ];
                    });

                $view->with('chatUsers', $users);
            } else {
                $view->with('chatUsers', collect());
            }
        });

        // Share notifications
        try {
            $userId = Auth::id() ?? 0;

            View::share('notifications', Cache::remember("notifications_user_{$userId}", 3600, function () use ($userId) {
                return NotificationUser::with('notification')
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }));
        } catch (\Throwable $e) {
            View::share('notifications', collect());
        }
    }
}
