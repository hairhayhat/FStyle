<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
      
        if (DB::connection()->getDriverName() !== 'sqlite') {
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
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
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
    }
};
