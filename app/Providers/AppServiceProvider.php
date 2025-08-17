<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('categories', Cache::remember('categories', 3600, fn() => Category::all()));
        View::share('notifications', Cache::remember('notifications', 60, fn() => Notification::latest()->take(10)->get()));
    }
}
