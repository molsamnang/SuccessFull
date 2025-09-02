<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_s', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->string('image')->nullable(); // single image (optional)
            $table->json('images')->nullable();  // multiple images stored as JSON array
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_s');
    }
};
