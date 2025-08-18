<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->change();
            $table->json('gateway_data')->nullable()->after('method');
            $table->index('method');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->change();
            $table->dropColumn('gateway_data');
            $table->dropIndex(['method']);
        });
    }
};
