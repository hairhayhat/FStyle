<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'address_id',
        'total_amount',
        'status',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function orderVoucher()
    {
        return $this->hasOne(OrderVoucher::class);
    }
}
