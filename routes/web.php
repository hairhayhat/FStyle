<?php

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.welcome');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');

    Route::get('/profile', [AdminProfileController::class, 'editProfile'])->name('admin.profile.edit');

});

Route::middleware(['auth', 'verified', 'client'])->prefix('client')->group(function () {
    Route::get('/welcome', function () {
        return view('client.welcome');
    })->name('client.welcome');
});

require __DIR__ . '/auth.php';
