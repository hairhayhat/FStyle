<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentMedia extends Model
{
    protected $fillable = [
        'comment_id',
        'file_path',
        'type',
    ];

    public $timestamps = false;

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function getIsImageAttribute()
    {
        return $this->type === 'image';
    }

    public function getIsVideoAttribute()
    {
        return $this->type === 'video';
    }
}
