<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'total_amount',
        'status',
        'paid_at',
        'gateway_data',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'gateway_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
