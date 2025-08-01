<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('post_s', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('poster_head');
            $table->json('poster_sizes')->nullable(); // example: ["A4", "A3"]
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('post_s');
    }
};
