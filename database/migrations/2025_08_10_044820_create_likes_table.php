<?php

// database/migrations/xxxx_create_likes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained('customers') // specify the table
                ->onDelete('cascade');
            $table->foreignId('post_id')
                ->constrained('post_s')   // your posts table
                ->onDelete('cascade');
            $table->timestamps();

            // ensure a customer can like a post only once
            $table->unique(['customer_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
