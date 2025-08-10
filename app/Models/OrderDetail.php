<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'color',
        'size',
        'quantity',
        'price',
    ];

    /**
     * Quan hệ đến Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ đến ProductVariant
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
