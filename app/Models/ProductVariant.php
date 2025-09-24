<?php // Tệp PHP

namespace App\Models; // Namespace cho model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait factory
use Illuminate\Database\Eloquent\Model; // Lớp Model Eloquent
use App\Models\Product; // Import model Product để khai báo quan hệ

class ProductVariant extends Model // Model Biến thể sản phẩm
{
    use HasFactory; // Kích hoạt factory

    protected $table = 'product_variants'; // Tên bảng tương ứng (nếu khác chuẩn)

    protected $fillable = [ // Các trường được gán hàng loạt
        'product_id',
        'color_id',
        'size_id',
        'import_price',
        'sale_price',
        'quantity'
    ];

    /**
     * Sản phẩm mà biến thể thuộc về
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

    public function cartDetails() // Quan hệ 1-n: các dòng chi tiết giỏ liên quan tới biến thể
    {
        return $this->hasMany(CartDetail::class);
    }

    public function orderDetails() // Quan hệ 1-n: các dòng chi tiết đơn hàng liên quan tới biến thể
    {
        return $this->hasMany(OrderDetail::class);
    }
}
