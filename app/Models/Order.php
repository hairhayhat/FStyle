<?php // Tệp PHP

namespace App\Models; // Namespace cho model

use Illuminate\Database\Eloquent\Model; // Lớp Model Eloquent
use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait factory

class Order extends Model // Model Đơn hàng
{
    use HasFactory; // Kích hoạt factory

    protected $fillable = [ // Các trường có thể gán hàng loạt
        'user_id',
        'code',
        'address_id',
        'total_amount',
        'status',
        'note'
    ];

    public function user() // Quan hệ: đơn hàng thuộc về 1 người dùng
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress() // Quan hệ: địa chỉ giao hàng (khóa ngoại address_id)
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function payment() // Quan hệ 1-1: thông tin thanh toán
    {
        return $this->hasOne(Payment::class);
    }
    public function orderDetails() // Quan hệ 1-n: các dòng chi tiết đơn hàng
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function orderVoucher() // Quan hệ 1-1: phiếu giảm giá áp dụng cho đơn
    {
        return $this->hasOne(OrderVoucher::class);
    }
    
}
