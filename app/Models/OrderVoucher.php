<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVoucher extends Model
{
    use HasFactory;

    protected $table = 'order_vouchers';

    protected $fillable = [
        'order_id',
        'voucher_id',
        'code',
        'discount',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'discount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
