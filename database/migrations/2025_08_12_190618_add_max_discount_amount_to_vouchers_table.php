<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxDiscountAmountToVouchersTable extends Migration
{
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Thêm cột max_discount_amount kiểu số nguyên (unsigned), nullable
            $table->unsignedInteger('max_discount_amount')->nullable()->after('value');
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('max_discount_amount');
        });
    }
}
