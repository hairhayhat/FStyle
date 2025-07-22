<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $table = 'product_gallery';

    protected $fillable = [
        'product_id',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
