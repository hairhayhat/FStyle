<?php // Định nghĩa các route web của ứng dụng

use App\Models\Favorite; // Model Favorite (dùng ở một số controller)
use Illuminate\Support\Facades\Route; // Facade định nghĩa route
use App\Http\Controllers\VNPayController; // Xử lý VNPay
use App\Http\Controllers\ProfileController; // Profile (nếu dùng)
use App\Http\Controllers\Admin\SizeController; // Quản trị Size
use App\Http\Controllers\Admin\UserController; // Quản trị User
use App\Http\Controllers\Admin\ColorController; // Quản trị Màu sắc
use App\Http\Controllers\Admin\OrderController; // Quản trị Đơn hàng
use App\Http\Controllers\Client\CartController; // Giỏ hàng (client)
use App\Http\Controllers\Client\HomeController; // Trang chủ (client)
use App\Http\Controllers\Admin\VoucherController; // Quản trị Voucher
use App\Http\Controllers\Client\SearchController; // Tìm kiếm (client)
use App\Http\Controllers\Admin\CategoryController; // Quản trị Danh mục
use App\Http\Controllers\Client\AddressController; // Địa chỉ (client)
use App\Http\Controllers\Client\CommentController; // Bình luận (client)
use App\Http\Controllers\Admin\CommentController as AdminCommentController; // Bình luận (admin)
use App\Http\Controllers\Admin\DashboardController; // Bảng điều khiển admin
use App\Http\Controllers\Client\CheckoutController; // Thanh toán (client)
use App\Http\Controllers\Client\FavoriteController; // Yêu thích (client)
use App\Http\Controllers\Admin\NotificationController; // Thông báo (admin)
use App\Http\Controllers\Admin\ProductVariantController; // Biến thể sản phẩm (admin)
use App\Http\Controllers\Admin\ProfileController as AdminProfileController; // Hồ sơ (admin)
use App\Http\Controllers\Client\ChatController; // Chat (client)
use App\Http\Controllers\Client\ProfileController as ClientProfileController; // Hồ sơ (client)
use App\Http\Controllers\Client\VoucherController as ClientVoucherController; // Voucher (client)
use App\Http\Controllers\Client\NotificationController as ClientNotificationController; // Thông báo (client)
use Illuminate\Support\Facades\Broadcast; // Pusher/Broadcast routes

Broadcast::routes(['middleware' => []]); // Đăng ký route broadcast (websockets), có thể thêm middleware khi cần

// Public routes (không yêu cầu đăng nhập)
Route::get('/', [HomeController::class, 'index'])->name('welcome'); // Trang chủ
Route::get('/api/product/{slug}', [HomeController::class, 'show'])->name('product.detail.api'); // API chi tiết sản phẩm cho client
Route::get('/product/{slug}', [HomeController::class, 'detailProduct'])->name('product.detail'); // Trang chi tiết sản phẩm
Route::get('/search/ajax', [SearchController::class, 'ajaxSearchProducts'])->name('search.ajax.products'); // Tìm kiếm ajax
Route::get('/category/{slug}', [SearchController::class, 'searchCategory'])->name('search.category'); // Lọc theo danh mục
Route::get('/filter-products', [SearchController::class, 'filter'])->name('products.filter'); // Bộ lọc sản phẩm
Route::get('/product/{slug}/comments', [CommentController::class, 'ajaxComments'])->name('product.ajaxComments'); // Load comment theo sản phẩm



