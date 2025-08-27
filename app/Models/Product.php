<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'description', 'category_id', 'views'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function isFavoritedByUser($userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }

        return $userId
            ? $this->favoritedBy()->where('user_id', $userId)->exists()
            : false;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function activeComments()
    {
        return $this->hasMany(Comment::class)->where('status', 1);
    }

    public function inUse()
    {
        return $this->variants()->whereHas('cartDetails')
            ->orWhereHas('orderDetails')
            ->exists();
    }


}
