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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // `name` column as string
            // Foreign key reference for `state_id`
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->string('state_code', 255); // `state_code` column
            // Foreign key reference for `country_id`
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->char('country_code', 2); // `country_code` as a fixed-length char(2)
            $table->decimal('latitude', 10, 8); // `latitude` as a decimal type
            $table->decimal('longitude', 11, 8); // `longitude` as a decimal type
            $table->tinyInteger('flag')->default(1); // Default flag set to 1
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities'); // `wikiDataId` as string, nullable with a comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
