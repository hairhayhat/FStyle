<?php // Tệp PHP

namespace App\Models; // Namespace cho các model ứng dụng

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait hỗ trợ factory
use Illuminate\Database\Eloquent\Model; // Lớp cơ sở Model của Eloquent

class Product extends Model // Model Sản phẩm
{
    use HasFactory; // Kích hoạt factory cho Product

    protected $fillable = ['name', 'slug', 'image', 'description', 'category_id', 'views']; // Các trường được phép gán hàng loạt

    public function category() // Quan hệ: sản phẩm thuộc về một danh mục
    {
        return $this->belongsTo(Category::class);
    }

    public function variants() // Quan hệ: sản phẩm có nhiều biến thể
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function galleries() // Quan hệ: sản phẩm có nhiều ảnh gallery
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function favoritedBy() // Quan hệ n-n: người dùng đánh dấu yêu thích sản phẩm
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps(); // Lưu thời điểm tạo/cập nhật trong bảng pivot
    }

    public function isFavoritedByUser($userId = null) // Kiểm tra sản phẩm đã được user yêu thích chưa
    {
        if (!$userId && auth()->check()) { // Nếu không truyền userId thì lấy từ auth
            $userId = auth()->id();
        }

        return $userId
            ? $this->favoritedBy()->where('user_id', $userId)->exists() // Tồn tại bản ghi trong pivot
            : false; // Nếu không có userId trả về false
    }

    public function favorites() // Quan hệ 1-n: các bản ghi favorite liên quan đến sản phẩm
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments() // Quan hệ 1-n: bình luận của sản phẩm
    {
        return $this->hasMany(Comment::class);
    }

    public function activeComments() // Quan hệ 1-n kèm điều kiện: chỉ lấy bình luận đang active
    {
        return $this->hasMany(Comment::class)->where('status', 1);
    }

    public function inUse() // Kiểm tra sản phẩm đang được dùng ở giỏ hàng/đơn hàng thông qua biến thể
    {
        return $this->variants()
            ->where(function ($q) {
                $q->whereHas('cartDetails') // Biến thể nằm trong giỏ hàng
                    ->orWhereHas('orderDetails'); // Hoặc nằm trong chi tiết đơn hàng
            })
            ->exists(); // Có bất kỳ biến thể nào đang được dùng
    }

}
