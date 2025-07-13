<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    // Cho phép gán dữ liệu hàng loạt cho cột name
    protected $fillable = ['name'];

    // Một role có nhiều users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
