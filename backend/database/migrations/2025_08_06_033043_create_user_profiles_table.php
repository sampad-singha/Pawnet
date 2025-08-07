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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone_number');
            $table->boolean('phone_verified')->default(false);
            $table->string('address')->nullable();
//            $table->string('city')->nullable();
//            $table->string('state')->nullable();
//            $table->string('country')->nullable();
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            $table->text('bio')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
