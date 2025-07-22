<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Client\AddressController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('client.welcome');
Route::get('/api/product/{slug}', [HomeController::class, 'show'])->name('product.detail.api');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');

    Route::get('/profile', [AdminProfileController::class, 'editProfile'])->name('admin.profile.edit');
    // Category Routes
    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');

    Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
    // Product Routes
    Route::resource('product', \App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.product.index',
        'create' => 'admin.product.create',
        'store' => 'admin.product.store',
        'show' => 'admin.product.show',
        'edit' => 'admin.product.edit',
        'update' => 'admin.product.update',
        'destroy' => 'admin.product.destroy',
    ]);
    // Product Variant Routes
    Route::resource('product-variants', ProductVariantController::class)->only([
        'store',
        'update',
        'destroy'
    ])->names([
                'store' => 'admin.product-variant.store',
                'update' => 'admin.product-variant.update',
                'destroy' => 'admin.product-variant.destroy',
            ]);
    // Color Routes
    Route::resource('color', ColorController::class)->names([
        'index' => 'admin.color.index',
        'create' => 'admin.color.create',
        'store' => 'admin.color.store',
        'edit' => 'admin.color.edit',
        'update' => 'admin.color.update',
        'destroy' => 'admin.color.destroy',
    ]);

    // Size Routes
    Route::resource('size', SizeController::class)->names([
        'index' => 'admin.size.index',
        'create' => 'admin.size.create',
        'store' => 'admin.size.store',
        'edit' => 'admin.size.edit',
        'update' => 'admin.size.update',
        'destroy' => 'admin.size.destroy',
    ]);
});

Route::middleware(['auth', 'verified', 'client'])->prefix('client')->group(function () {
    Route::get('/welcome', [HomeController::class, 'index'])->name('client.welcome');

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
