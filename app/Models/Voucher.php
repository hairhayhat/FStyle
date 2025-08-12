<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'active',
        'meta'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'meta' => 'array',
        'active' => 'boolean',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    // Kiểm tra có hợp lệ hiện tại không
    public function isValidForAmount($orderAmount): bool
    {
        if (!$this->active)
            return false;

        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at))
            return false;
        if ($this->expires_at && $now->gt($this->expires_at))
            return false;

        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit)
            return false;

        if ($orderAmount < $this->min_order_amount)
            return false;

        return true;
    }

    public function incrementUsage(int $by = 1)
    {
        $this->used_count = $this->used_count + $by;
        $this->save();
    }
}
