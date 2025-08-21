<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comment_media', function (Blueprint $table) {
            $table->id(); // id INT PRIMARY KEY AUTO_INCREMENT
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade'); // comment_id INT REFERENCES comment(id) ON DELETE CASCADE
            $table->string('file_path'); // file_path VARCHAR(255) NOT NULL
            $table->enum('type', ['image', 'video'])->default('image'); // type ENUM('image','video') DEFAULT 'image'
            $table->timestamp('created_at')->useCurrent(); // created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_media');
    }
};

