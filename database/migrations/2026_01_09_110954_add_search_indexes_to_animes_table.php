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
        Schema::table('animes', function (Blueprint $table) {
            // Individual indexes on title fields for LIKE searches
            // While LIKE with leading wildcard (%term%) can't use index for prefix matching,
            // the index still helps with sorting and filtering
            $table->index('title_english');
            $table->index('title_japanese');

            // Composite index for the ORDER BY clause
            // Since we order by score DESC, this helps the query optimizer
            // Note: title is already indexed in the original migration
            $table->index(['score', 'id']); // id added for tie-breaking and consistent ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropIndex(['title_english']);
            $table->dropIndex(['title_japanese']);
            $table->dropIndex(['score', 'id']);
        });
    }
};
