<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // Tạo cột id kiểu BIGINT AUTO_INCREMENT, là khóa chính
            $table->string('name'); // Cột name kiểu VARCHAR (TEXT thì có thể dùng text())
            $table->timestamps(); // Tạo cột created_at và updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
