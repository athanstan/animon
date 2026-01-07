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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mal_id')->unique(); // Jikan API ID which is the MAL ID
            $table->string('title');
            $table->string('title_english')->nullable();
            $table->string('title_japanese')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedSmallInteger('episodes')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('season', 10)->nullable();
            $table->string('rating', 50)->nullable();
            $table->decimal('score', 4, 2)->nullable();
            $table->text('synopsis')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->timestamps();

            // Indexes for search
            $table->index('title');
            $table->index(['type', 'status']);
            $table->index(['season', 'year']);
            $table->index('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
