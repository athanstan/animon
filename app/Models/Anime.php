<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnimeRating;
use App\Enums\AnimeSeason;
use App\Enums\AnimeStatus;
use App\Enums\AnimeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class Anime extends Model
{
    protected $fillable = [
        'mal_id',
        'slug',
        'title',
        'title_english',
        'title_japanese',
        'image_url',
        'episodes',
        'status',
        'type',
        'season',
        'rating',
        'score',
        'synopsis',
        'year',
    ];

    protected function casts(): array
    {
        return [
            'mal_id' => 'integer',
            'episodes' => 'integer',
            'score' => 'float',
            'year' => 'integer',
            'type' => AnimeType::class,
            'status' => AnimeStatus::class,
            'season' => AnimeSeason::class,
            'rating' => AnimeRating::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Anime $anime) {
            if (empty($anime->slug)) {
                $anime->slug = Str::slug($anime->title);

                // Ensure uniqueness
                $originalSlug = $anime->slug;
                $counter = 1;
                while (static::where('slug', $anime->slug)->exists()) {
                    $anime->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }
}
