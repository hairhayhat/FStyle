<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_discount_amount',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'active',
    ];
    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
    protected $dates = [
        'starts_at',
        'expires_at',
    ];

    /**
     * Kiểm tra voucher có hợp lệ với giá trị đơn hàng không
     */
    public function isValidForAmount(float $orderAmount): bool
    {
        if (!$this->active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false; // chưa đến ngày bắt đầu
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false; // đã hết hạn
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false; // vượt quá số lượt sử dụng
        }

        if ($this->min_order_amount !== null && $orderAmount < $this->min_order_amount) {
            return false; // đơn hàng nhỏ hơn đơn tối thiểu
        }

        return true;
    }
}
