<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('friend_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'blocked', 'rejected'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'friend_id']);
            $table->unique(['friend_id', 'user_id']); // Ensure uniqueness in both directions
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
