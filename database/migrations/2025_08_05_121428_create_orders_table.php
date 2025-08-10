<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->integer('total_amount');
            $table->enum('status', [
                'pending',       // Chờ xác nhận
                'confirmed',     // Đã xác nhận
                'packaging',     // Đang đóng gói
                'shipped',       // Đang giao
                'delivered',     // Đã giao
                'cancelled',     // Đã hủy
                'returned'       // Đã trả hàng
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
