<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'usage_limit',        // null = không giới hạn
        'used_count',         // default 0
        'active',             // boolean
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'active' => 'boolean',
        'used_count' => 'integer',
        'usage_limit' => 'integer',
        'value' => 'float',
        'max_discount_amount' => 'float',
        'min_order_amount' => 'float',
    ];

    /**
     * Scope: chỉ voucher đang bật
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Kiểm tra voucher có hợp lệ với giá trị đơn hàng không
     */
    public function isValidForAmount(float $orderAmount): bool
    {
        if (!$this->active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false; // chưa đến ngày bắt đầu
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false; // đã hết hạn
        }

        // usage_limit: null = không giới hạn; 0 = hết lượt (tuỳ quy ước: khuyến nghị null = không giới hạn)
        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit) {
            return false; // vượt quá số lượt sử dụng
        }

        if (!is_null($this->min_order_amount) && $orderAmount < $this->min_order_amount) {
            return false; // đơn hàng nhỏ hơn đơn tối thiểu
        }

        return true;
    }

    /**
     * Tính số tiền giảm giá
     */
    public function calculateDiscount(float $orderAmount): float
    {
        $discount = 0.0;

        if ($this->type === 'percent') {
            // Clamp phần trăm phòng thủ
            $rate = max(0.0, min(100.0, (float) $this->value));
            $discount = $orderAmount * ($rate / 100.0);

            if (!is_null($this->max_discount_amount)) {
                $discount = min($discount, (float) $this->max_discount_amount);
            }
        } elseif ($this->type === 'fixed') {
            $discount = max(0.0, (float) $this->value);
        }

        // Không để giảm vượt quá giá trị đơn hàng
        return round(min($discount, $orderAmount), 2);
    }

    /**
     * Gọi khi redeem voucher thành công (tăng used_count an toàn)
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
