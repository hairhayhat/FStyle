<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Client\AddressController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
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
    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');

    Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

});

Route::middleware(['auth', 'verified', 'client'])->prefix('client')->group(function () {
    Route::get('/welcome', function () {
        return view('client.welcome');
    })->name('client.welcome');

    Route::get('dashboard', function () {
        return view("client.dashboard.dashboard");
    })->name('client.dashboard');

    Route::get('/profile', [ClientProfileController::class, 'renderProfile'])->name('client.profile');
    Route::post('/profile', [ClientProfileController::class, 'updateProfile'])->name('client.profile.update');
    Route::post('/profile/change-password', [ClientProfileController::class, 'changePassword'])->name('client.change.passowrd');

    Route::get('/address', [AddressController::class, 'create'])->name('client.address');
    Route::post('/address', [AddressController::class, 'store'])->name('client.address.create');
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('client.address.update');
    Route::get('/api/address/{id}/edit', [AddressController::class, 'edit'])->name('api.address.edit');


});

require __DIR__ . '/auth.php';
