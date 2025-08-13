<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // mã voucher
            $table->enum('type', ['fixed','percent'])->default('fixed'); // kiểu: tiền cố định hoặc %
            $table->decimal('value', 12, 2); // giá trị (số tiền hoặc %)
            $table->decimal('min_order_amount', 12, 2)->default(0); // giá trị tối thiểu đơn hàng
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->unsignedInteger('usage_limit')->nullable(); // tổng số lần có thể dùng (null = vô hạn)
            $table->unsignedInteger('used_count')->default(0); // đã dùng bao nhiêu lần
            $table->boolean('active')->default(true);
            $table->json('meta')->nullable(); // lưu thêm điều kiện (ví dụ chỉ cho category X...)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
