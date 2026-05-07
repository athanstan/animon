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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('number');
            $table->string('title');
            $table->string('title_japanese')->nullable();
            $table->string('title_romanji')->nullable();
            $table->timestamp('aired')->nullable();
            $table->decimal('score', 3, 2)->nullable();
            $table->boolean('filler')->default(false);
            $table->boolean('recap')->default(false);
            $table->string('url')->nullable();
            $table->string('forum_url')->nullable();
            $table->timestamps();

            $table->unique(['anime_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
