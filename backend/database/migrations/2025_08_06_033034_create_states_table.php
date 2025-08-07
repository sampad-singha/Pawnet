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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade'); // Using foreignId
            $table->char('country_code', 2);
            $table->string('fips_code')->nullable();
            $table->string('iso2')->nullable();
            $table->string('type')->nullable();
            $table->integer('level')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('native')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
