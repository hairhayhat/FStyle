<?php

use App\Models\Favorite;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Client\SearchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Client\AddressController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\FavoriteController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\VoucherController as ClientVoucherController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;

Route::get('/', [HomeController::class, 'index'])->name('client.welcome');
Route::get('/api/product/{slug}', [HomeController::class, 'show'])->name('product.detail.api');
Route::get('/product/{slug}', [HomeController::class, 'detailProduct'])->name('product.detail');
Route::get('/search/ajax', [SearchController::class, 'ajaxSearchProducts'])->name('search.ajax.products');
Route::get('/category/{slug}', [SearchController::class, 'searchCategory'])->name('search.category');
Route::get('/filter-products', [SearchController::class, 'filter'])->name('products.filter');



Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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
        'deleteGallery' => 'admin.product.gallery.delete',
    ]);
    // Product Variant Routes (AJAX)
    Route::prefix('product/variant')->group(function () {
        Route::post('/store', [ProductVariantController::class, 'store'])->name('admin.product.variant.store');
        Route::put('/{variant}/update', [ProductVariantController::class, 'update'])->name('admin.product.variant.update');
        Route::delete('/{variant}/delete', [ProductVariantController::class, 'destroy'])->name('admin.product.variant.destroy');
    });
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

    // Voucher Routes
    Route::resource('vouchers', VoucherController::class)->names([
        'index' => 'admin.vouchers.index',
        'create' => 'admin.vouchers.create',
        'store' => 'admin.vouchers.store',
        'show' => 'admin.vouchers.show',
        'edit' => 'admin.vouchers.edit',
        'update' => 'admin.vouchers.update',
        'destroy' => 'admin.vouchers.destroy',
    ]);

    // Apply voucher (test hoặc checkout)
    Route::post('voucher/apply', [VoucherController::class, 'apply'])->name('admin.voucher.apply');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');

    Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
    Route::post('/order/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
    Route::get('/order/{code}', [OrderController::class, 'detail'])->name('admin.order.detail');

    Route::get('/notification/fetch', [NotificationController::class, 'fetchNotification'])->name('admin.fetch.notification');
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

    Route::post('/products/{product}/favorite', [FavoriteController::class, 'favorite'])->name('client.products.favorite');
    Route::get('/wishlist', [FavoriteController::class, 'wishlist'])->name('client.wishlist');
    Route::get('/products/{product}/variants', [FavoriteController::class, 'getProductVariants'])->name('client.products.variants');
    Route::post('/products/{product}/unfavorite', [FavoriteController::class, 'unfavorite'])->name('client.products.unfavorite');

    Route::get('/cart', [CartController::class, 'index'])->name('client.cart');
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('client.cart.add');
    Route::get('/cart-dropdown', [CartController::class, 'getDropdownHTML']);
    Route::post('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/buy-now', [CartController::class, 'buyNow'])->name('client.cart.buyNow');

    Route::get('/checkout/index', [CheckoutController::class, 'index'])->name('client.checkout.index');
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('client.checkout');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('client.checkout.store');
    Route::get('/checkout/{code}', [CheckoutController::class, 'detail'])->name('client.checkout.detail');
    Route::post('/order/{id}/update-status', [CheckoutController::class, 'updateStatus'])->name('client.update.status');
    Route::post('/order/{id}/cancel', [CheckoutController::class, 'cancelOrder'])->name('client.cancel.order');
    Route::post('/checkout/{order}/rebuy', [CheckoutController::class, 'reBuy'])->name('client.checkout.rebuy');

    Route::post('/voucher/check', [ClientVoucherController::class, 'check'])->name('voucher.check');

});
require __DIR__ . '/auth.php';
