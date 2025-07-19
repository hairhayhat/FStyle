<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'price',
        'quantity',
    ];

    /**
     * Sản phẩm thuộc về biến thể
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Màu sắc của biến thể
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Kích thước của biến thể
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
