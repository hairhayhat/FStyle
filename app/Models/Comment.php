<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'status',
        'rating',
        'is_accurate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function media()
    {
        return $this->hasMany(CommentMedia::class);
    }

    public function getIsAccurateTextAttribute()
    {
        return $this->is_accurate ? 'Có' : 'Không';
    }
}
