<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnimeRating;
use App\Enums\AnimeSeason;
use App\Enums\AnimeStatus;
use App\Enums\AnimeType;
use Illuminate\Database\Eloquent\Model;

final class Anime extends Model
{
    protected $fillable = [
        'mal_id',
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
}
