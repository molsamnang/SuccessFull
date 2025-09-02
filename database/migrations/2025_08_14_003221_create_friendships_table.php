<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // sender
            $table->unsignedBigInteger('friend_id'); // receiver
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('customers')->onDelete('cascade');

            // prevent duplicates
            $table->unique(['user_id', 'friend_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
