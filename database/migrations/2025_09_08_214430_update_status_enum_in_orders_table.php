<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Thay đổi cột enum, thêm 2 trạng thái mới
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'confirmed',
            'packaging',
            'shipped',
            'delivered',
            'rated',
            'cancelled',
            'returned'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Rollback về enum cũ
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'confirmed',
            'packaging',
            'shipped',
            'delivered',
            'cancelled',
            'returned'
        ) NOT NULL DEFAULT 'pending'");
    }
};
