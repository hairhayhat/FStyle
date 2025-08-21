<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // id INT PRIMARY KEY AUTO_INCREMENT
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // user_id INT REFERENCES users(id)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // product_id INT REFERENCES products(id)
            $table->text('content')->nullable(); // content TEXT
            $table->boolean('status')->default(true); // status BOOLEAN DEFAULT TRUE
            $table->boolean('is_accurate')->default(false);
            $table->integer('rating')->nullable(); // rating INT
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

