<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',               // 'percent' hoặc 'fixed'
        'value',              // % giảm hoặc số tiền cố định
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

    /**
     * Tính số tiền giảm giá
     */
    public function calculateDiscount(float $orderAmount): float
    {
        $discount = 0;

        if ($this->type === 'percent') {
            $discount = $orderAmount * ($this->value / 100);

            // Nếu có giới hạn giảm tối đa thì áp dụng
            if ($this->max_discount_amount !== null) {
                $discount = min($discount, $this->max_discount_amount);
            }
        } elseif ($this->type === 'fixed') {
            $discount = $this->value;
        }

        // Không để giảm vượt quá giá trị đơn hàng
        return min($discount, $orderAmount);
    }
    public function isUsed(): bool
    {
        return $this->orderVouchers()->exists();
    }

    public function orderVouchers()
    {
        return $this->hasMany(OrderVoucher::class);
    }

}
