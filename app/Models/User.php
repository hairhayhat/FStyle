<?php // Tệp PHP

namespace App\Models; // Namespace cho model

use Illuminate\Contracts\Auth\MustVerifyEmail; // Interface bắt buộc xác minh email
use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait factory
use Illuminate\Foundation\Auth\User as Authenticatable; // Lớp người dùng có sẵn của Laravel
use Illuminate\Notifications\Notifiable; // Trait gửi thông báo
use App\Notifications\CustomVerifyEmail; // Notification tuỳ chỉnh xác minh email
use App\Notifications\CustomResetPassword; // Notification tuỳ chỉnh đặt lại mật khẩu

class User extends Authenticatable implements MustVerifyEmail // Model User, yêu cầu verify email
{
    use HasFactory, Notifiable; // Bật factory và thông báo

    protected $fillable = [ // Các trường có thể gán hàng loạt
        'name',
        'email',
        'password',
        'provider_id',
        'avatar',
        'phone',
        'email_verified_at',
        'role_id',
        'is_locked'
    ];

    protected $hidden = [ // Ẩn các trường khi serialize
        'password',
        'remember_token',
    ];

    protected function casts(): array // Ép kiểu các thuộc tính
    {
        return [
            'email_verified_at' => 'datetime', // Thời gian xác minh email
            'password' => 'hashed', // Tự động hash khi gán password
            'is_locked' => 'boolean', // Khóa tài khoản: bool
        ];
    }

    // Relationships
    public function role() // Vai trò của người dùng
    {
        return $this->belongsTo(Role::class);
    }

    public function addresses() // Danh sách địa chỉ giao hàng của người dùng
    {
        return $this->hasMany(Address::class);
    }

    public function favorites() // Sản phẩm yêu thích (n-n qua bảng favorites)
    {
        return $this->belongsToMany(Product::class, 'favorites')
            ->withTimestamps();
    }

    public function hasFavorited($productId) // Kiểm tra đã yêu thích một sản phẩm hay chưa
    {
        return $this->favorites()->where('product_id', $productId)->exists();
    }

    public function getDefaultAddress() // Lấy địa chỉ mặc định
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function sendEmailVerificationNotification() // Gửi email xác minh
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function sendPasswordResetNotification($token) // Gửi email đặt lại mật khẩu
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function cart() // Quan hệ 1-1 với giỏ hàng
    {
        return $this->hasOne(Cart::class);
    }

    public function comments() // Quan hệ 1-n với bình luận
    {
        return $this->hasMany(Comment::class);
    }
    public function orders() // Quan hệ 1-n với đơn hàng
{
    return $this->hasMany(Order::class);
}

// Helper methods for account locking
public function isLocked() // Kiểm tra tài khoản bị khoá
{
    return $this->is_locked;
}

public function lock() // Khoá tài khoản
{
    $this->update(['is_locked' => true]);
}

public function unlock() // Mở khoá tài khoản
{
    $this->update(['is_locked' => false]);
}

public function canPurchase() // Có thể đặt hàng hay không
{
    return !$this->is_locked;
}

}
