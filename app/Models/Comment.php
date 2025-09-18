<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'content',
        'status',
        'rating',
        'is_accurate',
    ];

    protected $appends = ['time_ago'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function media()
    {
        return $this->hasMany(CommentMedia::class);
    }

    public function getIsAccurateTextAttribute()
    {
        return $this->is_accurate ? 'Có' : 'Không';
    }

    public function getTimeAgoAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