// Admin routes (yêu cầu auth + verified + admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard'); // Bảng điều khiển admin

    Route::get('/profile', [AdminProfileController::class, 'editProfile'])->name('admin.profile.edit'); // Form chỉnh sửa hồ sơ admin
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update'); // Lưu hồ sơ
    // Category Routes
    
    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index'); // Danh sách danh mục
    Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create'); // Form tạo danh mục
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store'); // Lưu danh mục
    Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit'); // Form sửa danh mục
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update'); // Cập nhật danh mục
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('admin.category.destroy'); // Xoá danh mục
    // Product Routes
    Route::resource('product', \App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.product.index',
        'create' => 'admin.product.create',
        'store' => 'admin.product.store',
        'show' => 'admin.product.show',
        'edit' => 'admin.product.edit',
        'update' => 'admin.product.update',
        'destroy' => 'admin.product.destroy',
        'deleteGallery' => 'admin.product.gallery.delete', // Tên route phụ nếu dùng xoá ảnh gallery
    ]);
    // Product Variant Routes (AJAX)
    Route::prefix('product/variant')->group(function () { // Các hành động với biến thể (qua AJAX)
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

    // Quản lý người dùng
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/lock', [UserController::class, 'lock'])->name('admin.users.lock');
    Route::post('/users/{user}/unlock', [UserController::class, 'unlock'])->name('admin.users.unlock');


    // Quản lý đơn hàng
    Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
    Route::post('/order/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
    Route::get('/orders/{code}', [OrderController::class, 'detail'])->name('admin.order.detail');

    // Thông báo (admin)
    Route::get('/notification/fetch', [NotificationController::class, 'fetchNotification'])->name('admin.fetch.notification');
    Route::post('/notification/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notification.markAsRead');
    Route::post('/notification/{id}/assign-admin', [NotificationController::class, 'assignedAdmin'])->name('admin.notification.assignAdmin');

    // Bình luận (admin)
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::post('/comments/toggle-status/{comment}', [AdminCommentController::class, 'toggleStatus'])->name('admin.comments.toggleStatus');
    Route::get('/comment/{comment}', [AdminCommentController::class, 'show'])->name('comments.show');

    // Chat (admin)
    Route::get('/chat/{user}', [ChatController::class, 'index']);
    Route::post('/chat/send/{user}', [ChatController::class, 'store']);
    Route::post('/chat/edit/{chatMessage}', [ChatController::class, 'edit']);
    Route::post('/chat/delete/{chatMessage}', [ChatController::class, 'destroy']);
    Route::post('/chat/mark-as-read/{chatMessage}', [ChatController::class, 'markAsRead']);

});

// Client routes (yêu cầu đăng nhập role client)
Route::middleware(['auth', 'verified', 'client'])->prefix('client')->group(function () {
    Route::get('/welcome', [HomeController::class, 'index'])->name('client.welcome'); // Trang chào mừng client

    Route::get('dashboard', [ClientProfileController::class, 'dashboard'])->name('client.dashboard'); // Bảng điều khiển client
    // routes/web.php
    Route::get('/change-password', [ClientProfileController::class, 'changePassword'])->name('client.changePassword'); // Form đổi mật khẩu
    Route::post('/change-password', [ClientProfileController::class, 'updatePassword'])->name('client.updatePassword'); // Xử lý đổi mật khẩu


    Route::get('/profile', [ClientProfileController::class, 'renderProfile'])->name('client.profile'); // Trang hồ sơ
    Route::post('/profile', [ClientProfileController::class, 'updateProfile'])->name('client.profile.update'); // Cập nhật hồ sơ
    Route::post('/profile/change-password', [ClientProfileController::class, 'changePassword'])->name('client.change.passowrd'); // Đổi mật khẩu nhanh

    Route::get('/address', [AddressController::class, 'create'])->name('client.address'); // Trang quản lý địa chỉ
    Route::post('/address', [AddressController::class, 'store'])->name('client.address.create'); // Lưu địa chỉ mới
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('client.address.update'); // Cập nhật địa chỉ
    Route::get('/api/address/{id}/edit', [AddressController::class, 'edit'])->name('api.address.edit'); // Lấy dữ liệu địa chỉ qua API
    Route::delete('/address/destroy/{id}', [AddressController::class, 'destroy'])->name('client.address.destroy'); // Xoá địa chỉ

    Route::post('/products/{product}/favorite', [FavoriteController::class, 'favorite'])->name('client.products.favorite'); // Yêu thích sản phẩm
    Route::get('/wishlist', [FavoriteController::class, 'wishlist'])->name('client.wishlist'); // Danh sách yêu thích
    Route::get('/products/{product}/variants', [FavoriteController::class, 'getProductVariants'])->name('client.products.variants'); // API biến thể theo sản phẩm
    Route::post('/products/{product}/unfavorite', [FavoriteController::class, 'unfavorite'])->name('client.products.unfavorite'); // Bỏ yêu thích

    Route::get('/cart', [CartController::class, 'index'])->name('client.cart'); // Trang giỏ hàng
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->middleware('check.locked')->name('client.cart.add'); // Thêm vào giỏ (chặn nếu bị khoá)
    Route::get('/cart-dropdown', [CartController::class, 'getDropdownHTML']); // API dropdown giỏ
    Route::post('/remove-from-cart', [CartController::class, 'remove'])->middleware('check.locked')->name('cart.remove'); // Xoá khỏi giỏ
    Route::put('/cart/{id}', [CartController::class, 'updateQuantity'])->middleware('check.locked')->name('cart.updateQuantity'); // Cập nhật số lượng
    Route::post('/buy-now', [CartController::class, 'buyNow'])->middleware('check.locked')->name('client.cart.buyNow'); // Mua ngay

    Route::get('/checkout/index', [CheckoutController::class, 'index'])->name('client.checkout.index'); // Trang danh sách đơn hàng
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('client.checkout'); // Trang thanh toán
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->middleware('check.locked')->name('client.checkout.store'); // Tạo đơn hàng
    Route::get('/checkout/{code}', [CheckoutController::class, 'detail'])->name('client.checkout.detail'); // Chi tiết đơn theo mã
    Route::post('/order/{id}/update-status', [CheckoutController::class, 'updateStatus'])->middleware('check.locked')->name('client.update.status'); // Cập nhật trạng thái đơn
    Route::post('/order/{id}/cancel', [CheckoutController::class, 'cancelOrder'])->middleware('check.locked')->name('client.cancel.order'); // Huỷ đơn
    Route::post('/checkout/{order}/rebuy', [CheckoutController::class, 'reBuy'])->middleware('check.locked')->name('client.checkout.rebuy'); // Mua lại đơn cũ
    Route::get('/checkout/edit/{code}', [CheckoutController::class, 'edit'])->name('client.checkout.edit'); // Form sửa đơn (địa chỉ, ghi chú...)
    Route::put('/checkout/update/{code}', [CheckoutController::class, 'update'])->middleware('check.locked')->name('client.checkout.update'); // Cập nhật đơn
    Route::get('/checkout/apiDetail/{code}', [CheckoutController::class, 'apiDetail']); // API chi tiết đơn

    Route::post('/voucher/check', [ClientVoucherController::class, 'check'])->name('voucher.check'); // Kiểm tra voucher

    Route::get('/notification/fetch', [ClientNotificationController::class, 'fetchNotification'])->name('client.fetch.notification'); // Fetch thông báo client
    Route::post('/notification/{id}/mark-as-read', [ClientNotificationController::class, 'markAsRead'])->name('client.notification.markAsRead'); // Đánh dấu đã đọc

    Route::get('/payment/vnpay/ipn', [VNPayController::class, 'ipn'])->name('vnpay.ipn'); // IPN từ VNPay
    Route::get('/payment/vnpay/return', [VNPayController::class, 'return'])->name('vnpay.return'); // Return URL VNPay

    Route::post('/comment/store', [CommentController::class, 'store'])->name('client.comment.store'); // Gửi bình luận
    Route::get('/comments', [CommentController::class, 'index'])->name('client.comments'); // Danh sách bình luận

    Route::get('/chat/{user}', [ChatController::class, 'index']); // Trang chat với 1 user
    Route::post('/chat/send/{user}', [ChatController::class, 'store']); // Gửi tin nhắn
    Route::post('/chat/edit/{chatMessage}', [ChatController::class, 'edit']); // Sửa tin nhắn
    Route::post('/chat/delete/{chatMessage}', [ChatController::class, 'destroy']); // Xoá tin nhắn
    Route::post('/chat/mark-as-read/{chatMessage}', [ChatController::class, 'markAsRead']); // Đánh dấu đã đọc
});
require __DIR__ . '/auth.php'; // Gắn các route xác thực (login/register)
